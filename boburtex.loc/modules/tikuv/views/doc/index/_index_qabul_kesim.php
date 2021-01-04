<?php

use app\modules\bichuv\models\BichuvDocSearch;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\DocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">
    <?php if(Yii::$app->user->can('doc/kochirish_mato/create')):?>
        <p class="pull-right">
        </p>
    <?php endif;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model['status'] < 3){
                return ['class' => 'danger'];
            }else{
                return ['class' => 'success'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'doc_number_and_date',
                'label' => Yii::t('app','Hujjat raqami va sanasi'),
                'value' => function($model){
                    return '<b>№ '.$model['doc_number'].'</b><br><small><i>'.$model['reg_data'].'</i></small>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'nastel_no',
                'label' => Yii::t('app','Nastel No'),
                'value' => function($model){
                    return $model['nastel_no'];
                }
            ],
            [
                'attribute' => 'doc_number2',
                'label' => Yii::t('app','Buyurtma raqami'),
                'value' => function($model){
                    return $model['doc_number2'];
                }
            ],
            [
                'attribute' => 'doc_number1',
                'label' => Yii::t('app',"O'zgargan buyurtma raqami"),
                'value' => function($model){
                    return $model['doc_number1'];
                }
            ],
            [
                'attribute' => 'article_old',
                'label' => Yii::t('app','Model'),
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                /*'headerOptions' => ['style' => 'white-space: normal;width:20%'],*/
            ],
            [
                'attribute' => 'article_new',
                'label' => Yii::t('app',"O'zgargan model"),
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                /*'headerOptions' => ['style' => 'white-space: normal;width:20%'],*/
            ],
            [
                'attribute' => 'color_old',
                'label' => Yii::t('app','Rangi'),
                'format' => 'raw',
                'options' => ['class' => 'text-center'],
                /*'headerOptions' => ['style' => 'white-space: normal;width:20%'],*/
            ],
            [
                'attribute' => 'color_new',
                'label' => Yii::t('app',"O'zgargan rangi"),
                'format' => 'raw',
                'options' => ['class' => 'text-center'],
                /*'headerOptions' => ['style' => 'white-space: normal;width:20%'],*/
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"Ish soni (dona)"),
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('doc/qabul_kesim/view'),
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
                        $url = Url::to(["view",'id'=> $model['id'],'slug' => $slug]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
