<?php

declare(strict_types=1);

namespace Woopple\Components\Rbac;

use Woopple\Models\User\User;

final class Rbac
{
    private const TYPE_ROLE = 1;
    private const TYPE_PERMISSION = 2;

    /** @return Role[] */
    public static function roles(): array
    {
        $data = self::load(self::TYPE_ROLE);
        $response = array_map(function (array $raw) {
            return new Role(
                $raw['key'],
                $raw['description'],
                array_map(fn(array $perm) => new Permission($perm['key'], $perm['description']), $raw['children'])
            );
        }, $data);
        return array_values($response);
    }


    /** @return Permission[] */
    public static function permissions(): array
    {
        $data = self::load(self::TYPE_PERMISSION);
        return array_map(fn(array $item) => new Permission($item['key'], $item['description']), $data);
    }

    public static function role(\Core\Enums\Role|string $requestRole): Role
    {
        $data = self::roles();
        $key = $requestRole instanceof \Core\Enums\Role ? $requestRole->value : $requestRole;
        /** @var Role $role */
        $result = array_filter($data, function (Role $role) use ($key) {
            return $role->key == $key;
        });
        return array_shift($result);
    }

    /** @return Permission[] */
    public static function permissionsByRole(\Core\Enums\Role|string $requestRole): array
    {
        $data = self::roles();
        $key = $requestRole instanceof \Core\Enums\Role ? $requestRole->value : $requestRole;

        /** @var Role $role */
        $result = array_filter($data, function (Role $role) use ($key) {
            return $role->key == $key;
        });
        $role = array_shift($result);

        return $role->permissions;
    }

    /**
     * @param string[] $roles
     * @return Role[]
     */
    public static function parse(array $roles): array
    {
        $data = self::roles();
        $result = array_map(function (Role $role) use ($roles) {
            if (in_array($role->key, $roles)) {
                return $role;
            }
            return null;
        }, $data);
        return array_filter($result);
    }

    private static function load(int $type): array
    {
        $data = require dirname(__DIR__, 4) . '/app/woopple/rbac/items.php';
        $response = array_map(function (string $key, array $raw) use ($data, $type) {
            if ($raw['type'] == $type) {
                $item = ['key' => $key, 'description' => $raw['description']];
                if (isset($raw['children'])) {
                    $item['children'] = array_map(fn(string $perm) => [
                        'key' => $perm,
                        'description' => $data[$perm]['description']
                    ], $raw['children']);
                }
                return $item;
            }
            return null;
        }, array_keys($data), $data);
        return array_filter($response);
    }
}