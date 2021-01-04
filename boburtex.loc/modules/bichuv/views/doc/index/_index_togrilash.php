<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
$t = Yii::$app->request->get('t',3);
?>
<div class="toquv-documents-index">
    <?php if(Yii::$app->user->can('doc/kirim_mato/create')):?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', Url::to(['create', 'slug' => $this->context->slug, 't' => $t]), ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_number_and_date',
                'label' => Yii::t('app','Hujjat yoki sana'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'white-space: normal;max-width:100px;'],
            ],
            [
                'attribute' => 'musteri_id',
                'label' => Yii::t('app','Yetkazib beruvchi'),
                'value' => function($model){
                    return "<b>".$model->musteri->name . "</b><br><small><i>" . $model->musteri_responsible . "</i></small>";
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'musteri_id',
                    'data' => $searchModel->getMusteries(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'contentOptions' => ['style' => 'white-space: normal;max-width:200px;'],
            ],
            [
                'attribute' => 'model_id',
                'label' => Yii::t('app','Model'),
                'value' => function($model){
                    return $model->getProductModelList();
                },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'model_id',
                    'data' => $searchModel->getProductModels(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'contentOptions' => ['style' => 'white-space: normal;max-width:100px;'],
            ],
            [
                'attribute' => 'party',
                'label' => Yii::t('app','Partiya No'),
                'value' => function($model){
                    return $model->getParties();
                },
                'contentOptions' => ['style' => 'white-space: normal;max-width:150px;'],
            ],
            [
                'attribute' => 'musteri_party',
                'label' => Yii::t('app','Musteri Partiya No'),
                'value' => function($model){
                    return $model->getParties(true);
                },
                'contentOptions' => ['style' => 'white-space: normal;max-width:150px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('doc/kirim_mato/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('doc/kirim_mato/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('doc/kirim_mato/delete') && $model->status !== $model::STATUS_SAVED;
                    },
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success mr1"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary mr1"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger mr1",
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                    'return' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-backward"></span>', $url, [
                            'title' => Yii::t('app', 'Tamir'),
                            'class' => "btn btn-xs btn-danger",
                        ]);
                    },

                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $slug = Yii::$app->request->get('slug');
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug,'t' => $model->type]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'slug' => $slug, 't' => $model->type]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model->id,'slug' => $slug, 't' => $model->type]);
                        return $url;
                    }
                    if ($action === 'return') {
                        $url = Url::to(["#",'id' => $model->id, 'slug' => $slug, 't' => $model->type]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
