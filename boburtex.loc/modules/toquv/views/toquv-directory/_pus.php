<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchPusFine app\modules\toquv\models\ToquvPusFineSearch */
/* @var $dataPusFine yii\data\ActiveDataProvider */



Pjax::begin(['id' => 'pusfineModelType_pjax']);
echo GridView::widget([
    'dataProvider' => $dataPusFine,
    'filterModel' => $searchPusFine,
    'columns' => [
        'name',
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}{delete}',
            'visibleButtons' => [
                'update' => function ($model, $key, $index) {
                    return Yii::$app->user->can('toquv-pus-fine/update') ?? false;
                },
                'delete' => function ($model, $key, $index) {
                    return Yii::$app->user->can('toquv-pus-fine/delete') ?? false;
                }
            ],
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
                        [
                        'title' => Yii::t('app', 'lead-update'),
                        'data-form-id' => $model->id,
                        'class' => "pusModelBtn btn btn-xs mr1 btn-primary",
                        'data-action-type' => 'update',
                        ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                        false,
                        [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn ajaxDeletePusFine btn-xs btn-danger",
                            'data-pjax-container' => 'pusfineModelType_pjax',
                            'data-deleted-url' => $url,
                        ]
                    );
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'update') {
                    $url = Url::to(['/toquv/toquv-pus-fine/update', 'id' => $model->id]);
                    return $url;
                }
                if ($action === 'delete') {
                    $url = Url::to(['toquv-pus-fine/delete', 'id' => $model->id]);
                    return $url;
                }
            }
        ],

    ],
]);
$this->registerJs('
$(".ajaxDeletePusFine").on("click", function (e) {
        e.preventDefault();
        var deleteUrl     = $(this).attr("data-deleted-url");
        var pjaxContainer = $(this).attr("data-pjax-container");
        var result = confirm("Are you sure you want to change status of this item?");
        if (result) {
            $.ajax({
                url:   deleteUrl,
                type:  "post",
                success:function(response){
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 4000;
                    PNotify.alert({text:response.message,type:response.status});
                    $.pjax.reload({container: "#" + $.trim(pjaxContainer)});
                },
                error: function (xhr, status, error) {
                    console.log("There was an error with your request."
                        + xhr.responseText);
                }
            });
        }
    });
');
Pjax::end(); ?>