<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Forms\Hr\ManageTeamForm $model
 */

use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2Asset;

$this->title = 'Управление командами';

$cardTitle = is_null($model->id) ? 'Данные о команде' : "Данные о команде \"{$model->name}\"";

// Register the Select2Asset bundle
Select2Asset::register($this);

// CSS to hide the search field
$this->registerCss("
    .select2-search {
        display: none;
    }
");


?>

<div class="card">
    <div class="card-header"><?= $cardTitle ?></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'lead_id')->widget(\kartik\select2\Select2::class, [
            'data' => $model->availableListOfLead
        ]) ?>
        <?= $form->field($model, 'department_id')->widget(\kartik\select2\Select2::class, [
            'data' => $model->departments,

        ]) ?>
        <?= $form->field($model, 'members')->widget(\kartik\select2\Select2::class, [
            'data' => $model->availableListOfMembers,
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true,
                'hideSearch' => true,
            ],
            'options' => [
                'multiple' => true,
                'prompt' => 'Добавьте сотрудников в команду',
            ]
        ]) ?>

        <?= \yii\helpers\Html::submitButton('Подтвердить', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>