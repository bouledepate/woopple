<?php

namespace Woopple\Models;

use Woopple\Components\Enums\RestorePasswordStatus;
use Woopple\Models\User\User;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $moderated_by
 * @property string $reason
 * @property integer $status
 * @property integer $request_date
 * @property string $key
 * @property User $user
 * @property User $moderator
 */
final class Restore extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'security_restore';
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getModerator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'moderated_by']);
    }

    public function rules(): array
    {
        return [
            [['user_id', 'reason'], 'required'],
            [['user_id', 'moderated_by'], 'integer'],
            [['user_id', 'moderated_by'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['status', 'request_date', 'key'], 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID запроса',
            'reason' => 'Причина запроса',
            'status' => 'Статус',
            'request_date' => 'Дата обращения',
            'user_id' => 'Инициатор',
            'moderated_by' => 'Проверяющий'
        ];
    }

    public static function requestByKey(string $key): ?self
    {
        return self::findOne(['key' => $key]) ?? null;
    }

    /** @throws \Exception */
    public static function newRequest(string $login, string $reason): self|bool
    {
        $object = new self();
        $object->setAttributes(['reason' => $reason]);
        $object->generateUniqueKey();

        $user = $object->defineUser($login);
        if (is_null($user)) return false;

        $object->setAttribute('user_id', $user->id);

        return $object->save() ? $object : false;
    }

    public function updateStatus(RestorePasswordStatus $status): bool
    {
        return self::updateAll(['status' => $status->value], ['key' => $this->key]);
    }

    /** @throws Exception */
    private function generateUniqueKey(): void
    {
        $this->key = bin2hex(\Yii::$app->security->generateRandomKey(64));
    }

    private function defineUser(string $login): ?User
    {
        return User::findOneByLogin($login);
    }
}