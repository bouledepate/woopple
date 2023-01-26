<?php

namespace Woopple\Models\User;

use yii\db\ActiveRecord;

class UserSecurity extends ActiveRecord
{
    public function rules(): array
    {
        return [
            ['password_hash', 'required'],
            ['password_hash', 'string'],
            ['password_hash', 'trim'],
            ['reset_pass', 'default', 'value' => true]
        ];
    }
}