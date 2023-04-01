<?php

namespace Woopple\Models\Structure;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use Woopple\Models\User\User;

/**
 * @property int $id
 * @property string $name
 * @property int|User $lead
 * @property Team[] $teams
 */
class Department extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['name', 'lead'], 'required'],
            ['name', 'string'],
            ['name', 'trim'],
            ['user', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::class]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование отдела',
            'lead' => 'Глава отдела'
        ];
    }

    public function getLead(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'lead']);
    }

    public function getTeams(): ActiveQuery
    {
        return $this->hasMany(Team::class, ['department_id' => 'id']);
    }
}