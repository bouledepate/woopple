<?php

namespace Woopple\Controllers;

use yii\web\BadRequestHttpException;
use yii\web\Controller;

class AdminController extends Controller
{
    /** @throws BadRequestHttpException */
    public function beforeAction($action): bool
    {
        \Yii::$app->session->set('layoutKey', 'admin');
        return parent::beforeAction($action);
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}