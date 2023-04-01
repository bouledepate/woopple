<?php

namespace Woopple\Models\Structure;

use Woopple\Models\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property int $department_id
 * @property Department $department
 * @property int|User $lead
 * @property TeamMember[] $members
 */
class Team extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['name', 'department_id', 'lead'], 'required'],
            ['name', 'string'],
            ['name', 'trim'],
            ['department_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Department::class],
            ['lead', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::class],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование команды',
            'department_id' => 'Отдел',
            'lead' => 'Глава команды'
        ];
    }

    public function getLead(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'lead']);
    }

    public function getDepartment(): ActiveQuery
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    public function getMembers(): ActiveQuery
    {
        return $this->hasMany(TeamMember::class, ['team_id' => 'id']);
    }
}