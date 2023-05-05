<?php
/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\Test\Test $test
 * @var \Woopple\Models\Test\Question[] $questions
 * @var \Woopple\Models\Test\UserAnswer[] $answers
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use Woopple\Forms\AnswersForm;

$this->title = 'Проверка тестирования';
$answerForm = new AnswersForm();
$user = $answers[0]->user;

$css = <<<CSS
.card-title {
  font-weight: bold;
}

.card-text em {
  color: #6c757d;
  font-size: 0.9em;
  font-style: italic;
}

.correct-answer {
  font-weight: bold;
  color: green;
}

.wrong-answer {
  font-weight: bold;
  color: red;
}

CSS;
$this->registerCss($css);

?>

<?php if ($test): ?>
    <div class="row">
        <div class="col-md-3">
           <?= $this->render('_respondent', compact('user')) ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1 class="card-title"><?= $test->title ?></h1>
                </div>

                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?php foreach ($answers as $answer): ?>
                        <?php $question = $answer->question; ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="card-title"><?= $question->title ?></h3>
                                <p class="card-text"><em><?= $question->description ?></em></p>

                                <?php if ($question->type === 'open'): ?>
                                    <div class="form-group">
                                        <label for="question<?= $question->id ?>">Ответ пользователя:</label>
                                        <textarea class="form-control" id="question<?= $question->id ?>"
                                                  disabled><?= $answer->text ?></textarea>
                                    </div>
                                    <?php if ($question->is_strict): ?>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                   id="correct<?= $question->id ?>"
                                                   name="correct[<?= $answer->id ?>]">
                                            <label class="form-check-label" for="correct<?= $question->id ?>">Правильный
                                                ответ</label>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="form-group">
                                        <?php foreach ($question->answers as $possibleAnswer): ?>
                                            <?php
                                            $inputType = $question->is_multiple ? 'checkbox' : 'radio';
                                            $isChecked = false;
                                            $answerStyle = null;
                                            $userAnswer = null;

                                            foreach ($answers as $userAnswer) {
                                                $values = $userAnswer->answer_ids?->getValue() ?? [];
                                                if (in_array($possibleAnswer->id, $values)) {
                                                    $isChecked = true;
                                                }
                                            }

                                            if ($question->is_strict) {
                                                if ($isChecked && $possibleAnswer->is_correct) {
                                                    $answerStyle = "text-success";
                                                } elseif ($isChecked && !$possibleAnswer->is_correct) {
                                                    $answerStyle = "text-danger";
                                                } elseif (!$isChecked && $possibleAnswer->is_correct) {
                                                    $answerStyle = "text-success";
                                                }
                                            } else {
                                                if ($isChecked) {
                                                    $answerStyle = "font-weight-bold";
                                                } else {
                                                    $answerStyle = "";
                                                }
                                            }
                                            ?>
                                            <div class="form-check">
                                                <input class="<?= $answerStyle ?>" type="<?= $inputType ?>"
                                                       disabled <?= $isChecked ? "checked" : "" ?>>
                                                <label class="<?= $answerStyle ?>"><?= $possibleAnswer->text ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="form-group">
                        <label for="feedback">Отзыв о результатах тестирования:</label>
                        <textarea class="form-control" id="feedback" name="feedback"></textarea>
                    </div>

                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" name="notification" class="custom-control-input" id="notification">
                        <label class="custom-control-label" for="notification">Отобразить результаты в профиле сотрудника</label>
                    </div>

                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger" role="alert">
        Не удалось найти ответы для проверки.
    </div>
<?php endif; ?>

<script>
    $('form').on('beforeSubmit', function (event) {
        var correctAnswers = {};
        $('input[type=checkbox]').each(function () {
            var answerId = $(this).attr('name').match(/(\d+)/)[1];
            var isChecked = $(this).is(':checked');
            correctAnswers[answerId] = isChecked;
        });
        $('#correct-answers').val(JSON.stringify(correctAnswers));
        return true;
    });
</script>

