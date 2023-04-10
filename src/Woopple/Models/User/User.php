<?php

namespace Woopple\Models\User;

use Core\Enums\Role;
use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\Hr\FillProfileForm;
use Woopple\Models\Structure\Department;
use Woopple\Models\Structure\Team;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\ArrayExpression;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\db\StaleObjectException;

/**
 * @User
 * @property int $id
 * @property string $login
 * @property string $email
 * @property int $status
 * @property ArrayExpression $roles
 * @property-read UserProfile $profile
 * @property-read UserSecurity $security
 */
class User extends ActiveRecord
{
    public array $rawData = [];

    public function rules(): array
    {
        return [
            [['login', 'email'], 'required'],
            [['login', 'email'], 'trim'],
            ['login', 'string'],
            ['email', 'email'],
            ['status', 'in', 'range' => AccountStatus::values()],
            ['status', 'default', 'value' => AccountStatus::CREATED->value],
            ['roles', 'each', 'rule' => ['in', 'range' => Role::values()]],
            [['created', 'updated', 'rawData', 'last_seen'], 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Логин пользователя',
            'email' => 'Электронная почта',
            'created' => 'Дата создания',
            'updated' => 'Дата обновления',
            'last_seen' => 'Последнее посещение',
            'status' => 'Статус аккаунта'
        ];
    }

    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    public function getSecurity(): ActiveQuery
    {
        return $this->hasOne(UserSecurity::class, ['user_id' => 'id']);
    }

    public static function findOneByLogin(string $login): ?self
    {
        return self::findOne(['login' => $login]) ?? null;
    }

    public static function findOneByEmail(string $email): ?self
    {
        return self::findOne(['email' => $email]) ?? null;
    }

    public static function newObject(UserManagementInterface $management): self
    {
        $raw = $management->userData();

        $object = new self();
        $object->setAttributes([
            'login' => $raw['login'],
            'email' => $raw['email'],
            'roles' => $raw['roles'],
            'rawData' => $raw
        ]);

        $response = $object->save();

        return $object;
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function fillProfile(FillProfileForm $form): bool
    {
        $object = new UserProfile();
        $object->setAttributes([
            'user_id' => $this->id,
            'first_name' => $form->firstName,
            'second_name' => $form->secondName,
            'last_name' => $form->lastName,
            'education' => $form->education,
            'skills' => $form->skills,
            'position' => $form->position
        ]);

        if ($object->save(false)) {
            $team = Team::findOne(['id' => $form->team]);
            if ($team->addMember($object->id)) {
                return true;
            } else {
                $object->delete();
            }
        }

        return false;
    }

    public function fillSecurityProperties(): bool
    {
        $object = new UserSecurity();
        $object->setAttributes([
            'user_id' => $this->id,
            'password_hash' => $this->rawData['password'],
        ]);
        return $object->save(false);
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function stats(): array
    {
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand("SELECT status, count(*) as total, count(CASE WHEN status = 2 THEN status END) 
            AS blocked, count(CASE WHEN created > now() - INTERVAL '15 DAY' THEN created end) AS new FROM wooppledb.public.user group by status");
        $response = $command->queryAll();

        $result = ['total' => 0, 'blocked' => 0, 'new' => 0];
        foreach ($response as $status) {
            $result['new'] += $status['new'];
            $result['total'] += $status['total'];
            $result['blocked'] += $status['blocked'];
        }

        return $result;
    }

    /** @throws \Throwable */
    public function changeStatus(AccountStatus $status): void
    {
        $this->setAttribute('status', $status->value);
        $this->update(false);
    }

    public function isDepartmentLead(): bool
    {
        $model = Department::find()->where(['lead' => $this->id])->one();
        return !is_null($model);
    }

    public function isTeamLead(): bool
    {
        $model = Team::find()->where(['lead' => $this->id])->one();
        return !is_null($model);
    }
}