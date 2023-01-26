<?php

namespace Woopple\Models\User;

use yii\db\ActiveRecord;

class UserProfile extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['first_name', 'second_name', 'last_name'], 'string'],
            [['first_name', 'second_name', 'last_name'], 'trim'],
            ['birthday', 'date']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'first_name' => 'Имя',
            'second_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'birthday' => 'Дата рождения'
        ];
    }
}