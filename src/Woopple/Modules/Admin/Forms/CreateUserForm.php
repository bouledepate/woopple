<?php

namespace Woopple\Modules\Admin\Forms;

use Core\Enums\Role;
use Woopple\Models\User\User;
use Woopple\Models\User\UserManagementInterface;
use yii\base\Exception;
use yii\base\Model;

class CreateUserForm extends Model implements UserManagementInterface
{
    public $login = '';
    public $email = '';
    public $password = '';
    public $passwordRepeat = '';
    public $roles = [];

    /** @throws Exception */
    public function rules(): array
    {
        return [
            [['login', 'email', 'password', 'passwordRepeat'], 'required'],
            [['login', 'email', 'password', 'passwordRepeat'], 'string'],
            [['login', 'email', 'password', 'passwordRepeat'], 'trim'],
            [['login', 'email'], 'unique', 'targetClass' => User::class],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => "Введённые пароли не совпадают"],
//            ['password', 'match', 'pattern' => '/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/'],
            ['roles', 'each', 'rule' => ['in', 'range' => Role::values()]],
            ['email', 'email']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Имя пользователя',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повторение пароля',
            'roles' => 'Доступные роли'
        ];
    }

    public function attributeHints(): array
    {
        return [
            'login' => 'Логин пользователя для входа в систему',
            'password' => 'Данный пароль используется для первого входа в систему. Далее пользователь задаёт собственный пароль'
        ];
    }

    public function userData(): array
    {
        return $this->getAttributes();
    }
}