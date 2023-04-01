<?php

namespace Woopple\Models\User;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $second_name
 * @property string $last_name
 * @property string $date
 * @property string $education
 * @property string $skills
 * @property string $notes
 * @property string $avatar
 * @property string $position
 */
class UserProfile extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['first_name', 'second_name', 'last_name', 'education', 'skills', 'notes', 'position'], 'string'],
            [['first_name', 'second_name', 'last_name', 'education', 'skills', 'notes', 'position'], 'trim'],
            ['birthday', 'date'],
            ['user_id', 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'first_name' => 'Имя',
            'second_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'birthday' => 'Дата рождения',
            'education' => 'Образование',
            'skills' => 'Навыки',
            'notes' => 'Заметки',
            'position' => 'Должность'
        ];
    }

    public function shortlyName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function fullName(): string
    {
        return "{$this->last_name} {$this->first_name} {$this->second_name}";
    }
}