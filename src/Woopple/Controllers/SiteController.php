<?php

namespace Woopple\Controllers;

use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    /** @throws BadRequestHttpException */
    public function beforeAction($action): bool
    {
        \Yii::$app->session->set('layoutKey', 'site');
        return parent::beforeAction($action);
    }

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

    public function actionLogin(): string
    {

    }

    public function actionLogout(): string
    {

    }
}