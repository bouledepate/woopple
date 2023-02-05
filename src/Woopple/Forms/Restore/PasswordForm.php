<?php

namespace Woopple\Forms\Restore;

use yii\base\Model;

class PasswordForm extends Model implements RestoreStep
{
    public string $password = '';
    public string $repeatPassword = '';

    public function rules(): array
    {
        return [
            [['password', 'repeatPassword'], 'required'],
            [['password', 'repeatPassword'], 'string'],
            [['password', 'repeatPassword'], 'trim'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => "Введённые пароли не совпадают"],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'password' => 'Новый пароль',
            'repeatPassword' => 'Повторите пароль'
        ];
    }
}