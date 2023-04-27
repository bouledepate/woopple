<?php

namespace Woopple\Forms\Restore;

use Woopple\Models\User\User;
use yii\base\Model;

class RequestForm extends Model implements RestoreStep
{
    public string $login = '';
    public string $reason = '';

    public function rules(): array
    {
        return [
            [['login', 'reason'], 'required'],
            [['login', 'reason'], 'string'],
            [['login', 'reason'], 'trim'],
            ['login', 'exist', 'targetClass' => User::class, 'message' => 'Заданного пользователя не существует']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Ваш логин',
            'reason' => 'Причина сброса пароля'
        ];
    }
}