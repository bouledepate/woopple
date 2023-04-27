<?php

namespace Woopple\Forms;

use yii\base\Model;

class AnswersForm extends Model
{
    public $answers;

    public function rules()
    {
        return [
            ['answers', 'required'],
        ];
    }

    public function __construct(array $config = [])
    {
        $this->answers = [];
        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [
            'answers' => 'Ответ'
        ];
    }
}