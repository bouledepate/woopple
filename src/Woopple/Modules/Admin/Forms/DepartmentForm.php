<?php

namespace Woopple\Modules\Admin\Forms;

use Woopple\Components\Enums\AccountStatus;
use Woopple\Models\User\User;
use yii\base\Model;

class DepartmentForm extends Model
{
    public ?int $id = null;
    public string $name = '';
    public int $lead = 0;
    public string $leadPosition = '';
    public array $users;

    public function init(): void
    {
        parent::init();

        $raw = User::findAll(['status' => AccountStatus::ACTIVE->value]);
        $this->users = array_combine(
            array_map(fn(User $user) => $user->id, $raw),
            array_map(fn(User $user) => $user->profile->fullName(), $raw)
        );
    }

    public function rules(): array
    {
        return [
            [['name', 'lead', 'leadPosition'], 'required'],
            [['name', 'leadPosition'], 'string'],
            [['name', 'leadPosition'], 'trim'],
            ['lead', 'exist', 'targetAttribute' => 'id', 'targetClass' => User::class],
            ['id', 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование отдела',
            'lead' => 'Сотрудник компании',
            'leadPosition' => 'Назначаемая должность'
        ];
    }
}