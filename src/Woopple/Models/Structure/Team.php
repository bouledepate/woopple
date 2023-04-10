<?php

namespace Woopple\Models\Structure;

use Woopple\Models\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * @property int $id
 * @property string $name
 * @property int $department_id
 * @property int $lead
 * @property Department $department
 * @property TeamMember[] $members
 * @property User $teamLead
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

    public function addMember(int $id): bool
    {
        $model = new TeamMember();

        $model->setAttributes([
            'team_id' => $this->id,
            'user_id' => $id
        ]);

        return $model->save();
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function removeMember(int $id): bool
    {
        $model = TeamMember::findOne([
            'team_id' => $this->id,
            'user_id' => $id
        ]);

        return $model->delete();
    }

    public function getTeamLead(): ActiveQuery
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