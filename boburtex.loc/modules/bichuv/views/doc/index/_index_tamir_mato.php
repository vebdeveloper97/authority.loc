<?php

use app\modules\bichuv\models\BichuvDocSearch;
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
?>
<div class="toquv-documents-index">


    <?php if(Yii::$app->user->can('doc/kochirish_mato/create')):?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', Url::to(['create', 'slug' => $this->context->slug]), ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app','Hujjat'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'from_department',
                'label' => Yii::t('app','Qayerdan'),
                'value' => function($model){
                    return "<b>".$model->fromDepartment->name ."</b><br><small><i>". $model->fromEmployee->user_fio . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getDepartments()
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return "<b>".$model->toDepartment->name ."</b><br><small><i>". $model->toEmployee->user_fio . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getDepartments(true),
            ],
            [
                'attribute' => 'party',
                'label' => Yii::t('app','Partiya No'),
                'value' => function($model){
                    return $model->bichuvDocItems[0]->party_no;
                }
            ],
            [
                'attribute' => 'musteri_party',
                'label' => Yii::t('app','Musteri Partiya No'),
                'value' => function($model){
                    return $model->bichuvDocItems[0]->musteri_party_no;
                }
            ],
            [
                'attribute' => 'toquv_doc_id',
                'label' => Yii::t('app','Status'),
                'value' => function($model){
                    if(!empty($model->toquvDoc)){
                        if($model->toquvDoc->status == 3){
                            return '<button class="btn btn-success">Qabul qilindi</button>';
                        }else{
                            return '<button class="btn btn-warning">Jo\'natildi</button>';
                        }
                    }else{
                        return null;
                    }
                },
                'filter' => [
                        '' => Yii::t('app','Barchasi'),
                        1 => Yii::t('app',"Jo'natilgan"),
                        3 => Yii::t('app',"Qabul qilingan")
                        ],
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('doc/kochirish_mato/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('doc/kochirish_mato/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('doc/kochirish_mato/delete') && $model->status !== $model::STATUS_SAVED;
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
