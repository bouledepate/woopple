<?php

namespace Woopple\Components\Rbac;

use Yii;
use Woopple\Models\User\User;
use yii\rbac\CheckAccessInterface;

final class AccessChecker implements CheckAccessInterface
{
    public function checkAccess($userId, $permissionName, $params = []): bool
    {
        if (is_null($userId)) return false;

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $roles = Rbac::parse($user->roles->getValue());

        foreach ($roles as $role) {
            if ($role->key === $permissionName) return true;
            foreach ($role->permissions as $permission) {
                if ($permission->key === $permissionName) return true;
            }
        }

        return false;
    }
}