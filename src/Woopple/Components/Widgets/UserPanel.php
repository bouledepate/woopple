<?php

namespace Woopple\Components\Widgets;

class UserPanel extends Widget
{
    protected string $username;
    protected string $profileImage;

    // todo: Инициализировать свойства виджета после создания логики пользователей и профилей.
    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('user_panel', $this->getViewParams());
    }

    protected function getViewParams(): array
    {
        return [
            'username' => '',
            'profileImage' => '',
            // todo: Remove this.
            'assetDir' => \Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist')
        ];
    }
}