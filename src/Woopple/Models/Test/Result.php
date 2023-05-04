<?php

namespace Woopple\Models\Test;

use Woopple\Models\User\User;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $reviewer_id
 * @property int $mark
 * @property string $feedback
 * @property-read User $user,
 * @property-read User $reviewer
 */
class Result extends ActiveRecord
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'reviewer_id', 'test_id', 'mark', 'feedback'], 'safe']
        ];
    }


}