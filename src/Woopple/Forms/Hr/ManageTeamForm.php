<?php

namespace Woopple\Forms\Hr;

use Woopple\Components\Enums\AccountStatus;
use Woopple\Models\Structure\Department;
use Woopple\Models\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $name
 * @property int $lead_id
 * @property User $lead
 * @property int $department_id
 * @property Department $department
 * @property array $members
 */

class ManageTeamForm extends Model
{
    public $id;
    public $name;
    public $lead_id;
    public $department_id;
    public $members;

    public array $departments = [];
    public array $availableListOfLead = [];
    public array $availableListOfMembers = [];

    public function init()
    {
        parent::init();
        $this->departments = ArrayHelper::map(Department::find()->all(), 'id', 'name');
        $users =  User::find()->where([
            'status' => AccountStatus::ACTIVE->value,
        ])->all();

        $leads = array_filter($users, function (User $user) {
            return !$user->isDepartmentLead();
        });

        /** @var User $lead */
        foreach ($leads as $lead) {
            $this->availableListOfLead[$lead->id] = $lead->profile->fullName();
        }

        $members = array_filter($users, function (User $user) {
            return !$user->isTeamLead() && !$user->isDepartmentLead();
        });

        /** @var User $member */
        foreach ($members as $member) {
            $this->availableListOfMembers[$member->id] = $member->profile->fullName();
        }
    }

    public function rules(): array
    {
        return [
            [['name', 'lead_id', 'department_id'], 'required'],
            ['name', 'string'],
            ['name', 'trim'],
            ['members', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            ['lead_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['department_id', 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id'],
            ['id', 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название команды',
            'lead_id' => 'Руководитель',
            'department_id' => 'Отдел',
            'members' => 'Участники'
        ];
    }

    public function data(): array
    {
        return $this->getAttributes();
    }
}