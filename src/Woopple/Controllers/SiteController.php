<?php

namespace Woopple\Controllers;

use yii\db\Exception;
use yii\web\BadRequestHttpException;
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

    /** @throws BadRequestHttpException */
    public function beforeAction($action): bool
    {
        \Yii::$app->session->set('layoutKey', 'site');
        return parent::beforeAction($action);
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}