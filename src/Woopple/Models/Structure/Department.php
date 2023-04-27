<?php

namespace Woopple\Models\Structure;

use Woopple\Models\Event\Event;
use Woopple\Models\Event\EventData;
use Woopple\Models\Event\Icon;
use Woopple\Modules\Admin\Forms\DepartmentForm;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use Woopple\Models\User\User;
use yii\db\StaleObjectException;

/**
 * @property int $id
 * @property string $name
 * @property int $lead
 * @property Team[] $teams
 * @property User $departmentLead
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

    public function getDepartmentLead(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'lead']);
    }

    public function getTeams(): ActiveQuery
    {
        return $this->hasMany(Team::class, ['department_id' => 'id']);
    }

    public static function new(DepartmentForm $form): ?self
    {
        $model = new self();

        $model->setAttributes([
            'name' => $form->name,
            'lead' => $form->lead
        ]);

        return $model->save(false) ? $model : null;
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public static function modify(DepartmentForm $form): ?self
    {
        $model = self::findOne(['id' => $form->id]);

        $model->setAttributes([
            'name' => $form->name,
            'lead' => $form->lead
        ]);

        $response = $model->update(false);

        if ($model->departmentLead->profile->position !== $form->leadPosition) {
            return $model;
        }

        return $response ? $model : null;
    }

    public function setLead(string $position): void
    {
        $this->departmentLead->profile->updatePosition($position);

        Event::create(new EventData(
            $this->lead,
            'Назначен руководителем отдела',
            "Назначается руководителем отдела: \"{$this->name}\".",
            new Icon("fas fa-user-tie", 'bg-success')
        ));
    }

    public function changeLead(int $previous, string $position): void
    {
        if ($this->lead != $previous) {
            Event::create(new EventData(
                $previous,
                'Снят с должности руководителя отдела',
                "Данный сотрудник более не является руководителем отдела \"{$this->name}\".",
                new Icon("fas fa-user-tie", 'bg-danger')
            ));

            User::findOne(['id' => $previous])->profile->updatePosition('Сотрудник');
            $this->departmentLead->profile->updatePosition($position);

            Event::create(new EventData(
                $this->lead,
                'Назначен руководителем отдела',
                "Назначается руководителем отдела: \"{$this->name}\".",
                new Icon("fas fa-user-tie", 'bg-success')
            ));
        } else {
            if ($this->departmentLead->profile->position !== $position) {
                $this->departmentLead->profile->updatePosition($position);

                Event::create(new EventData(
                    $this->lead,
                    'Смена должности',
                    "Данный сотрудник переведён на новую должность - {$position}.",
                    new Icon("fas fa-user-tie", 'bg-info')
                ));
            }
        }
    }

    public function removeLead(): void
    {
        $this->departmentLead->profile->updatePosition('Сотрудник');
        Event::create(new EventData(
            $this->lead,
            'Снят с должности руководителя отдела',
            "Данный сотрудник более не является руководителем отдела \"{$this->name}\".",
            new Icon("fas fa-user-tie", 'bg-danger')
        ));
    }
}