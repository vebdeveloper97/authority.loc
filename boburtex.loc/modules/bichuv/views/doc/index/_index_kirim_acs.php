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


    <?php if(Yii::$app->user->can('doc/kirim_acs/create')):?>
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
                'attribute' => 'musteri_id',
                'label' => Yii::t('app','Yetkazib beruvchi'),
                'value' => function($model){
                    return "<b>".$model->musteri->name . "</b><br><small><i>" . $model->musteri_responsible . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getMusteries()
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return "<b>".$model->toHrDepartment->name ."</b><br><small><i>". $model->toHrEmployee->fish . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getDepartments()
            ],
            [
                'attribute' => 'add_info',
                'label' => Yii::t('app','Add Info'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}{return}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('doc/kirim_acs/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('doc/kirim_acs/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('doc/kirim_acs/delete') && $model->status !== $model::STATUS_SAVED;
                    },
                    'return' => function($model) {
                        return Yii::$app->user->can('doc/kirim_acs/delete') && $model->status === $model::STATUS_SAVED;
                    }
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
                            'title' => Yii::t('app', 'Qaytarish'),
                            'class' => "btn btn-xs btn-default",
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
                    if ($action === 'return') {
                        $url = Url::to(["return",'id' => $model->id,'slug' => $model::DOC_TYPE_RETURN_LABEL]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
