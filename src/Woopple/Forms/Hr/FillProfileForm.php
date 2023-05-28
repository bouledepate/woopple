<?php

namespace Woopple\Forms\Hr;

use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class FillProfileForm extends Model
{
    public $firstName = null;
    public $secondName = null;
    public $lastName = null;
    public $birthday = null;
    public $education = null;
    public $skills = null;
    public $position = null;
    public $department = null;
    public $team = null;

    public array $departments = [];

    public function init(): void
    {
        $departments = Department::find()->all();
        $this->departments = ArrayHelper::map($departments, 'id', 'name');

        parent::init();
    }

    public function rules(): array
    {
        return [
            [['firstName', 'secondName', 'lastName', 'birthday', 'position'], 'required'],
            [['firstName', 'secondName', 'lastName', 'education', 'skills', 'position'], 'string'],
            [['firstName', 'secondName', 'lastName', 'education', 'skills', 'position'], 'trim'],
            ['department', 'exist', 'targetClass' => Department::class, 'targetAttribute' => 'id'],
            ['team', 'exist', 'targetClass' => Team::class, 'targetAttribute' => 'id'],
            [['department', 'team'], 'safe']
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