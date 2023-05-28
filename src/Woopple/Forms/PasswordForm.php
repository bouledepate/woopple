<?php

namespace Woopple\Forms;

use yii\base\Model;

class PasswordForm extends Model
{
    public $password;
    public $passwordRepeat;

    public function rules()
    {
        return [
            [['password', 'passwordRepeat'], 'required'],
            [['password', 'passwordRepeat'], 'string'],
            [['password', 'passwordRepeat'], 'trim'],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => "Введённые пароли не совпадают"],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'passwordRepeat' => 'Повторение пароля'
        ];
    }
}