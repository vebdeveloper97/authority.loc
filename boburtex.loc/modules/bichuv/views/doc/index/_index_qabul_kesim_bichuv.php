<?php

use app\modules\bichuv\models\BichuvDocSearch;
use app\widgets\helpers\Count;
use kartik\select2\Select2;
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;
use yii\web\View;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type} (ishlab chiqarishdan)', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kirim-mato-tab">
    <ul id="w0" class="nav nav-tabs">
        <li class="active"><a href="#w0-tab0" data-toggle="tab"><?php echo Yii::t('app', 'Bichuvdan')?></a></li>
    </ul>
</div>
<div class="toquv-documents-index">
    <?php if(P::can('doc/qabul_kesim/create')):?>
    <?php endif;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->status == $model::STATUS_ACTIVE){
                return ['class' => 'warning'];
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
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return $model->toHrDepartment->name;
                }
            ],
            [
                'attribute' => 'nastel_party',
                'label' => Yii::t('app','Nastel No'),
                'value' => function($model){
                    return $model->getNastelParty();
                }
            ],
            [
               'attribute' => 'model_and_variations',
               'label' => Yii::t('app','Model va ranglari'),
               'value' =>  function($model){
                   $modelData = $model->getModelListInfo();
                   return "<p class='text-bold'>".$modelData['model']."</p>".$modelData['model_var_code'];
               },
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['style' => 'white-space: normal;width:20%'],
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"O'lcham / Soni"),
                'value' => function($model){
                    $result = $model->getWorkCount('slice',true);
                    return "<div><div class='text-center'><small>{$result['size']}</small></div><div class='text-center'><b>{$result['count']}</b></div></div>";
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('doc/qabul_kesim_bichuv/view'),
                    'update' => function($model){
                        return $model->status == BichuvDoc::STATUS_ACTIVE && P::can('doc/qabul_kesim_bichuv/update');
                    } ,
                ],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $slug = Yii::$app->request->get('slug');
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'slug' => $slug]);
                        return $url;
                    }
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id,'slug' => $slug]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
