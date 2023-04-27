<?php

namespace Woopple\Models\Test;

use stdClass;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\AnswersForm;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use Woopple\Models\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
            ['is_closed', 'default', 'value' => true],
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
            [['created_at', 'expiration_date'], 'date', 'format' => 'php:Y-m-d H:i'],
            ['questions_raw', 'safe']
        ];
    }

    public function applyUserAnswers(array $data): void
    {
        foreach ($data['answers'] as $question => $answer) {
            $answer = $answer['answer'];
            $question = Question::findOne(['id' => $question]);

            if (is_array($answer)) {
                foreach ($answer as $id) {
                    $this->submitUserAnswer($question, (int)$id);
                }
            } else {
                $this->submitUserAnswer($question, $answer);
            }
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
        $percent = round( ($passedCount / $totalRespondents) * 100);


        return '<div class="progress progress-sm">'
            . "<div class=\"progress-bar bg-green\" role=\"progressbar\" aria-valuenow=\"{$percent}\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: {$percent}%\">"
            . "</div></div><small>{$percent}% прошли тест</small>";
    }

    private function submitUserAnswer(Question $question, int|string $answer)
    {
        $object = new UserAnswer();
        $object->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'test_id' => $this->id,
            'question_id' => $question->id
        ]);

        if (QuestionType::tryFrom($question->type) == QuestionType::OPEN) {
            $object->setAttribute('text', $answer);
        } else {
            $object->setAttribute('answer_id', $answer);
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
}