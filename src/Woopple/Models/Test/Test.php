<?php

namespace Woopple\Models\Test;

use stdClass;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\AnswersForm;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\Structure\TeamMember;
use Woopple\Models\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UnprocessableEntityHttpException;

/**
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property \DateTimeInterface|int $created_at
 * @property string $state
 * @property \DateTimeInterface|int $expiration_date
 * @property bool $is_closed
 * @property string $availability
 * @property int|null $subject_id
 * @property string $questions_raw
 * @property-read User $author
 * @property-read User|Team|Department|null $subject
 * @property-read Question[] $questions
 */
class Test extends ActiveRecord
{
    public ?string $questions_raw = null;

    public function rules()
    {
        return [
            [['title', 'author_id'], 'required'],
            ['title', 'string'],
            ['title', 'trim'],
            ['is_closed', 'boolean'],
            [['author_id', 'subject_id'], 'integer'],
            ['state', 'default', 'value' => TestState::PROCESS->value],
            ['availability', 'default', 'value' => TestAvailability::TEAM_ONLY->value],
            ['availability', 'in', 'range' => TestAvailability::values()],
            ['state', 'in', 'range' => TestState::values()],
            ['subject_id', 'required', 'when' => function (self $model) {
                return TestAvailability::tryFrom($model->availability) !== TestAvailability::COMMON;
            }, 'whenClient' => 'function(attribute,value){return $(\'#test-availability\').val() != \'common\'}'],
            ['subject_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id', 'when' => function (self $model) {
                return TestAvailability::tryFrom($model->availability) == TestAvailability::USER_ONLY;
            }],
            ['subject_id', 'exist', 'targetClass' => Team::class, 'targetAttribute' => 'id', 'when' => function (self $model) {
                return TestAvailability::tryFrom($model->availability) == TestAvailability::TEAM_ONLY;
            }],
            ['subject_id', 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id', 'when' => function (self $model) {
                return TestAvailability::tryFrom($model->availability) == TestAvailability::DEP_ONLY;
            }],
            [['created_at',], 'date', 'format' => 'php:Y-m-d H:i'],
            [['questions_raw', 'expiration_date'], 'safe']
        ];
    }

    public function afterFind()
    {
        $this->updateTestState();
        parent::afterFind();
    }

    public function applyUserAnswers(array $data): void
    {
        foreach ($data['answers'] as $question => $answer) {
            $answer = $answer['answer'];
            $question = Question::findOne(['id' => $question]);
            $this->submitUserAnswer($question, $answer);
        }
    }

    public function currentProgress(): string
    {
        $respondents = [];
        $availability = TestAvailability::tryFrom($this->availability);
        $classname = match ($availability) {
            TestAvailability::COMMON, TestAvailability::USER_ONLY => User::class,
            TestAvailability::TEAM_ONLY => Team::class,
            TestAvailability::DEP_ONLY => Department::class,
        };
        $model = new $classname();
        if ($availability === TestAvailability::COMMON) {
            $data = $model::findAll(['status' => AccountStatus::ACTIVE->value]);
            $respondents = $data;
        } else {
            $data = $model::findOne(['id' => $this->subject_id]);
            if ($data instanceof User) {
                $respondents[] = $data;
            } elseif ($data instanceof Team) {
                foreach ($data->members as $member) {
                    $respondents[] = $member->user;
                }
            } elseif ($data instanceof Department) {
                foreach ($data->teams as $team) {
                    foreach ($team->members as $member) {
                        $respondents[] = $member->user;
                    }
                }
            }
        }

        // Вычисляется процент не прошедших
        $totalRespondents = count($respondents);
        /** @var User[] $notPassed */
        $passed = array_filter($respondents, function (User $user) {
            $obj = UserAnswer::findOne(['user_id' => $user->id, 'test_id' => $this->id]);
            return !is_null($obj);
        });
        $passedCount = count($passed);
        $percent = round(($passedCount / $totalRespondents) * 100);


        return '<div class="progress progress-sm">'
            . "<div class=\"progress-bar bg-green\" role=\"progressbar\" aria-valuenow=\"{$percent}\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: {$percent}%\">"
            . "</div></div><small>{$percent}% прошли тест</small>";
    }

    private function submitUserAnswer(Question $question, array|string $answers)
    {
        $object = new UserAnswer();
        $object->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'test_id' => $this->id,
            'question_id' => $question->id
        ]);

        if (QuestionType::tryFrom($question->type) == QuestionType::OPEN) {
            $object->setAttribute('text', $answers);
        } else {
            $object->setAttribute('answer_ids', is_array($answers) ? $answers : [$answers]);
        }

        $object->save();
    }

    public function apply(): ?self
    {
        if ($this->save()) {
            $questions = json_decode($this->questions_raw);
            /** @var stdClass $question */
            foreach ($questions as $question) {
                $result = $this->addQuestion($question);
            }
            return $this;
        }
        return null;
    }

    private function addQuestion(stdClass $data): ?Question
    {
        $obj = new Question();
        $obj->setAttributes([
            'title' => $data->title,
            'description' => $data->description,
            'type' => $data->type,
            'is_strict' => $data->is_strict,
            'is_multiple' => $data->is_multiple,
            'test_id' => $this->id
        ]);

        if ($obj->save()) {
            if (!empty($data->answers)) {
                /** @var stdClass $answer */
                foreach ($data->answers as $answer) {
                    $obj->addAnswer($answer);
                }
            }
        }

        return $obj;
    }

    public function beforeValidate(): bool
    {
        parent::beforeValidate();
        if (TestAvailability::tryFrom($this->availability) == TestAvailability::COMMON) {
            $this->setAttribute('is_closed', false);
            $this->setAttribute('subject_id', null);
        }
        return true;
    }

    public function getAuthor(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getSubject(): \yii\db\ActiveQuery|null
    {
        if (TestAvailability::tryFrom($this->availability) == TestAvailability::COMMON) {
            return null;
        }

        $model = match (TestAvailability::tryFrom($this->availability)) {
            TestAvailability::USER_ONLY => User::class,
            TestAvailability::TEAM_ONLY => Team::class,
            TestAvailability::DEP_ONLY => Department::class,
            default => throw new \Exception('Unexpected match value')
        };

        return $this->hasOne($model, ['id' => 'subject_id']);
    }

    public function getQuestions(): ActiveQuery
    {
        return $this->hasMany(Question::class, ['test_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование теста',
            'author_id' => 'Составитель',
            'created_at' => 'Дата создания',
            'expiration_date' => 'Дата истечения',
            'state' => 'Состояние теста',
            'is_closed' => 'Является закрытым',
            'availability' => 'Доступность для',
            'subject_id' => 'Субъект тестирования'
        ];
    }

    public function attributeHints()
    {
        return [
            'is_closed' => 'Если выставлен флаг, то доступ к результатам тестирования сможет получить только респондент и руководитель.'
        ];
    }

    public function isAvailableFor(User|IdentityInterface $user): bool
    {
        $subject = $this->subject;

        if (is_null($subject)) {
            return true;
        }

        if ($subject instanceof User) {
            return $subject->id === $user->id;
        }

        if ($subject instanceof Team) {
            $members = $subject->members;
            foreach ($members as $member) {
                if ($member->user->id === $user->id) {
                    return true;
                }
            }
        }

        if ($subject instanceof Department) {
            $teams = $subject->teams;
            foreach ($teams as $team) {
                $members = $team->members;
                foreach ($members as $member) {
                    if ($member->user->id === $user->id) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function isAlreadyPassedBy(User|IdentityInterface $user): bool
    {
        $obj = UserAnswer::findOne(['user_id' => $user->id, 'test_id' => $this->id]);
        return !is_null($obj);
    }

    public function getAnswersByUser(User $user): array
    {
        return UserAnswer::findAll(['user_id' => $user->id, 'test_id' => $this->id]);
    }

    /**
     * @return array<User>
     */
    public function respondents(): array
    {
        $availability = TestAvailability::tryFrom($this->availability);
        return match ($availability) {
            TestAvailability::COMMON => $this->receiveAll(),
            TestAvailability::USER_ONLY => $this->receiveUser(),
            TestAvailability::TEAM_ONLY => $this->receiveTeam(),
            TestAvailability::DEP_ONLY => $this->receiveDepartment(),
        };
    }

    /**
     * @return array<User>
     */
    protected function receiveUser(): array
    {
        return [$this->subject];
    }

    /**
     * @return array<User>
     */
    protected function receiveTeam(): array
    {
        $team = $this->subject;
        return array_map(function (TeamMember $member) {
            return $member->user;
        }, $team->members);
    }

    /**
     * @return array<User>
     */
    protected function receiveDepartment(): array
    {
        $respondents = [];
        $department = $this->subject;

        foreach ($department->teams as $team) {
            foreach ($team->members as $member) {
                $respondents[] = $member->user;
            }
        }

        return $respondents;
    }

    protected function receiveAll(): array
    {
        return User::findAll(['status' => AccountStatus::ACTIVE->value]);
    }

    public function submitResults(User $user, ?string $feedback = null, ?array $additional = []): ?Result
    {
        $userAnswers = $this->getAnswersByUser($user);
        $mark = $this->validateAnswers($userAnswers, $additional);

        $result = new Result();
        $result->setAttributes([
            'user_id' => $user->id,
            'reviewer_id' => \Yii::$app->user->id,
            'test_id' => $this->id,
            'mark' => $mark,
            'feedback' => $feedback
        ]);

        return $result->save() ? $result : null;
    }

    public function getResultByUser(User|IdentityInterface $user): ?Result
    {
        return Result::findOne(['test_id' => $this->id, 'user_id' => $user->id]);
    }

    protected function validateAnswers(array $answers, ?array $add_answers = []): int
    {
        $mark = 0;

        /** @var UserAnswer $answer */
        foreach ($answers as $answer) {
            $question = $answer->question;

            if (QuestionType::tryFrom($question->type) == QuestionType::OPEN && $question->is_strict) {
                $isCorrectValue = $add_answers[$answer->id] ?? false;
                if ($isCorrectValue === 'on') {
                    $answer->markAsCorrect();
                    $mark++;
                }
            }

            if (QuestionType::tryFrom($question->type) == QuestionType::CLOSED && $question->is_strict) {
                $questionCorrectAnswers = $question->getCorrectAnswers();

                if ($question->is_multiple) {
                    $userAnswers = $answer->answer_ids->getValue();
                    foreach ($userAnswers as $key => $value) {
                        array_walk($questionCorrectAnswers, function (QuestionAnswer $answer) use (&$userAnswers, $key, $value) {
                            if ($answer->id === $value && $answer->is_correct) {
                                unset($userAnswers[$key]);
                            }
                        });
                    }
                    if (empty($userAnswers)) {
                        $answer->markAsCorrect();
                        $mark++;
                    }
                } else {
                    $userAnswer = $answer->answer_ids->getValue()[0];
                    if ($questionCorrectAnswers[0]->id == $userAnswer) {
                        $answer->markAsCorrect();
                        $mark++;
                    }
                }
            }
        }

        return $mark;
    }

    public function updateTestState(): void
    {
        $isPassed = true;
        $respondents = $this->respondents();

        foreach ($respondents as $respondent) {
            if (!$this->isAlreadyPassedBy($respondent)) {
                $isPassed = false;
            }
        }

        if ($isPassed) {
            $this->updateAttributes(['state' => TestState::PASSED->value]);
            return;
        }

        if (!is_null($this->expiration_date)) {
            $currentDate = new \DateTimeImmutable();
            $expirationDate = new \DateTimeImmutable($this->expiration_date);

            if ($currentDate->getTimestamp() >= $expirationDate->getTimestamp()) {
                $this->updateAttributes(['state' => TestState::EXPIRED->value]);
            }
        }
    }
}