<?php

namespace Woopple\Controllers;

use Core\Enums\Permission;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\Hr\FillProfileForm;
use Woopple\Forms\Hr\ManageTeamForm;
use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\Structure\TeamMember;
use Woopple\Models\User\User;
use Woopple\Models\User\UserProfile;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;

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
                        'actions' => [
                            'teams',
                            'get-team',
                            'create-team',
                            'modify-team',
                            'set-team-lead'
                        ],
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
        $users = [];
        $raw = array_filter(User::findAll(['status' => AccountStatus::ACTIVE->value]), function (User $user) {
            return !$user->isTeamLead() && !$user->isDepartmentLead();
        });

        foreach ($raw as $user) {
            $users[$user->id] = $user->profile->fullName();
        }

        $departments = ArrayHelper::map(Department::find()->all(), 'id', 'name');


        return $this->render('teams', compact('users', 'departments'));
    }

    public function actionGetTeam(int $id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Team::find()->where(['department_id' => $id])
        ]);

        return $this->renderPartial('_team', compact('dataProvider'));
    }

    public function actionSetTeamLead(int $team, int $lead): Response
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $team = Team::findOne(['id' => $team]);
        $user = User::findOne(['id' => $lead]);

        if ($user->isTeamLead() || $user->isDepartmentLead()) {
            $this->sendNotification('error', 'Ошибка при попытке привязки руководителя к команде. Уже является руководителем.');
            return $this->redirect('/hr/teams');
        }

        $team->setAttribute('lead', $user->id);
        $result = $team->update() && $team->addMember($user->id, true);

        if ($result) {
            Event::create(new EventData($lead, 'Изменения орг. структуры',
                'Сотрудник был назначен руководителем команды: "' . $team->name . '".',
                new Icon(
                    'fas fa-user-tie',
                    'bg-success'
                )
            ));
        }

        if ($result) {
            $this->sendNotification('success', 'Руководитель успешно закреплён за командой');
        } else {
            $this->sendNotification('error', 'Ошибка при попытке привязки руководителя к команде.');
        }

        return $this->redirect('/hr/teams');
    }

    public function actionCreateTeam(): \yii\web\Response|string
    {
        $model = new ManageTeamForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $team = Team::createWithMembers($model);
            if (!is_null($team)) {
                $this->sendNotification('success', 'Команда успешно добавлена.');
                return $this->redirect('/hr/teams');
            } else {
                $this->sendNotification('error', 'При добавлении команды произошла ошибка.');
            }
        }

        return $this->render('team-modify', compact('model'));
    }

    public function actionModifyTeam(int $id): Response|string
    {
        $team = Team::findOne(['id' => $id]);
        $model = new ManageTeamForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $response = Team::updateTeam($team, $model);
            if (is_null($response)) {
                $this->sendNotification('error', 'При обновлении команды произошла ошибка.');
            } else {
                $this->sendNotification('success', 'Данные команды были успешно обновлены.');
                return $this->redirect('/hr/teams');
            }
            return $this->render('team-modify', compact('model'));
        }

        $result = [];
        $members = TeamMember::findAll(['team_id' => $team->id]);
        array_walk($members, function (TeamMember $member) use (&$result) {
            if (!$member->user->isTeamLead()) {
                $result[] = $member->user->id;
            }
        });

        $model->setAttributes([
            'id' => $team->id,
            'name' => $team->name,
            'lead_id' => $team->lead,
            'department_id' => $team->department_id,
            'members' => $result
        ]);

        return $this->render('team-modify', compact('model'));
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