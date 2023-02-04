<?php

namespace Woopple\Modules\Admin\Forms;

use yii\validators\Validator;

class PasswordValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'Введённые пароли должны быть идентичны';
    }

    /**
     * @param CreateUserForm $model
     * @param $attribute
     * @return void
     */
    public function validateAttribute($model, $attribute): void
    {
        if ($model->$attribute !== $model->passwordRepeat) {
            $model->addError($attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view): string
    {
        $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return <<<JS
if ($("#createuserform-password").val() !== $("#createuserform-password-repeat").val()) {
    messages.push($message);
}
JS;
    }
}