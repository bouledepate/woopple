<?php

namespace Woopple\Controllers;

use Woopple\Forms\AnswersForm;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\Test\Test;
use Woopple\Models\Test\TestAvailability;
use Woopple\Models\Test\UserAnswer;
use Woopple\Models\User\User;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\Response;

class TestController extends Controller
{
    public function actionControl()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Test::find()->where(['author_id' => \Yii::$app->user->id])
        ]);
        return $this->render('control', compact('dataProvider'));
    }

    public function actionStartTest(int $id)
    {
        $test = Test::findOne($id);
        $questions = $test->questions;

        if (\Yii::$app->request->isPost) {
            $test->applyUserAnswers(\Yii::$app->request->post('AnswerForm'));
            $this->notice('success', 'Ваши ответы были записаны. Спасибо за участие в тестировании');
            return $this->redirect(['test/user-tests']);
        }

        return $this->render('test', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    public function actionUserResults(int $id)
    {

    }

    public function actionUserResultsByLogin(int $id, string $login)
    {

    }

//'/tests' => 'test/user-tests',
//'/tests/start/<id>' => 'test/start-test',
//'/tests/result/<id>' => 'test/user-results',
//'/tests/result/<id>/by/<login>' => 'test/user-results-by-login'

    public function actionUserTests()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $team = $user->getTeam();
        $department = $user->getDepartment();
        $notFinishedDataProvider = new ActiveDataProvider([
            'query' => Test::find()
                ->where(['availability' => TestAvailability::COMMON->value])
                ->orWhere(['availability' => TestAvailability::USER_ONLY->value, 'subject_id' => intval($this->id)])
                ->orWhere(['availability' => TestAvailability::TEAM_ONLY->value, 'subject_id' => $team?->id ? intval($team->id) : null])
                ->orWhere(['availability' => TestAvailability::DEP_ONLY->value, 'subject_id' => $department?->id ? intval($department->id) : null])
        ]);

        list($notFinished, $finished) = $this->filterTests($notFinishedDataProvider->getModels());
        $notFinishedDataProvider->setModels($notFinished);

        $finishedDataProvider = new ArrayDataProvider([
            'models' => $finished
        ]);

        return $this->render('user-tests', compact('notFinishedDataProvider', 'finishedDataProvider'));
    }

    private function filterTests(array $data): array
    {
        $notFinishedYet = [];
        $finished = [];

        /** @var Test $test */
        foreach ($data as $test) {
            $obj = UserAnswer::findOne(['user_id' => \Yii::$app->user->id, 'test_id' => $test->id]);
            if (is_null($obj)) {
                $notFinishedYet[] = $test;
            } else {
                $finished[] = $test;
            }
        }

        return [$notFinishedYet, $finished];
    }

    public function actionCreateTest(): Response|string
    {
        $model = new Test();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $response = $model->apply();
            if (!is_null($response)) {
                $this->notice('success', 'Тест был успешно создан. Пользователи получат соответствующие уведомления.');
                return $this->redirect(['test/control']);
            } else {
                $this->notice('error', 'При создании теста возникли неполадки. Убедитесь в корректности данных.');
            }
        }

        return $this->render('create', compact('model'));
    }

    public function actionGetSubjects()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $type = $_POST['depdrop_parents'][0] ?? null;

        $data = [];
        $type = TestAvailability::tryFrom($type);
        list($user, $team, $department) = $this->getCurrentLeadInfo();

        if (is_null($type) || $type == TestAvailability::COMMON->value) {
            return ['output' => '', 'selected' => ''];
        }

        /** @var User|Team|Department $class */
        $class = match ($type) {
            TestAvailability::USER_ONLY => User::class,
            TestAvailability::TEAM_ONLY => Team::class,
            TestAvailability::DEP_ONLY => Department::class
        };

        switch ($class) {
            case Department::class:
                /**
                 * @var User $user
                 * @var Department $department
                 */
                if (!is_null($department) && $user->isDepartmentLead()) {
                    $data[] = ['id' => $department->id, 'name' => $department->name];
                }
                break;
            case Team::class:
                /**
                 * @var User $user
                 * @var Team $team
                 * @var Department $department
                 */
                if ($user->isTeamLead()) {
                    $data[] = ['id' => $team->id, 'name' => $team->name];
                } else {
                    foreach ($department->teams as $team) {
                        $data[] = ['id' => $team->id, 'name' => $team->name];
                    }
                }
                break;
            case User::class:
                /**
                 * @var User $user
                 * @var Team $team
                 * @var Department $department
                 */
                if ($user->isTeamLead()) {
                    foreach ($team->members as $member) {
                        $data[] = ['id' => $member->user->id, 'name' => $member->user->profile->fullName()];
                    }
                } else {
                    foreach ($department->teams as $team) {
                        foreach ($team->members as $member) {
                            $data[] = ['id' => $member->user->id, 'name' => $member->user->profile->fullName()];
                        }
                    }
                }
        }

        return ['output' => $data, 'selected' => ''];
    }

    private function getCurrentLeadInfo(): array
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $department = $user->getDepartment();
        $team = $user->getTeam();

        return [$user, $team, $department];
    }

    private function notice(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'Раздел тестирования',
            'message' => $message
        ]);
    }
}