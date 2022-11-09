<?php
/**
 * @var string $navbarItems ;
 * @var boolean $showMessages ;
 * @var int $messagesCount ;
 * @var string $messages ;
 * @var boolean $showNotifications ;
 * @var int $notificationsCount ;
 * @var string $notifications ;
 * @var boolean $fullscreen
 */
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <?= $navbarItems ?>
    </ul>

    <ul class="navbar-nav ml-auto">
        <?php if ($showMessages): ?>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#"><i class="far fa-comments"></i>
                    <?php if ($messagesCount > 0): ?>
                        <span class="badge badge-danger navbar-badge"><?= $messagesCount ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <?= $messages ?>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>
        <?php endif; ?>

        <?php if ($showNotifications): ?>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <?php if ($notificationsCount > 0): ?>
                        <span class="badge badge-warning navbar-badge"><?= $notificationsCount ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header"><?= $notificationsCount ?> Notifications</span>
                    <div class="dropdown-divider"></div>
                    <?= $notifications ?>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
        <?php endif; ?>

        <!-- <li class="nav-item">-->
        <!-- Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link'])-->
        <!-- </li>-->

        <?php if ($fullscreen): ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        <?php endif; ?>

        <?php if (\Core\Enums\Environment::current() === \Core\Enums\Environment::DEVELOPMENT): ?>
            <li class="nav-item">
                <a class="nav-link" data-widgete="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
