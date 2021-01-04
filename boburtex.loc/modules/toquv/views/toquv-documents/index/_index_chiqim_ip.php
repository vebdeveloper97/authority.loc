<?php

use app\modules\toquv\models\ToquvDocumentsSearch;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;
use app\modules\toquv\models\ToquvDocuments;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$isOwn = Yii::$app->request->get('t',1);
$t = $searchModel->getIsOwnLabel($isOwn);

$this->title = Yii::t('app', 'Toquv Documents {type}', ['type' => ToquvDocuments::getDocTypeBySlug($this->context->slug)])."({$t})";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">


    <?php if(Yii::$app->user->can('toquv-documents/chiqim_ip/create')):?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', ["create",'slug' => $this->context->slug,'t' => $isOwn], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'number_and_date',
                'label' => Yii::t('app','Hujjat raqami va sanasi'),
                'value' => function($model){
                    $doc = Yii::t('app','№{number} - {date}', ['number' => $model->doc_number,'date' => date('d.m.Y', strtotime($model->reg_date))]);
                    $status = '<span class="fa fa-check text-success"></span>';
                    return $status.'&nbsp;&nbsp;'.$doc;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'from_department',
                'label' => Yii::t('app','Qayerdan'),
                'value' => function($model){
                    return $model->fromDepartment->name;
                },
                'filter' => $searchModel->getDepartments()
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return ($model->to_department != NULl)?$model->toDepartment->name:$model->musteri->name;
                },
                'filter' => $searchModel->getDepartments()
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('toquv-documents/chiqim_ip/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('toquv-documents/chiqim_ip/update') && $model->status !== $model::STATUS_SAVED && $model->to_department != NULL;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('toquv-documents/chiqim_ip/delete') && $model->status !== $model::STATUS_SAVED && $model->to_department != NULL;
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
                    $isOwn = Yii::$app->request->get('t',1);
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug,'t' => $isOwn]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'slug' => $slug,'t' => $isOwn]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model->id,'slug' => $slug,'t' => $isOwn]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
