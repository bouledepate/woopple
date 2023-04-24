<?php
/**
 * @var \yii\web\View $this
 * @var array $departments
 * @var array $users
 */

use yii\bootstrap4\Modal;

$this->title = 'Команды компании'; ?>

<div class="row">
    <div class="col-md-3 my-3">
        <?= \yii\helpers\Html::a('Создать команду', \yii\helpers\Url::to(['hr/create-team']), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Просмотр команд внутри отдела
    </div>
    <div class="card-body row">
        <div class="col-6">
            <?= \yii\helpers\Html::dropDownList(name: 'department', items: $departments, options: [
                'prompt' => 'Выберите отдел',
                'class' => 'form-control',
                'onchange' => 'loadTeamData(this)',
                'id' => 'departmentField'
            ]) ?>
        </div>
        <div class="col-2">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Команды</div>
    <div class="card-body">
        <div id="loading-spinner" style="display: none">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div id="team-data"><i>Ничего не выбрано</i></div>
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
    function loadTeamData(event) {
        let id = $('#departmentField').find(":selected").val();
        $('#team-data').empty()
        $('#loading-spinner').show()
        $.ajax({
            url: '/hr/get-team?id=' + id,
            success: function (data) {
                $('#loading-spinner').hide()
                $('#team-data').html(data)
            },
            error: function (data) {
                $('#loading-spinner').hide()
                $('#team-data').html("<i>Ничего не найдено</i>")
            }
        })
    }

    function setLead() {
        let lead = document.getElementById('lead-field').value
        let team = document.getElementById('team-field').value
        $.ajax({
            type: 'GET',
            url: "/hr/set-team-lead?team=" + team + "&lead=" + lead
        })
    }
</script>
