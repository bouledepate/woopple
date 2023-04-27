<?php

namespace Woopple\Models\Test;

use yii\db\ActiveRecord;


class UserTesting extends ActiveRecord
{
    public function rules()
    {
        return [
            [['user_id', 'test_id', 'status'], 'safe']
        ];
    }

    public static function createNote(Test $test, int $user, TestStatus $status): void
    {
        $object = new self();
        $object->setAttributes(['test_id' => $test->id, 'user_id' => $user, 'status' => $status->value]);
        $object->save();
    }

    public static function updateNote(Test $test, int $user, TestStatus $status): void
    {
        $object = self::findOne(['test_id' => $test->id, 'user_id' => $user]);
        if (is_null($object)) {
            $object = new self();
        }
        $object->setAttributes(['test_id' => $test->id, 'user_id' => $user, 'status' => $status->value]);
        $object->save();
    }
}