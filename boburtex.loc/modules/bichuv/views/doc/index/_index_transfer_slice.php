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
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
$t = Yii::$app->request->get('t',1);
?>
<div class="toquv-documents-index">
    <?php if(Yii::$app->user->can('doc/transfer_slice/create')):?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', Url::to(['create', 'slug' => $this->context->slug,'t' => $t]), ['class' => 'btn btn-success']) ?>
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
                'headerOptions' => ['style' => 'white-space: normal;width:15%'],
            ],
            [
                'attribute' => 'to_hr_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    if($model->is_service){
                        return Yii::t('app','Xizmat uchun');
                    }
                    return "<b>".$model->toHrDepartment->name ."</b><br><small><i>". $model->toHrEmployee->fish . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getDepartments(true),
            ],
            [
                'attribute' => 'model_and_variation',
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
                        $result = $model->getWorkCount('slice',true);
                        return "<div><div class='text-center'><small>{$result['size']}</small></div><div class='text-center'><b>{$result['count']}</b></div></div>";
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
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('doc/transfer_slice/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('doc/transfer_slice/update') && $model->status !== $model::STATUS_SAVED && $model->type !== 4;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('doc/transfer_slice/delete') && $model->status !== $model::STATUS_SAVED && $model->type !== 4;
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
                        $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug, 't' => $model->type]);
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
                }
            ],
        ],
    ]); ?>
</div>
