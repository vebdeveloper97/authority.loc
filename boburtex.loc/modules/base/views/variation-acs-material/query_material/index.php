<?php

use app\modules\wms\models\WmsDocumentRel;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\wms\models\WmsDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Documents') . '(' . Yii::t('app', "Query (Material)") . ')';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wms-document-index">
    <?php if (Yii::$app->user->can("variation-acs-material/{$this->context->slug}/create")): ?>
        <p class="pull-right no-print">
            <?= Html::a('<span class="fa fa-plus"></span>', ['create', 'slug' => $this->context->slug], ['class' => 'btn btn-sm btn-success']) ?>
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

            'doc_number',
            [
                'attribute' => 'to_department',
                'value' => function ($model) {
                    return $model->toDepartment->name
                        . '<br/>'
                        . '<i style="font-size: 11px;">'
                        . $model->toEmployee->fish
                        .'</i>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'wms_document_rel_status',
                'value' => function ($model) {
                    return WmsDocumentRel::getStatusHtmlLabel($model->wmsDocumentRelParent->status);
                },
                'format' => 'html',
                'filter' => WmsDocumentRel::getStatusList(),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can("variation-acs-material/{$this->context->slug}/view"),
                    'update' => function($model) {
                        return Yii::$app->user->can("variation-acs-material/{$this->context->slug}/update") && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can("variation-acs-material/{$this->context->slug}/delete") && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger",
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },

                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $slug = Yii::$app->request->get('slug');
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'slug' => $slug]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model->id,'slug' => $slug]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>


</div>
