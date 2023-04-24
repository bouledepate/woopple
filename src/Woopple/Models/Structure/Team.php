<?php

namespace Woopple\Models\Structure;

use Woopple\Forms\Hr\ManageTeamForm;
use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
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

    public static function updateTeam(self $team, ManageTeamForm $form): ?self
    {
        $team->updateAttributes([
            'name' => $form->name,
            'department_id' => $form->department_id
        ]);

        if ($team->lead !== $form->lead_id && !empty($form->lead_id)) {
            if (!is_null($team->lead)) {
                $tm = TeamMember::findOne(['user_id' => $team->id]);
            } else {
                $tm = new TeamMember();
            }

            if (!is_null($tm->user_id)) {
                Event::create(new EventData($tm->user_id, 'Изменения орг. структуры',
                    "Сотрудник был снят с должности руководителя команды: \"{$team->name}\".",
                    new Icon('fas fa-user-tie', 'bg-danger')
                ));
            }

            $tm->setAttributes(['team_id' => $team->id, 'user_id' => $form->lead_id]);
            $team->updateAttributes(['lead' => $form->lead_id]);
            $tm->update();

            Event::create(new EventData($form->lead_id, 'Изменения орг. структуры',
                'Сотрудник был назначен руководителем команды: "' . $team->name . '".',
                new Icon('fas fa-user-tie', 'bg-success')
            ));
        }

        $newMembers = [];
        $kickedMembers = [];
        $currentMembers = array_filter($team->members, function (TeamMember $member) {
            return !$member->user->isTeamLead();
        });

        foreach ($currentMembers as $member) {
            $key = array_search((string)$member->user_id, $form->members);
            if ($key !== false) {
                unset($form->members[$key]);
            } else {
                $kickedMembers[] = $member->user_id;
            }
        }

        if (!empty($form->members)) {
            $newMembers = $form->members;
        }

        foreach ($kickedMembers as $member) {
            $tm = TeamMember::findOne(['team_id' => $team->id, 'user_id' => $member]);
            $tm->delete();

            Event::create(new EventData($member, 'Изменения орг. структуры',
                'Сотрудник был исключён из команды: "' . $team->name . '".',
                new Icon('fas fa-user-check', 'bg-danger')
            ));
        }

        foreach ($newMembers as $member) {
            $tm = TeamMember::findOne(['user_id' => $member]);
            if (is_null($tm)) {
                $tm = new TeamMember();
            }
            $tm->setAttributes(['team_id' => $team->id, 'user_id' => $member]);
            $tm->save();

            Event::create(new EventData($member, 'Изменения орг. структуры',
                'Сотрудник был переведён в следующую команду: "' . $team->name . '".',
                new Icon('fas fa-user-check', 'bg-success')
            ));
        }

        return $team;
    }

    public static function createWithMembers(ManageTeamForm $form): ?self
    {
        $leadUser = User::findOne(['id' => $form->lead_id]);
        $currentTeam = $leadUser->getTeam();
        $isTeamLead = $leadUser->isTeamLead();

        $obj = new self();
        $obj->setAttributes([
            'name' => $form->name,
            'department_id' => $form->department_id,
            'lead' => $form->lead_id
        ]);

        if ($obj->save()) {
            if ($isTeamLead) {
                Event::create(new EventData($obj->lead, 'Изменения орг. структуры',
                    "Сотрудник был снят с должности руководителя команды: \"{$currentTeam->name}\".",
                    new Icon(
                        'fas fa-user-tie',
                        'bg-danger'
                    )
                ));
                $currentTeam->setAttributes(['lead' => null]);
                $currentTeam->update(false);
            }

            if (!is_null($currentTeam)) {
                $ltm = TeamMember::findOne(['user_id' => $leadUser->id, 'team_id' => $currentTeam->id]);
                $ltm->setAttributes(['team_id' => $obj->id]);
            } else {
                $ltm = new TeamMember();
                $ltm->setAttributes(['team_id' => $obj->id, 'user_id' => $obj->lead]);
            }

            if ($ltm->save()) {
                Event::create(new EventData($obj->lead, 'Изменения орг. структуры',
                    'Сотрудник был назначен руководителем команды: "' . $obj->name . '".',
                    new Icon(
                        'fas fa-user-tie',
                        'bg-success'
                    )
                ));
            } else {
                $obj->delete();
                return null;
            };

            foreach ($form->members as $member) {
                $memberUser = User::findOne(['id' => $member]);
                $currentTeam = $memberUser->getTeam();
                if ($currentTeam) {
                    $tm = TeamMember::findOne(['user_id' => $memberUser->id, 'team_id' => $currentTeam->id]);
                } else {
                    $tm = new TeamMember();
                }
                $tm->setAttributes(['team_id' => $obj->id, 'user_id' => $member]);

                if ($tm->save()) {
                    Event::create(new EventData($member, 'Изменения орг. структуры',
                        'Сотрудник был переведён в следующую команду: "' . $obj->name . '".',
                        new Icon(
                            'fas fa-user-check',
                            'bg-info'
                        )
                    ));
                }
            }
        } else {
            return null;
        }

        return $obj;
    }

    public function addMember(int $id, bool $isLead = false): bool
    {
        $user = User::findOne(['id' => $id]);
        $userTeam = $user->getTeam();

        if (is_null($userTeam)) {
            $model = new TeamMember();
        } else {
            $model = $userTeam;
        }

        $model->setAttributes([
            'team_id' => $this->id,
            'user_id' => $id
        ]);

        $result = $model->save();

        if ($result && !$isLead) {
            Event::create(new EventData($user->id, 'Изменения орг. структуры',
                'Сотрудник был переведён в следующую команду: "' . $model->name . '".',
                new Icon(
                    'fas fa-user-check',
                    'bg-info'
                )
            ));
        }

        return $result;
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