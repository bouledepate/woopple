<?php

namespace Woopple\Modules\Admin\Controllers;

use Core\Enums\Permission;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [
                            Permission::ACCESS_ADMIN_PANEL->value
                        ]
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException('You are not allowed to access this page');
                }
            ]
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}