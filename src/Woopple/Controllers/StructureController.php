<?php

namespace Woopple\Controllers;

use Woopple\Components\Auth\Identity;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\Hr\ManageTeamForm;
use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\Structure\TeamMember;
use Woopple\Models\User\User;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class StructureController extends Controller
{
    public function actionDepartments()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find()
        ]);

        return $this->render('departments', ['dataProvider' => $dataProvider]);
    }

    public function actionTeams()
    {
        /** @var User $current */
        $current = \Yii::$app->user->identity;

        $users = [];
        $raw = array_filter(User::findAll(['status' => AccountStatus::ACTIVE->value]), function (User $user) {
            return !$user->isTeamLead() && !$user->isDepartmentLead();
        });

        foreach ($raw as $user) {
            $users[$user->id] = $user->profile->fullName();
        }

        $departments = ArrayHelper::map(Department::find()->all(), 'id', 'name');

        $teamData = new ActiveDataProvider([
            'query' => Team::find()->where(['department_id' => $current->getDepartment()?->id])
        ]);

        return $this->render('teams', compact('users', 'departments', 'teamData'));
    }

    public function actionGetTeam(int $id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Team::find()->where(['department_id' => $id])
        ]);

        return $this->renderPartial('_team', compact('dataProvider'));
    }

    public function actionMembers()
    {
        /** @var User $current */
        $current = \Yii::$app->user->identity;

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['status' => AccountStatus::ACTIVE->value])
        ]);

        $data = $dataProvider->getModels();

        /** @var User $model */
        foreach ($data as $id => $model) {
            if ($model->getDepartment()?->id != $current->getDepartment()?->id) {
                unset($data[$id]);
            }
        }

        $dataProvider->setModels($data);

        return $this->render('members', compact('dataProvider'));
    }

    public function actionSetTeamLead(int $team, int $lead): Response
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $team = Team::findOne(['id' => $team]);
        $user = User::findOne(['id' => $lead]);

        if ($user->isTeamLead() || $user->isDepartmentLead()) {
            $this->sendNotification('error', 'Ошибка при попытке привязки руководителя к команде. Уже является руководителем.');
            return $this->redirect('/structure/teams');
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

        return $this->redirect('/structure/teams');
    }

    public function actionCreateTeam(): \yii\web\Response|string
    {
        $model = new ManageTeamForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $team = Team::createWithMembers($model);
            if (!is_null($team)) {
                $this->sendNotification('success', 'Команда успешно добавлена.');
                return $this->redirect('/structure/teams');
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
                return $this->redirect('/structure/teams');
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

    public function actionChangeEmployeePosition(int $id)
    {
        /** @var User $lead */
        $lead = \Yii::$app->user->identity;

        $employee = User::findOne(['id' => $id, 'status' => AccountStatus::ACTIVE->value]);
        if (is_null($employee)) {
            $this->notice('error', 'Данный сотрудник в системе не найден.');
        }

        $position = \Yii::$app->request->post('position');
        if (empty($position)) {
            $this->notice('error', 'Необходимо назначить должность!');
            return $this->redirect(['staff/index']);
        }

        if ($this->checkChangePositionAvailability($lead, $employee)) {
            $employee->profile->updatePosition($position);
            $this->notice('success', 'Должность сотрудника была изменена');
            return $this->redirect(['staff/index']);
        } else {
            $this->notice('error', 'У вас нет возможности управлять должностью данного сотрудника');
        }

        return $this->redirect(['staff/index']);
    }


    private function checkChangePositionAvailability(User|Identity $lead, User|Identity $employee): bool
    {
        if (in_array(\Core\Enums\Role::HR->value, $lead->roles->getValue())) {
            return true;
        }

        if ($lead->isDepartmentLead() && $lead->getDepartment()->id == $employee->getDepartment()?->id) {
            return true;
        }

        if ($lead->isTeamLead() && $lead->getTeam()->id == $employee->getTeam()?->id) {
            return true;
        }

        return false;
    }

    private function notice(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'Взаимодействие в разделе руководителя',
            'message' => $message
        ]);
    }

    protected function sendNotification(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'Взаимодействие в разделе руководителя',
            'message' => $message
        ]);
    }
}