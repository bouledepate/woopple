<?php

namespace Woopple\Controllers;

use yii\db\Exception;
use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class
            ]
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}