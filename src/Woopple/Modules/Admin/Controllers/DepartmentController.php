<?php

namespace Woopple\Modules\Admin\Controllers;

use Core\Enums\Permission;
use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
use Woopple\Models\Structure\Department;
use Woopple\Modules\Admin\Forms\DepartmentForm;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class DepartmentController extends Controller
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
                        'roles' => [Permission::ACCESS_DEPARTMENT_CONTROL->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add'],
                        'roles' => [Permission::CREATE_DEPARTMENT->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['modify'],
                        'roles' => [Permission::MODIFY_DEPARTMENT->value]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['remove'],
                        'roles' => [Permission::REMOVE_DEPARTMENT->value]
                    ]
                ],
            ]
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find()
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionAdd(): Response|string
    {
        $model = new DepartmentForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $department = Department::new($model);
            if (!is_null($department)) {
                $department->setLead($model->leadPosition);
                $this->sendNotification('success', "Вы успешно зарегистрировали новый отдел");
                return $this->redirect(['department/index']);
            }
        }

        return $this->render('modify', ['model' => $model, 'department' => null]);
    }

    public function actionModify(int $id): Response|string
    {
        $model = new DepartmentForm();
        $department = Department::findOne(['id' => $id]);

        if (is_null($department)) {
            $this->sendNotification('error', 'Попытка изменения несуществующего отдела. Убедитесь в корректности передаваемых данных.');
            return $this->redirect(['department/index']);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $updated = Department::modify($model);
            if (!is_null($updated)) {
                $updated->changeLead($department->lead, $model->leadPosition);
                $this->sendNotification('success', "Вы успешно обновили данные отдела");
                return $this->redirect(['department/index']);
            }
        }

        $model->setAttributes([
            'id' => $department->id,
            'name' => $department->name,
            'lead' => $department->lead,
            'leadPosition' => $department->departmentLead->profile->position
        ]);

        return $this->render('modify', [
            'model' => $model,
            'department' => $department
        ]);
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionRemove(int $id): Response
    {
        $model = Department::findOne(['id' => $id]);

        if (is_null($model)) {
            $this->sendNotification('error', 'Отдел не найден.');
            return $this->redirect(['department/index']);
        }

        $model->removeLead();
        if ($model->delete()) {
            $this->sendNotification('success', 'Отдел был успешно удалён');
        } else {
            $this->sendNotification('error', 'Во время удаления произошла ошибка. Убедитесь в корректности выполняемых действий');
        }

        return $this->redirect(['department/index']);
    }

    protected function sendNotification(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'Управление отделами',
            'message' => $message
        ]);
    }
}