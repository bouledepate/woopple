<?php

namespace Woopple\Models\User;

use Exception;
use Throwable;
use yii\db\ActiveRecord;

/**
 * @property string $password_hash
 * @property boolean $reset_pass
 */
class UserSecurity extends ActiveRecord
{
    public function rules(): array
    {
        return [
            ['password_hash', 'required'],
            ['password_hash', 'string'],
            ['password_hash', 'trim'],
            ['reset_pass', 'default', 'value' => true],
            ['user_id', 'safe']
        ];
    }

    /** @throws Throwable */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->hashPassword();
        return true;
    }

    /** @throws Throwable */
    public function resetDefaultPassword(string $password): bool
    {
        $this->password_hash = $password;
        $this->reset_pass = false;
        $this->hashPassword();
        return $this->update();
    }

    /** @throws Exception */
    private function hashPassword(): void
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
    }
}