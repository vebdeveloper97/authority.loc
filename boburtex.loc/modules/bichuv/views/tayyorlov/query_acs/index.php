<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Preparation') . ' (' . Yii::t('app', 'Query accessory') . ')';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->status == 1){
                return ['class' => 'danger'];
            }else{
                return ['class' => 'success'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app','Hujjat'),
                'value' => function($model){
                    $name = Yii::t('app','№{number}<br>{date}', ['number' => $model->doc_number,'date' => date('d.m.Y H:i', strtotime($model->reg_date))]);
                    $slug = Yii::$app->request->get('slug');
                    $url = Url::to(["view",'id'=> $model->id, 'slug' => $slug]);
                    return Html::a($name,$url);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'to_hr_department',
                'label' => Yii::t('app','To department'),
                'value' => function($model){
                    return $model->toHrDepartment->name;
                },
                'filter' => $searchModel->getDepartments(true)
            ],
            /*[
                'attribute' => 'model_id',
                'label' => Yii::t('app','Model'),
                'value' => function($model){
                    return $model->getProductModelList('item');
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
                'contentOptions' => ['style' => 'white-space: normal;width:20%;'],
                'headerOptions' => ['style' => 'width:20%;']
            ],*/
            [
                'attribute' => 'nastel_party',
                'label' => Yii::t('app','Nastel No'),
                'value' => function($model){
                    return $model->getNastelParty('item');
                },
                'contentOptions' => ['style' => 'width:20%;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('tayyorlov/query_acs/view'),
                ],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },

                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $slug = Yii::$app->request->get('slug');
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'slug' => $slug]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
