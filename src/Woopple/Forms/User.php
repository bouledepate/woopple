<?php

namespace Woopple\Forms;

use yii\base\Model;

class User extends Model
{
    public string $login;
    public string $email;
    public string $password;
    public string $firstName;
    public string $secondName;
    public string $lastName;
    public string $birthday;

    public function rules(): array
    {
        return [

        ];
    }
}