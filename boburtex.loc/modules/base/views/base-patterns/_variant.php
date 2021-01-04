<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatterns */
/* @var $searchModel \app\modules\base\models\BasePatternsSearch */
/* @var $modelItems app\modules\base\models\BasePatternItems */
$dataProvider = $searchModel->searchItems(Yii::$app->request->queryParams, $id, $var_id);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterRowOptions' => ['class' => 'filters no-print'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'base_pattern_part_id',
            'value' => function ($model) {
                return $model->basePatternPart->name;
            }
        ],
        [
            'attribute' => 'base_pattern_id',
            'value' => function ($model) {
                return $model->basePattern->name;
            }
        ],
        [
            'attribute' => 'base_detail_list_id',
            'value' => function ($model) {
                return $model->baseDetailList->name;
            }
        ],
        [
            'attribute' => 'bichuv_detail_type_id',
            'label' => Yii::t('app', 'Detal Guruhi'),
            'value' => function ($model) {
                return $model->bichuvDetailType->name;
            }
        ],
        [
            'attribute' => 'base_patterns_variant_id',
            'label' => Yii::t('app', 'Base Patterns Variant Number'),
            'value' => function ($model) {
                $variantNumber = \app\modules\base\models\BasePatternsVariations::findOne($model->base_patterns_variant_id);
                return $variantNumber->variant_no;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{view}{delete}',
            'contentOptions' => ['class' => 'no-print', 'style' => 'width:100px;'],
            'visibleButtons' => [
                'view' => Yii::$app->user->can('base-pattern-items/view'),
                'update' => function ($model) {
                    return Yii::$app->user->can('base-pattern-items/update') && $model->basePattern->status !== $model::STATUS_SAVED;
                },
                'delete' => function ($model) {
                    return Yii::$app->user->can('base-pattern-items/delete') && $model->basePattern->status !== $model::STATUS_SAVED;
                }
            ],
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'title' => Yii::t('app', 'Update'),
                        'class' => 'update-dialog btn btn-xs btn-success',
                        'data-form-id' => $model->id,
                    ]);
                },
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                        'title' => Yii::t('app', 'View'),
                        'class' => 'btn btn-xs btn-primary view-dialog',
                        'data-form-id' => $model->id,
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('app', 'Delete'),
                        'class' => 'btn btn-xs btn-danger delete-dialog',
                        'data-form-id' => $model->id,
                    ]);
                },

            ],
        ],
    ],
]);