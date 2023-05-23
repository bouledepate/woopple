<?php

namespace Woopple\Controllers;

use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\AnswersForm;
use Woopple\Models\Event\Button;
use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\Test\Result;
use Woopple\Models\Test\Test;
use Woopple\Models\Test\TestAvailability;
use Woopple\Models\Test\TestState;
use Woopple\Models\Test\UserAnswer;
use Woopple\Models\User\User;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
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
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();

        if (is_null($test)) {
            $this->notice('error', 'Выбранного теста не существует.');
            return $this->redirect(['test/user-tests']);
        }

        if ($test->state !== TestState::PROCESS->value) {
            return $this->validateTestState($test);
        }

        if (!$test->isAvailableFor($user)) {
            $this->notice('error', 'Данный тест для вас недоступен.');
            return $this->redirect(['test/user-tests']);
        }

        if ($test->isAlreadyPassedBy($user)) {
            $this->notice('error', 'Вы уже проходили это тестирование.');
            return $this->redirect(['test/user-tests']);
        }


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

    public function actionRespondents(int $id)
    {
        $test = Test::findOne($id);
        if (is_null($test)) {
            $this->notice('error', 'Невозможно определить респондентов. Выбранного теста не существует.');
            return $this->redirect(['test/user-tests']);
        }
        $respondents = $test->respondents();

        asort($respondents);

        return $this->render('respondents', compact('test', 'respondents'));
    }

    public function actionReview(int $test, int $user)
    {
        $test = Test::findOne($test);
        if (is_null($test)) {
            $this->notice('error', 'Невозможно проверить ответы по тесту, которого не существует.');
            return $this->redirect(['test/control']);
        }

        $user = User::findOne(['id' => $user]);
        if (is_null($user)) {
            $this->notice('error', 'Невозможно провести ревью. Пользователя не существует.');
            return $this->redirect(['test/respondents', 'id' => $test->id]);
        }

        if (!$test->isAlreadyPassedBy($user)) {
            $this->notice('error', 'Невозможно провести ревью. Пользователь ещё не проходил выбранный тест.');
            return $this->redirect(['test/respondents', 'id' => $test->id]);
        }

        $answers = $test->getAnswersByUser($user);

        if (\Yii::$app->request->isPost) {
            $result = $test->submitResults(
                $user,
                \Yii::$app->request->post('feedback'),
                \Yii::$app->request->post('correct')
            );

            if (!is_null($result)) {
                $this->notice('success', 'Ответы респондента были успешно провалидированы и подтверждены.');

                if (\Yii::$app->request->post('notification') == 'on') {
                    Event::create(new EventData(
                        user: $user->id,
                        title: 'Результаты прошедшего тестирования',
                        message: 'Сотрудник успешно прошёл тестирование.',
                        icon: new Icon('fas fa-splotch', 'bg-warning'),
                        buttons: [
                            new Button(
                                title: 'Результаты тестирования',
                                link: Url::to(['test/user-results', 'test' => $test->id, 'login' => $user->login]),
                                style: 'btn btn-success btn-sm'
                            )
                        ]
                    ));
                }

                return $this->redirect(['test/user-results', 'test' => $test->id, 'login' => $user->login]);
            } else {
                $this->notice('warning', 'При записи результатов возникли неполадки.');
                return $this->redirect(['test/respondents', 'id' => $test->id]);
            }
        }

        return $this->render('review', compact('test', 'user', 'answers'));
    }

    public function actionUserResults(int $test, string $login)
    {
        $test = Test::findOne(['id' => $test]);
        if (is_null($test)) {
            $this->notice('error', 'Невозможно получить результаты по тестированию. Выбранного теста не существует.');
            return $this->redirect(['test/user-tests']);
        }

        $user = User::findOne(['login' => $login, 'status' => AccountStatus::ACTIVE->value]);
        if (is_null($user)) {
            $this->notice('error', 'Невозможно получить результаты по тестированию. Выбранного пользователя не существует.');
            return $this->redirect(['test/user-tests']);
        }

        $result = Result::findOne(['test_id' => $test->id, 'user_id' => $user->id]);
        if (is_null($result)) {
            $this->notice('error', 'Невозможно получить результаты по тестированию. Они не сформированы.');
            return $this->redirect(['test/user-tests']);
        }

        if ($test->is_closed && ($result->reviewer_id != \Yii::$app->user->id && $result->user_id != \Yii::$app->user->id)) {
            $this->notice('error', 'Невозможно получить результаты по тестированию. Доступ к ним ограничен.');
            return $this->redirect(['test/user-tests']);
        }

        $answers = $test->getAnswersByUser($user);

        return $this->render('user-results', compact('test', 'user', 'result', 'answers'));
    }

    public function actionUserTests()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $team = $user->getTeam();
        $department = $user->getDepartment();
        $notFinishedDataProvider = new ActiveDataProvider([
            'query' => Test::find()
                ->where(['availability' => TestAvailability::COMMON->value])
                ->orWhere(['availability' => TestAvailability::USER_ONLY->value, 'subject_id' => intval($user->id)])
                ->orWhere(['availability' => TestAvailability::TEAM_ONLY->value, 'subject_id' => $team?->id ? intval($team->id) : null])
                ->orWhere(['availability' => TestAvailability::DEP_ONLY->value, 'subject_id' => $department?->id ? intval($department->id) : null])
                ->andWhere(['in', 'state', [TestState::PROCESS->value, TestState::PASSED->value]])
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

    private function validateTestState(Test $test)
    {
        if (TestState::tryFrom($test->state) == TestState::CANCELED) {
            $this->notice('error', 'Данный тест недоступен. Он был отменён.');
            return $this->redirect(['test/user-tests']);
        }

        if (TestState::tryFrom($test->state) == TestState::EXPIRED) {
            $this->notice('error', 'Данный тест недоступен. Он был просрочён. Крайний срок прохождения был: ' . $test->expiration_date);
            return $this->redirect(['test/user-tests']);
        }

        if (TestState::tryFrom($test->state) == TestState::PASSED) {
            $this->notice('error', 'Данный тест недоступен. Все респонденты уже прошли его.');
            return $this->redirect(['test/user-tests']);
        }
    }
}