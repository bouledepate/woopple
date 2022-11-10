<?php
/**
 * @var \yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

?>

<div class="container">
    <?= \Woopple\Components\Widgets\Error::widget([
        'title' => $name,
        'message' => $message,
        'exception' => $exception,
        'httpCode' => Yii::$app->getResponse()->getStatusCode()
    ]) ?>
</div>