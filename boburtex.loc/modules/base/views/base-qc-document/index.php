<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\BaseQcDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Base Qc Document');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-qc-document-index">
    <?php if (Yii::$app->user->can('base-qc-document/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'nastel_no',
                'value' => function($model){
                    return "<code>".$model->nastel_no."</code>";
                },
                'format' => 'raw'
            ],
//            'mobile_process_production_id',
//            'norm_standart_id',
            [
                'attribute' => 'sort_id',
                'value' => function($model){
                    return (!empty($model->sort_id)) ? $model->sort->name : "";
                },
                'filter' => Select2::widget([
                    'attribute' => 'sort_id',
                    'model' => $searchModel,
                    'data' => \app\modules\toquv\models\SortName::getSortListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...'),
                        'allowClear' => true
                    ]
                ])
            ],
            'reg_date',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return ($model::getStatusList($model->status))?$model::getStatusList($model->status):$model->status;
                },
                'filter' => \app\modules\base\models\BaseQcDocument::getStatusList()
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('base-qc-document/view'),
                    'update' => function($model) {
                        return P::can('base-qc-document/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return P::can('base-qc-document/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-default view-dialog mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>

</div>

<?php
$this->registerCss("
.select2-container--krajee .select2-selection__clear{
    top: 0;
}
");
?>