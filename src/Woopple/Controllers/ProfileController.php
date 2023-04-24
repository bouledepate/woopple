<?php

namespace Woopple\Controllers;

use Woopple\Components\Enums\AccountStatus;
use Woopple\Forms\ProfileForm;
use Woopple\Models\Event\Event;
use Woopple\Models\User\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProfileController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function actionProfile(?string $login = null): string
    {
        $profile = new ProfileForm();

        if (is_null($login)) {
            /** @var User $user */
            $user = \Yii::$app->user->identity;
        } else {
            $user = User::findOneByLogin($login);
        }

        if (is_null($user) || $user->status == AccountStatus::CREATED->value) {
            throw new NotFoundHttpException();
        }

        return $this->render('profile', [
            'user' => $user,
            'events' => $this->receiveUserTimeline($user->id),
            'model' => $profile
        ]);
    }

    /** @throws \Throwable */
    public function actionUpdateProfile(): Response
    {
        if (\Yii::$app->request->isPost) {
            $form = new ProfileForm();
            $form->load(\Yii::$app->request->post());
            if ($form->validate() && $form->update()) {
                $this->sendNotification('success', 'Ваш профиль был успешно обновлён');
            } else {
                $this->sendNotification('error', 'При обновлении профиля произошла ошибка');
            }
        }

        return $this->redirect(\Yii::$app->request->getReferrer());
    }

    protected function receiveUserTimeline(int $id): array
    {
        return Event::find()
            ->where(['user_id' => $id])
            ->orderBy(['date' => SORT_DESC])
            ->all();
    }

    protected function sendNotification(string $type, string $message): void
    {
        \Yii::$app->session->addFlash('notifications', [
            'type' => $type,
            'title' => 'Обновление профиля',
            'message' => $message
        ]);
    }
}