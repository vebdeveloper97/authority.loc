<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 08.05.20 14:45
 */



/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use app\modules\bichuv\models\BichuvDoc;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
$slug = Yii::$app->request->get('slug',BichuvDoc::DOC_TYPE_ADJUSTMENT_SERVICE_LABEL);
$this->title = Yii::t('app', '{type}', ['type' => \app\modules\bichuv\models\BichuvDoc::getDocTypeBySlug($slug)]);
$this->params['breadcrumbs'][] = $this->title;
$t = Yii::$app->request->get('t',1);
?>
<div class="toquv-documents-index">
    <?php if(Yii::$app->user->can('doc/usluga/create')):?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', Url::to(['form-service', 'slug' => $slug,'t' => $t]), ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_number_and_date',
                'label' => Yii::t('app','Hujjat raqami va sanasi'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'service_musteri_id',
                'label' => Yii::t('app','Bajaruvchi'),
                'value' => function($model){
                    return $model->serviceMusteri->name;
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'white-space: normal;width:20%'],
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'service_musteri_id',
                    'data' => $searchModel->getMusteries(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'model_and_variation',
                'label' => Yii::t('app','Model va ranglari'),
                'value' =>  function($model){
                    $modelData = $model->getModelListInfo();
                    return "<p class='text-bold'>".$model->modelList->article." ".$model->modelList->name." </p>".\app\modules\base\models\ModelsVariations::getListVar($model['models_list_id'])[$model['model_var_id']];
                },
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['style' => 'white-space: normal;width:20%'],
            ],

            [
                'attribute' => 'nastel_party',
                'label' => Yii::t('app','Nastel No'),
                'value' => function($model){
                    if($model->type == 1 OR $model->type == 4){
                        return $model->getNastelParty('slice');
                    }elseif ($model->type == 2){
                        return $model->getNastelParty('item');
                    }else{
                        return $model->getNastelParty('item');
                    }
                }
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"Miqdori (kg/dona)"),
                'value' => function($model){
                    if($model->type == 1 OR $model->type == 4){
                        return $model->getWorkCount('slice');
                    }elseif($model->type == 2){
                        return $model->getWorkCount('item');
                    }else{
                        return $model->getWorkCount('rm');
                    }
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{form-service}{view-service}{delete-service}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view-service' => Yii::$app->user->can('doc/usluga/view'),
                    'form-service' => function($model) {
                        return Yii::$app->user->can('doc/usluga/update') && $model->status < $model::STATUS_SAVED;
                    },
                    'delete-service' => function($model) {
                        return Yii::$app->user->can('doc/usluga/delete') && $model->status < $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'form-service' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                    'view-service' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                    'delete-service' => function ($url, $model) {
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
                    if ($action === 'form-service') {
                        $url = Url::to(['form-service','id'=> $model->id, 'slug' => $slug, 't' => $model->type]);
                        return $url;
                    }
                    if ($action === 'view-service') {
                        $url = Url::to(['view-service','id'=> $model->id,'slug' => $slug, 't' => $model->type]);
                        return $url;
                    }
                    if ($action === 'delete-service') {
                        $url = Url::to(['delete-service','id' => $model->id,'slug' => $slug, 't' => $model->type]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>