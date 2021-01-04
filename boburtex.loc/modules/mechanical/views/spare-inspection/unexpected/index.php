<?php

use app\modules\mechanical\models\SpareInspection;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\PermissionHelper as P;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\mechanical\models\search\SpareInspectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title=Yii::t('app', 'Machine control');
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="spare-inspection-index">
    <?php if (P::can('spare-inspection/unexpected/create')): ?>
        <p class="pull-right no-print">
            <?= Html::a('<span class="fa fa-plus"></span>', ['create', 'slug'=>$this->context->slug], ['class'=>'btn btn-sm btn-success']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target'=>'_black', 'class'=>'btn btn-sm btn-primary']) ?>
        </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider'=>$dataProvider,
        'filterRowOptions'=>['class'=>'filters no-print'],
        'filterModel'=>$searchModel,
        'columns'=>[
            ['class'=>'yii\grid\SerialColumn'],
            [
                'attribute'=>'sirhe_id',
                'value'=>function ($model) {
                    return (!empty($model->sirhe_id)) ? $model->sirhe->spareItem->name . " (<code>" . $model->sirhe->inv_number . "</code>)" : "";
                },
                'format'=>'raw',
                'filter'=>\kartik\select2\Select2::widget([
                    'data'=>\app\modules\mechanical\models\SpareItemRelHrEmployee::getSpareListMap(),
                    'model'=>$searchModel,
                    'attribute'=>'sirhe_id',
                    'language'=>'ru',
                    'options'=>[
                        'prompt'=>'',
                    ],
                    'pluginOptions'=>[
                        'allowClear'=>true
                    ],
                ])
            ],
            [
                'attribute'=>'status',
                'value'=>function ($model) {
                    return (SpareInspection::getStatusList($model->status)) ? SpareInspection::getStatusList($model->status) : $model->status;
                },
                'filter'=>SpareInspection::getStatusList()
            ],
            'reg_date',
            [
                'attribute'=>'created_by',
                'value'=>function ($model) {
                    return (\app\models\Users::findOne($model->created_by)) ? \app\models\Users::findOne($model->created_by)->user_fio : $model->created_by;
                },
            ],
            [
                'class'=>'yii\grid\ActionColumn',
                'template'=>'{update}{view}{delete}',
                'contentOptions'=>['class'=>'no-print', 'style'=>'width:100px;'],
                'visibleButtons'=>[
                    'view'=>P::can('spare-inspection/unexpected/view'),
                    'update'=>function ($model) {
                        return P::can('spare-inspection/unexpected/update') && $model->status !== $model::STATUS_ENDED;
                    },
                    'delete'=>function ($model) {
                        return P::can('spare-inspection/unexpected/delete') && $model->status !== $model::STATUS_ENDED;
                    }
                ],
                'buttons'=>[
                    'update'=>function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title'=>Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                    'view'=>function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title'=>Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                    'delete'=>function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title'=>Yii::t('app', 'Delete'),
                            'class'=>"btn btn-xs btn-danger",
                            'data'=>[
                                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method'=>'post',
                            ],
                        ]);
                    },

                ],
                'urlCreator'=>function ($action, $model, $key, $index) {
                    $slug=Yii::$app->request->get('slug');
                    if ($action === 'update') {
                        $url=Url::to(["update", 'id'=>$model->id, 'slug'=>$slug]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url=Url::to(["view", 'id'=>$model->id, 'slug'=>$slug]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url=Url::to(["delete", 'id'=>$model->id, 'slug'=>$slug]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>


</div>
