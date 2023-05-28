<?php

namespace Woopple\Modules\Admin\Controllers;

use Core\Enums\Permission;
use Woopple\Models\Restore;
use Woopple\Models\User\User;
use Woopple\Components\Rbac\Rbac;
use Woopple\Modules\Admin\Forms\CreateUserForm;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;

class UserController extends Controller
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
                        'roles' => [Permission::ACCESS_USER_MANAGEMENT->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['security'],
                        'roles' => [Permission::ACCESS_USER_MANAGEMENT->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => [Permission::CREATE_USER->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['modify'],
                        'roles' => [Permission::MODIFY_USER->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['block'],
                        'roles' => [Permission::ACCESS_USER_MANAGEMENT->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['unblock'],
                        'roles' => [Permission::ACCESS_USER_MANAGEMENT->value]
                    ],
                ],
            ]
        ];
    }

    /** @throws Exception */
    public function actionIndex(): string
    {
        $stats = User::stats();
        $users = new ActiveDataProvider([
            'query' => User::find()
        ]);
        return $this->render('index', ['stats' => $stats, 'users' => $users]);
    }

    public function actionSecurity(): string
    {
        $requests = new ActiveDataProvider([
            'query' => Restore::find()
        ]);

        return $this->render('security', ['requests' => $requests]);
    }

    public function actionCreate(): string|\yii\web\Response
    {
        $roles = Rbac::roles();
        $form = new CreateUserForm();

        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
            if ($form->validate()) {
                $response = User::newObject($form)
                    ->fillSecurityProperties();
                if ($response) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', ['form' => $form, 'roles' => $roles]);
    }

}