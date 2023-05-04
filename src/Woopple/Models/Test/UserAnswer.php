<?php

namespace Woopple\Models\Test;

use Woopple\Models\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\ArrayExpression;

/**
 * @property int $id
 * @property int $user_id
 * @property int $test_id
 * @property int $question_id
 * @property string $text
 * @property int $answer_id
 * @property array|ArrayExpression $answer_ids
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
            ['is_correct', 'default', 'value' => false],
            ['answer_ids', 'safe'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'user_id' => 'Респондент',
            'test_id' => 'Тест',
            'question_id' => 'Вопрос',
            'text' => 'Ответ респондента',
            'answer_ids' => 'Ответ респондента',
            'is_correct' => 'Правильность ответа'
        ];
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

    public function getAnswers(): array
    {
        return array_map(function (int $id) {
            return QuestionAnswer::findOne(['id' => $id]);
        }, $this->answer_ids);
    }

    public function markAsCorrect(): void
    {
        $this->is_correct = true;
        $this->update(false);
    }
}