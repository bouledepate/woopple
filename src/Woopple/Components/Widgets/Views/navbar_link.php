<?php
/**
 * @var string $url
 * @var string $title
 * @var boolean $access
 */
?>

<?php if ($access): ?>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= $url ?>" class="nav-link"><?= $title ?></a>
    </li>
<?php endif; ?>
