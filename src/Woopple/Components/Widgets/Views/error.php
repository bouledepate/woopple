<?php
/**
 * @var string $title
 * @var string $message
 * @var string $status
 * @var string $style
 */
?>


<div class="d-flex bg-white shadow-sm justify-content-center">
    <div class="p-2 align-self-center">
        <h1 class="display-1 <?= $style ?>"><?= $status ?></h1>
    </div>
    <div class="p-2 align-self-center">
        <h3 class="headline"><?= $title ?></h3>
        <p><?= $message ?></p>
    </div>
</div>

