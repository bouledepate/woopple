<?php

namespace Woopple\Controllers;

use Woopple\Forms\LoginForm;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    public $layout = 'auth/login';

    public function actionLogin(): string|Response
    {
        $form = new LoginForm();

        if (\Yii::$app->request->isPost) {
            if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
                $form->login();
                return $this->redirect(\Yii::$app->user->getReturnUrl() ?? "/");
            }
        }

        return $this->render('login', ['form' => $form]);
    }

    public function actionLogout(): Response
    {
        \Yii::$app->user->logout();
        return $this->redirect('/');
    }
}