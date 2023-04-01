<?php

namespace Woopple\Components\Widgets;

use Woopple\Models\User\User;

class UserPanel extends Widget
{
    protected string $username;
    protected string $profileImage;

    // todo: Инициализировать свойства виджета после создания логики пользователей и профилей.
    public function init(): void
    {
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();
        $this->username = !is_null($user) ? $user->profile->shortlyName() : '';
        parent::init();
    }

    public function run(): string
    {
        return $this->render('user_panel', $this->getViewParams());
    }

    protected function getViewParams(): array
    {
        return [
            'username' => $this->username,
            'profileImage' => '',
            'assetDir' => \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist')
        ];
    }
}