<?php

namespace Woopple\Controllers;

use Core\Enums\Permission;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\Hr\FillProfileForm;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\User\User;
use Woopple\Models\User\UserProfile;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;

class HrController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['beginners'],
                        'roles' => [Permission::HR_ACCESS_BEGINNERS->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['fill-profile'],
                        'roles' => [Permission::FILL_PROFILE->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['departments'],
                        'roles' => [Permission::VIEW_DEPARTMENT_LIST->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['teams'],
                        'roles' => [Permission::VIEW_TEAM_LIST->value]
                    ],
                ]
            ]
        ];
    }

    public function actionBeginners(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['status' => AccountStatus::CREATED->value]),
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('beginners', ['dataProvider' => $dataProvider]);
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionFillProfile(int $id): \yii\web\Response|string
    {
        $model = new FillProfileForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $user = User::findOne(['id' => $id]);
            if ($user->fillProfile($model)) {
                $this->sendNotification('success', 'Информация о пользователя была заполнена. Теперь этот сотрудник может авторизоваться и пользоваться системой.');
                $user->changeStatus(AccountStatus::ACTIVE);
                return $this->redirect(['hr/beginners']);
            } else {
                $this->sendNotification('error', 'Возникла ошибка при обновлении профиля данного пользователя.');
            }
        }

        return $this->render('fill-profile', ['model' => $model]);
    }

    public function actionDepartments()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find()
        ]);

        return $this->render('departments', ['dataProvider' => $dataProvider]);
    }

    public function actionTeams()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Team::find()
        ]);

        return $this->render('teams', ['dataProvider' => $dataProvider]);
    }

    protected function sendNotification(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'HR раздел',
            'message' => $message
        ]);
    }
}