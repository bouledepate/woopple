<?php
/**
 * @var string $layout
 */
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= Yii::$app->getHomeUrl() ?>" class="brand-link">
        <img src="themes/v1/build/images/logo/logo_round_short_v2.svg" alt="Woopple Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light brand">Woopple</span>
    </a>
    <div class="sidebar">
        <?= \Woopple\Components\Widgets\UserPanel::widget() ?>
        <nav class="mt-2">
            <?= \Woopple\Components\Widgets\Menu::widget([
                'layout' => $layout
            ]); ?>
        </nav>
    </div>
</aside>