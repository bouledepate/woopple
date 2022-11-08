<?php
/**
 * @var string $id
 * @var string $title
 * @var string $items
 * @var boolean $visible
 * @var boolean $submenu
 */
?>

<?php if ($visible): ?>
    <?php if ($submenu): ?>
        <li class="dropdown-submenu dropdown-hover">
            <a id="<?= $id ?>" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
               class="dropdown-item dropdown-toggle"><?= $title ?></a>
            <ul aria-labelledby="<?= $id ?>" class="dropdown-menu border-0 shadow">
                <?= $items ?>
            </ul>
        </li>
    <?php else: ?>
        <li class="nav-item dropdown">
            <a id="<?= $id ?>" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
               class="nav-link dropdown-toggle"><?= $title ?></a>
            <ul aria-labelledby="<?= $id ?>" class="dropdown-menu border-0 shadow">
                <?= $items ?>
            </ul>
        </li>
    <?php endif; ?>
<?php endif; ?>
