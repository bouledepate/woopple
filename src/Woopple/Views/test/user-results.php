<?php

/**
 * @var \yii\web\View $this
 * @var \Woopple\Models\User\User $user
 * @var \Woopple\Models\Test\Test $test
 * @var \Woopple\Models\Test\Result $result
 */

$this->title = 'Результаты тестирования сотрудника';

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
  color: green;
}

.wrong-answer {
  color: red;
}

CSS;
$this->registerCss($css);


?>
<div class="row">
    <div class="col-3">
        <?= $this->render('_respondent', compact('user')) ?>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Информация о результатах тестирования</span>
            </div>
            <div class="card-body">
                <span class="card-text font-weight-bold">Обратная связь от руководителя</span>
                <?php if (!empty($result->feedback)): ?>
                    <p class="card-text"><?= $result->feedback ?></p>
                <?php endif; ?>
                <p class="card-text"><span
                        class="card-text font-weight-bold">Количество верных ответов:</span> <?= $result->mark ?></p>
            </div>
        </div>
        <a href="<?= \yii\helpers\Url::to(['test/user-tests']) ?>" class="btn btn-info btn-sm btn-block">К вашим тестам</a>
        <a href="<?= \yii\helpers\Url::to(['profile/profile']) ?>" class="btn btn-danger btn-sm btn-block">На главную</a>
    </div>
    <div class="col-9">
        <div class="card card-primary">
            <div class="card-header">
                <h5 class="card-title">Результаты тестирования</h5>
            </div>
            <div class="card-body">
                <?php foreach ($test->questions as $question): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-title">
                                <?php
                                $badgeStyle = "";
                                $badgeText = "";
                                $userAnswer = $question->fetchUserAnswer($user);

                                if ($question->is_strict) {
                                    if ($userAnswer->is_correct) {
                                        $badgeStyle = "badge-success";
                                        $badgeText = "1/1";
                                    } else {
                                        $badgeStyle = "badge-danger";
                                        $badgeText = "0/1";
                                    }
                                } else {
                                    $badgeStyle = "badge-secondary";
                                    $badgeText = "N/A";
                                }
                                ?>
                                <div class="badge badge-pill <?= $badgeStyle ?>"><?= $badgeText ?></div>
                                <?= $question->title ?>
                            </h3>
                            <p class="card-text"><em><?= $question->description ?></em></p>
                            <?php if ($question->type == 'open'): ?>
                                <?php
                                $userAnswerText = "";
                                $isCorrect = false;
                                $answerStyle = "";
                                foreach ($answers as $userAnswer) {
                                    if ($userAnswer->question_id == $question->id) {
                                        $userAnswerText = $userAnswer->text;
                                        $isCorrect = $userAnswer->is_correct;
                                        break;
                                    }
                                }
                                if ($question->is_strict) {
                                    $answerStyle = $isCorrect ? 'text-success' : 'text-danger';
                                }
                                ?>
                                <textarea class="form-control <?= $answerStyle ?>" disabled><?= $userAnswerText ?></textarea>
                            <?php else: ?>
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

                                    if ($isChecked) {
                                        $answerStyle = "font-weight-bold";
                                    } else {
                                        $answerStyle = "";
                                    }
                                    ?>
                                    <div class="form-check">
                                        <input class="<?= $answerStyle ?>" type="<?= $inputType ?>"
                                               disabled <?= $isChecked ? "checked" : "" ?>>
                                        <label class="<?= $answerStyle ?>"><?= $possibleAnswer->text ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <?php if ($question->is_strict): ?>
                                    <p><span class="font-weight-bold">Правильный ответ:</span> <?= implode(', ', array_filter($question->answers, function ($answer) { return $answer->is_correct; })) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
