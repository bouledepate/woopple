<?php
/**
 * @var string $url
 * @var string $title
 * @var boolean $visible
 */
?>

<?php if ($visible): ?>
    <li><a href="<?= $url ?>" class="dropdown-item"><?= $title ?></a></li>
<?php endif; ?>
