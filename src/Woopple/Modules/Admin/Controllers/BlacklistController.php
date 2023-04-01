<?php

namespace Woopple\Modules\Admin\Controllers;

use Core\Enums\Permission;
use Core\Enums\Role;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Models\User\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class BlacklistController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [Permission::ACCESS_BLACKLIST_CONTROL->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['block'],
                        'roles' => [Permission::BLOCK_USER->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['unblock'],
                        'roles' => [Permission::UNBLOCK_USER->value]
                    ],
                ]
            ]
        ];
    }

    public function actionIndex(): string
    {
        $users = new ActiveDataProvider([
            'query' => User::find()
        ]);
        return $this->render('index', ['users' => $users]);
    }

    /** @throws \Throwable */
    public function actionBlock(string $login): \yii\web\Response
    {
        $entity = User::findOneByLogin($login);

        if (!is_null($entity) && $this->ensureCanBlock($entity)) {
            $entity->changeStatus(AccountStatus::BLOCKED);
        }

        return $this->redirect('/admin/users/blacklist');
    }

    /** @throws \Throwable */
    public function actionUnblock(string $login): \yii\web\Response
    {
        $entity = User::findOneByLogin($login);

        if (!is_null($entity) && $this->ensureCanBlock($entity)) {
            $entity->changeStatus(AccountStatus::ACTIVE);
        }

        return $this->redirect('/admin/users/blacklist');
    }

    protected function ensureCanBlock(User $user): bool
    {
        /** @var User $current */
        $current = \Yii::$app->user->identity;

        if ($user->login === $current->login) {
            return false;
        }

        if (in_array(Role::ADMIN, $user->roles->getValue())) {
            return false;
        }

        return true;
    }
}