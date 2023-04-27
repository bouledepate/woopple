<?php

namespace Woopple\Components\Auth;

use Woopple\Models\User\User;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class Identity implements IdentityInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __get(string $name): mixed
    {
        return $this->user->$name;
    }

    public function __call(string $name, array $arguments): mixed
    {
        return $this->user->$name($arguments);
    }

    public static function findIdentity($id): ?self
    {
        $user = User::findOne(['id' => $id]);
        return $user ? new self($user) : null;
    }

    /**
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): never
    {
        throw new NotSupportedException(__METHOD__ . ' not supported.');
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getAuthKey(): string
    {
        return '';
    }

    public function validateAuthKey($authKey): bool
    {
        return true;
    }
}