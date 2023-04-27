<?php
/**
 * @var string $url
 * @var string $title
 * @var boolean $access
 */
?>

<?php if ($access): ?>
    <li><a href="<?= $url ?>" class="dropdown-item"><?= $title ?></a></li>
<?php endif; ?>
