<?php

namespace Woopple\Forms;

use Woopple\Components\Auth\Identity;
use Woopple\Models\User\User;
use yii\base\Model;

class LoginForm extends Model
{
    public function __construct(
        public string $login = '',
        public string $password = '',
        public string $email = '',
        public bool   $remember = false,
        private ?User $user = null
    )
    {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['login', 'email', 'password'], 'string'],
            [['login', 'email', 'password'], 'trim'],
            ['password', 'required'],
            ['login', 'required',
                'when' => fn($model) => empty($this->email),
                'whenClient' => "function (attribute, value) {return $('#email').val() !== \"\";}"
            ],
            ['email', 'required',
                'when' => fn($model) => empty($this->login),
                'whenClient' => "function (attribute, value) {return $('#login').val() !== \"\";}"
            ],
            ['login', 'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'login',
                'when' => fn($model) => empty($this->email),
                'message' => 'Пользователь с указанным логином в системе не найден'
            ],
            ['email', 'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'when' => fn($model) => empty($this->login),
                'message' => 'Пользователь с указанной электронной почтой в системе не найден'
            ],
            ['password', 'passwordValidator'],
            ['remember', 'boolean']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Логин в системе',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'remember' => 'Запомнить меня'
        ];
    }

    public function login(): bool
    {
        if (is_null($this->user)) $this->findUser();
        return \Yii::$app->user->login(new Identity($this->user), $this->remember ? 6 * 60 * 60 : 0);
    }

    private function findUser(): void
    {
        if (!empty($this->login)) {
            $this->user = User::findOneByLogin($this->login);
        }

        if (!empty($this->email)) {
            $this->user = User::findOneByEmail($this->email);
        }
    }

    public function passwordValidator($attribute, $params, $validator): void
    {
        $this->findUser();
        $validation = \Yii::$app->security->validatePassword(
            $this->password,
            $this->user->security->password_hash
        );
        if (!$validation) $this->addError($attribute, 'Введён неправильный пароль.');
    }
}