<?php
/**
 * @var \yii\web\View $this
 * @var array $departments
 * @var array $users
 */

use yii\bootstrap4\Modal;

/** @var \Woopple\Models\User\User $current */
$current = Yii::$app->user->identity;
$this->title = 'Команды компании'; ?>
<?php if ($current->isDepartmentLead()): ?>
    <div class="row">
        <div class="col-md-3 my-3">
            <?= \yii\helpers\Html::a('Создать команду', \yii\helpers\Url::to(['structure/create-team']), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
<?php endif; ?>


<div class="card">
    <div class="card-header">
        Просмотр команд внутри отдела
    </div>
    <div class="card-body row">
        <div class="col-6">
            <?= \yii\helpers\Html::dropDownList(name: 'department', items: $departments, options: [
                'prompt' => 'Выберите отдел',
                'class' => 'form-control',
                'onclick' => 'loadTeamData(this)',
                'id' => 'departmentField',
                'disabled' => true,
                'options' => [
                    $current->getDepartment()?->id => ['selected' => true]
                ]
            ]) ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Команды</div>
    <div class="card-body">
        <?= $this->render('_team', ['dataProvider' => $teamData]); ?>
    </div>
</div>

<?php Modal::begin([
    'id' => 'add-team-lead',
    'title' => 'Управление командой',
    'footer' => \yii\helpers\Html::button('Подтвердить', [
        'id' => 'lead-submit-btn',
        'class' => 'btn btn-success',
        'onclick' => 'setLead()'
    ])
]) ?>
<?= \yii\helpers\Html::hiddenInput('team_id', null, ['id' => 'team-field']) ?>
<?= \kartik\select2\Select2::widget([
    'name' => 'team_lead_id',
    'id' => 'lead-field',
    'data' => $users,
    'options' => [
        'prompt' => 'Выберите руководителя'
    ]
]) ?>
<?php Modal::end() ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="application/javascript">
    function setLead() {
        let lead = document.getElementById('lead-field').value
        let team = document.getElementById('team-field').value
        $.ajax({
            type: 'GET',
            url: "/structure/set-team-lead?team=" + team + "&lead=" + lead
        })
    }
</script>
