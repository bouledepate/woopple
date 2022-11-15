<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="<?= Yii::$app->getHomeUrl() ?>" class="brand-link">
        <img src="v1/build/images/logo/logo_round_short_v2.svg" alt="Woopple Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light brand">Woopple</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Semyon Hertsen</a>
            </div>
        </div>
        <nav class="mt-2">
            <?= \Woopple\Components\Widgets\Menu::widget(); ?>
        </nav>
    </div>
</aside>