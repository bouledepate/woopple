<?php

namespace Woopple\Models\Test;

use Woopple\Models\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $test_id
 * @property int $question_id
 * @property string $text
 * @property int $answer_id
 * @property array $answer_ids
 * @property boolean $is_correct
 * @property-read User $user
 * @property-read Test $test
 * @property-read Question $question
 * @property-read QuestionAnswer|null $answer
 */
class UserAnswer extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['user_id', 'test_id', 'question_id'], 'required'],
            ['text', 'required', 'when' => function (self $model) {
                return QuestionType::tryFrom($model->question->type) == QuestionType::OPEN;
            }],
            ['answer_id', 'integer'],
            ['is_correct', 'default', 'value' => false]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => 'Респондент',
            'test_id' => 'Тест',
            'question_id' => 'Вопрос',
            'text' => 'Ответ респондента',
            'answer_id' => 'Ответ респондента',
            'is_correct' => 'Правильность ответа'
        ];
    }

    public function checkCorrectness(): void
    {

    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getTest(): ActiveQuery
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    public function getQuestion(): ActiveQuery
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }

    public function getAnswer(): ActiveQuery
    {
        return $this->hasOne(QuestionAnswer::class, ['id' => 'answer_id']);
    }
}