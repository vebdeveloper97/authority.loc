<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\toquv\models\ToquvDocuments;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Documents {type}', ['type' => ToquvDocuments::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">


    <?php if(Yii::$app->user->can('toquv-documents/create')):?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', ["create",'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
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
                    return Yii::t('app','№{number} - {date}', ['number' => $model->doc_number,'date' => date('d.m.Y H:i', strtotime($model->reg_date))]);
              }
            ],
            [
               'attribute' => 'musteri_id',
               'label' => Yii::t('app','Yetkazib beruvchi'),
               'value' => function($model){
                    return $model->musteri->name;
               },
               'filter' => $searchModel->getMusteries()
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return $model->toDepartment->name;
                },
                'filter' => $searchModel->getDepartments()
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('toquv-documents/view'),
                    'update' => function($model) {
                                return Yii::$app->user->can('toquv-documents/update') && $model->status !== $model::STATUS_SAVED;
                            },
                    'delete' => function($model) {
                                return Yii::$app->user->can('toquv-documents/delete') && $model->status !== $model::STATUS_SAVED;
                            },
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
