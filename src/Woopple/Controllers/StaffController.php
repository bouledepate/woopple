<?php

namespace Woopple\Controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use Woopple\Models\User\User;
use Woopple\Components\Auth\Identity;
use Woopple\Components\Enums\AccountStatus;

class StaffController extends Controller
{
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['status' => AccountStatus::ACTIVE->value])
        ]);

        return $this->render('index', compact('dataProvider'));
    }

    public function actionChangePosition(int $id): \yii\web\Response
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
            'title' => 'Сотрудники компании',
            'message' => $message
        ]);
    }
}