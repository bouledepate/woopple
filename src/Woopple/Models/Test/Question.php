<?php

namespace Woopple\Models\Test;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $test_id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property boolean $is_strict
 * @property boolean $is_multiple
 * @property-read Test $test
 * @property-read array $answers
 * @property-read QuestionAnswer $correctAnswer
 */
class Question extends ActiveRecord
{
    public function rules()
    {
        return [
            [['test_id', 'title', 'type'], 'required'],
            ['test_id', 'integer'],
            [['title', 'description'], 'string'],
            [['title', 'description'], 'trim'],
            [['is_strict', 'is_multiple'], 'boolean'],
            ['type', 'in', 'range' => QuestionType::values()],
            ['test_id', 'safe'],
            ['is_strict', 'default', 'value' => true],
            ['is_multiple', 'default', 'value' => false]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_id' => 'Тест',
            'title' => 'Текст вопроса',
            'description' => 'Дополнительная информация',
            'is_strict' => 'Должен иметь правильный ответ',
            'type' => 'Тип вопроса',
        ];
    }

    public function addAnswer(\stdClass $data): ?QuestionAnswer
    {
        $obj = new QuestionAnswer();
        $obj->setAttributes([
            'question_id' => $this->id,
            'text' => $data->text,
            'is_correct' => $data->is_correct
        ]);

        return $obj->save() ? $obj : null;
    }

    public function getTest(): ActiveQuery
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    public function getAnswers(): ActiveQuery
    {
        return $this->hasMany(QuestionAnswer::class, ['question_id' => 'id']);
    }

    public function getCorrectAnswer(): ActiveQuery
    {
        return $this->hasOne(QuestionAnswer::class, ['question_id' => 'id'])
            ->where(['is_correct' => true]);
    }
}