<?php

namespace Woopple\Models\Test;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * @property int $id
 * @property int $question_id
 * @property string $text
 * @property bool $is_correct
 * @property-read Question $question
 */
class QuestionAnswer extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['question_id', 'text'], 'required'],
            ['text', 'string'],
            ['text', 'trim'],
            [['is_correct'], 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'question_id' => 'Вопрос',
            'text' => 'Текст',
            'is_correct' => 'Правильный ответ'
        ];
    }

    public function getQuestion(): ActiveQuery
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }
}