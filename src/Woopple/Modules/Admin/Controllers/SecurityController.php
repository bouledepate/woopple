<?php

namespace Woopple\Modules\Admin\Controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class SecurityController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'action' => ['index'],
                        'allow' => true,
                        'roles' => []
                    ]
                ]
            ]
        ];
    }
}