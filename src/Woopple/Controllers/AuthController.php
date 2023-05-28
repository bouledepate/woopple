<?php

namespace Woopple\Controllers;

use Woopple\Components\Enums\RestorePasswordStatus;
use Woopple\Forms\LoginForm;
use Woopple\Forms\Restore\PasswordForm;
use Woopple\Forms\Restore\RequestForm;
use Woopple\Forms\Restore\RestoreStep;
use Woopple\Models\Restore;
use Woopple\Models\User\User;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;

class AuthController extends Controller
{
    public $layout = 'auth/login';
    private const RESTORE_COOKIE = 'X-restore-key';

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

    /** @throws /\Throwable */
    public function actionRestore(): string|Response
    {
        $step = $this->defineCurrentRestorePasswordStep();
        $view = $this->getViewByStep($step);
        $form = is_null($step) ? new RequestForm() : $this->getFormByStep($step);

        if (\Yii::$app->request->isPost) {
            if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
                if ($step === RestorePasswordStatus::WAITING) {
                    $key = \Yii::$app->request->cookies->get(self::RESTORE_COOKIE);
                    $request = Restore::requestByKey($key);
                    if (!is_null($request)) {
                        $request->user->security->resetDefaultPassword($form->password);
                        $request->updateStatus(RestorePasswordStatus::DONE);
                        \Yii::$app->response->cookies->remove(self::RESTORE_COOKIE);
                        $view = $this->getViewByStep(RestorePasswordStatus::DONE);
                    }
                } else {
                    $request = Restore::newRequest($form->login, $form->reason);
                    if ($request) {
                        \Yii::$app->response->cookies->add(new Cookie([
                            'name' => self::RESTORE_COOKIE,
                            'value' => $request->key,
                            'httpOnly' => true
                        ]));
                        $view = $this->getViewByStep(RestorePasswordStatus::NEW);
                    }
                }
            }
        }

        if ($step === RestorePasswordStatus::DONE) {
            \Yii::$app->response->cookies->remove(self::RESTORE_COOKIE);
        }

        return $this->render($view, ['form' => $form]);
    }

    private function defineCurrentRestorePasswordStep(): ?RestorePasswordStatus
    {
        $step = null;
        $key = \Yii::$app->request->cookies->get(self::RESTORE_COOKIE);

        if (!is_null($key)) {
            $data = Restore::findOne(['key' => $key]);
            $step = RestorePasswordStatus::tryFrom($data->status);
        }

        return $step;
    }

    /** @return RequestForm|PasswordForm */
    private function getFormByStep(RestorePasswordStatus $step): ?RestoreStep
    {
        return match ($step) {
            RestorePasswordStatus::NEW => new RequestForm(),
            RestorePasswordStatus::WAITING => new PasswordForm(),
            default => null
        };
    }

    private function getViewByStep(?RestorePasswordStatus $step): string
    {
        return match ($step) {
            RestorePasswordStatus::NEW => 'restore_new',
            RestorePasswordStatus::IN_PROGRESS => 'restore_in_progress',
            RestorePasswordStatus::WAITING => 'restore_password',
            RestorePasswordStatus::REFUSED => 'restore_refused',
            RestorePasswordStatus::DONE => 'restore_done',
            default => 'restore'
        };
    }

    public function actionChangePassword()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        if (!$user->security->reset_pass) {
            $this->notice('warning', 'Вы не можете изменить свой пароль. Если вам это необходимо, подайте заявку на странице авторизации.');
            return $this->redirect(['profile/profile', 'login' => $user->login]);
        }

        $form = new PasswordForm();

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            $user->security->resetDefaultPassword($form->password);
            \Yii::$app->user->logout();
            return $this->redirect('/auth/login');
        }

        return $this->render('reset-pass', compact('form'));
    }

    private function notice(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'Раздел авторизационных данных',
            'message' => $message
        ]);
    }
}