<?php

namespace Woopple\Models\User;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @User
 * @property string $login
 * @property string $email
 * @property-read UserProfile $profile
 * @property-read UserSecurity $security
 */
class User extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['login', 'email'], 'required'],
            [['login', 'email'], 'trim'],
            ['login', 'string'],
            ['email', 'email'],
            [['created', 'updated'], 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Логин пользователя',
            'email' => 'Электронная почта',
            'created' => 'Дата создания',
            'updated' => 'Дата обновления'
        ];
    }

    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    public function getSecurity(): ActiveQuery
    {
        return $this->hasOne(UserSecurity::class, ['user_id' => 'id']);
    }
}