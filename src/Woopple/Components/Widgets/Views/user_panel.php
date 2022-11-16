<?php
/**
 * @var string $profileImage
 * @var string $username
 * @var string $assetDir
 */

// todo: Убрать после настройки пользователей
$profileImage = $assetDir . '/img/user2-160x160.jpg';
$username = 'Semyon Hertsen'
?>

<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="<?= $profileImage ?>" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
        <a href="#" class="d-block"><?= $username ?></a>
    </div>
</div>
