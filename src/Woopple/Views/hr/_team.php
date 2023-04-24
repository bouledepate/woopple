<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Html;

?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'name',
            'contentOptions' => [
                'width' => '60%'
            ]
        ],
        [
            'attribute' => 'lead',
            'format' => 'html',
            'contentOptions' => [
                'width' => '30%'
            ],
            'value' => function (\Woopple\Models\Structure\Team $data) {
                return is_null($data->lead)
                    ? "Не назначен"
                    : "<a href='/profile/{$data->teamLead->login}'>{$data->teamLead->profile->fullName()}</a>";
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => [
                'width' => '10%'
            ],
            'template' => '{lead} {edit}',
            'buttons' => [
                'lead' => function ($url, \Woopple\Models\Structure\Team $model) {
                    return Html::button('Назначить руководителя', [
                        'title' => 'Обновить',
                        'data-toggle' => 'modal',
                        'data-target' => '#add-team-lead',
                        'class' => 'btn btn-sm btn-warning btn-block',
                        'data-team' => $model->id,
                        'onclick' => 'setTeam(this)'
                    ]);
                },
                'edit' => function ($url, \Woopple\Models\Structure\Team $model) {
                    return Html::a('Редактировать', \yii\helpers\Url::to(['hr/modify-team', 'id' => $model->id]), [
                        'class' => 'btn btn-sm btn-info btn-block'
                    ]);
                }
            ],
            'visibleButtons' => [
                'lead' => function (\Woopple\Models\Structure\Team $model) {
                    return is_null($model->lead);
                }
            ]
        ]
    ]
]) ?>
<script type="application/javascript">
    function setTeam(object) {
        let id = object.dataset.team;
        $("#team-field").val(id)
    }
</script>