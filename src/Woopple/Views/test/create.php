<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\Test\Test $model
 */

use yii\bootstrap4\ActiveForm;
use Woopple\Models\Test\TestAvailability;
use kartik\depdrop\DepDrop;
use yii\bootstrap4\Html;

$this->title = 'Создание нового теста'; ?>

<?php $form = ActiveForm::begin(['id' => 'new-test-form']) ?>
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <span class="h5">Основная информация</span>
            </div>

            <div class="card-body">
                <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>
                <?= $form->field($model, 'availability')->dropDownList(TestAvailability::titles(), [
                    'value' => TestAvailability::TEAM_ONLY->value
                ]) ?>
                <?= $form->field($model, 'subject_id')->widget(DepDrop::class, [
                    'pluginOptions' => [
                        'placeholder' => 'Выберите субъект',
                        'depends' => ['test-availability'],
                        'url' => \yii\helpers\Url::to(['test/get-subjects']),
                        'initialize' => true
                    ]
                ]) ?>
                <?= $form->field($model, 'expiration_date')->widget(\kartik\datetime\DateTimePicker::class, [
                    'options' => ['placeholder' => 'Выберите дату окончания'],
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]) ?>
                <?= Html::activeHiddenInput($model, 'questions_raw', ['id' => 'test-questions_raw']) ?>
                <?= Html::hiddenInput(name: 'Test[questions_count]', options: ['id' => 'test-questions_count', 'value' => 1]) ?>
                <?= Html::activeHiddenInput($model, 'author_id', ['value' => Yii::$app->user->id]) ?>
                <hr>
                <?= Html::submitButton('Создать тест', ['class' => 'btn btn-info btn-block']) ?>
            </div>
        </div>

    </div>
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <span class="h5">Вопросы к тесту</span>
                <span class="float-right">
                    <?= \yii\helpers\Html::button('Новый вопрос', [
                        'id' => "create-question",
                        'class' => 'btn btn-outline-primary btn-sm'
                    ]) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="accordion" id="questions">
                    <!-- Вопросы будут добавляться сюда -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
<?php
$js = <<<JS
    $("#test-availability").on('change', function (event) {
        let value = event.target.value;
        if (value === 'common') {
            $('.field-test-subject_id').hide();
        } else {
            $('.field-test-subject_id').show();
        }
    })
JS;
$this->registerJs($js);
?>
