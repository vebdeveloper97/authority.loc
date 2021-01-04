<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\MatoInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mato Info');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mato-info-index">
    <?php if (Yii::$app->user->can('mato-info/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'mato-info_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'musteri_id',
                'value' => function($m){
                    return $m->musteri->name;
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'musteri_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvOrders::getMusteriList()],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'headerOptions' => [
                    'style' => 'min-width:150px'
                ]
            ],
            [
                'attribute' => 'entity_id',
                'value' => function($m){
                    return $m->entity->name;
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'entity_id',
                    'data' => \app\modules\base\models\ModelsRawMaterials::getMaterialList(\app\modules\toquv\models\ToquvRawMaterials::MATO),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'escapeMarkup' => new JsExpression("function (markup) { 
                            return markup;
                         }"),
                        'templateResult' => new JsExpression("function(data) {
                           return data.text;
                        }"),
                        'templateSelection' => new JsExpression("
                            function (data) { return data.text; }
                        "),
                    ],
                ]),
                'headerOptions' => [
                    'style' => 'min-width:200px'
                ]
            ],
//            'entity_type',
            [
                'attribute' => 'pus_fine_id',
                'value' => function($m){
                    return $m->pusFine->name;
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'pus_fine_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvMakine::getPusFineList()],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            'thread_length',
            'finish_en',
            'finish_gramaj',
            [
                'attribute' => 'type_weaving',
                'value' => function($m){
                    return $m->typeWeaving->name;
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'type_weaving',
                    'data' => \app\models\Constants::getTypeWeaving(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            //'toquv_rm_order_id',
            //'toquv_instruction_rm_id',
            //'toquv_instruction_id',
            //'status',
            //'created_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('mato-info/view'),
                    /*'update' => function($model) {
                        return Yii::$app->user->can('mato-info/update'); // && $model->status !== $model::STATUS_SAVED;
                    },*/
                    /*'delete' => function($model) {
                        return Yii::$app->user->can('mato-info/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }*/
                ],
                'buttons' => [
                    /*'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success',
                            'data-form-id' => $model->id,
                        ]);
                    },*/
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-primary view-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    /*'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },*/

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'mato-info',
    'crud_name' => 'mato-info',
    'modal_id' => 'mato-info-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Mato Info') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'mato-info_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
$css = <<< CSS
#select2-matoinfosearch-musteri_id-results strong.select2-results__group,#select2-matoinfosearch-pus_fine_id-results strong.select2-results__group{
    display:none;
}
CSS;
$this->registerCss($css);
