<?php

namespace Woopple\Forms\Hr;

use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use yii\base\Model;

class FillProfileForm extends Model
{
    public ?string $firstName = null;
    public ?string $secondName = null;
    public ?string $lastName = null;
    public ?string $birthday = null;
    public ?string $education = null;
    public ?string $skills = null;
    public ?string $position = null;
    public ?int $department = null;
    public ?int $team = null;

    public array $departments;

    public function init(): void
    {
        $this->departments = Department::find()->all();
        parent::init();
    }

    public function rules(): array
    {
        return [
            [['firstName', 'secondName', 'lastName', 'birthday', 'position', 'department', 'team'], 'required'],
            [['firstName', 'secondName', 'lastName', 'education', 'skills', 'position'], 'string'],
            [['firstName', 'secondName', 'lastName', 'education', 'skills', 'position'], 'trim'],
            ['department', 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id'],
            ['team', 'exist', 'targetClass' => Team::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'firstName' => 'Имя',
            'lastName' => 'Фамилия',
            'secondName' => 'Отчество',
            'birthday' => 'Дата рождения',
            'education' => 'Образование',
            'skills' => 'Навыки',
            'position' => 'Должность',
            'department' => 'Отдел',
            'team' => 'Команда/Подотдел'
        ];
    }
}