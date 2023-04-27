<?php

namespace Woopple\Models\Structure;

use Woopple\Models\User\User;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property Team $team
 * @property User $user
 */
class TeamMember extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['team_id', 'user_id'], 'required'],
            ['team_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => Team::class],
            ['user_id', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::class]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'team_id' => 'Команда',
            'user_id' => 'Сотрудник'
        ];
    }

    public function getTeam(): ActiveQuery
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}