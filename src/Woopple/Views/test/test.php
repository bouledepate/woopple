<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\Test\Test $test
 * @var \Woopple\Models\Test\Question[] $questions
 * @var
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use Woopple\Forms\AnswersForm;

$this->title = 'Тестирование';
$answerForm = new AnswersForm();

$css = <<<CSS
.card-title {
  font-weight: bold;
}

.card-text em {
  color: #6c757d;
  font-size: 0.9em;
  font-style: italic;
}

CSS;
$this->registerCss($css);

?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h1 class="card-title"><?= $test->title ?></h1>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?php $i = 1; ?>
        <?php foreach ($questions as $question): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title"><?= $question->title ?></h3>
                    <p class="card-text"><em><?= $question->description ?></em></p>
                    <?php if ($question->type === 'open'): ?>
                        <div class="form-group">
                            <label for="question<?= $question->id ?>">Ваш ответ:</label>
                            <textarea class="form-control" id="question<?= $question->id ?>"
                                      name="AnswerForm[answers][<?= $question->id ?>][answer]"></textarea>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label><?= $question->is_multiple ? 'Выберите все, что подходит:' : 'Выберите один вариант:' ?></label>
                            <?php foreach ($question->answers as $answer): ?>
                                <div class="form-check">
                                    <?php if ($question->is_multiple): ?>
                                        <input class="form-check-input" type="checkbox"
                                               name="AnswerForm[answers][<?= $question->id ?>][answer][]"
                                               value="<?= $answer->id ?>">
                                    <?php else: ?>
                                        <input class="form-check-input" type="radio"
                                               name="AnswerForm[answers][<?= $question->id ?>][answer]"
                                               value="<?= $answer->id ?>">
                                    <?php endif; ?>
                                    <label class="form-check-label"><?= $answer->text ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    $('form').on('beforeSubmit', function(event) {
        var answers = {};
        $('input[type=checkbox]:checked, input[type=radio]:checked, textarea').each(function() {
            var questionId = $(this).attr('name').match(/(\d+)/)[1];
            var answer = $(this).val();
            if ($(this).attr('type') === 'checkbox') {
                if (!answers[questionId]) {
                    answers[questionId] = [];
                }
                answers[questionId].push(answer);
            } else {
                answers[questionId] = answer;
            }
        });
        $('#user-answers').val(JSON.stringify(answers));
        return true;
    });
</script>