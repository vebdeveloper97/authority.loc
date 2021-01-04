<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

    /* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\WhDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Wh Documents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-document-index">

    <p class="pull-right no-print">
        <?php if (Yii::$app->user->can('wh-document/sell/create')): ?>
            <?= Html::a('<span class="fa fa-plus"></span>', ['wh-document/sell/create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?php endif; ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>

    <?php //echo $this->render('/wh-document/_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app','Hujjat'),
                'value' => function($model){
                    return '<b>'.$model->doc_number.'</b><br>'
                        .'<small><i>'.date('d.m.Y', strtotime($model->reg_date)).'</i></small>';
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
                'attribute' => 'musteri_id',
                'label' => Yii::t('app','Kimga'),
                'value' => function($model){
                    return "<b>".$model->musteri->name . "</b><br><small><i>" . $model->musteri_responsible . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getMusteries()
            ],
            [
                'attribute' => 'add_info',
                'label' => Yii::t('app','Add Info'),
            ],
            [
                'attribute' => 'created_by',
                'contentOptions' => ['style' => 'width:10%;'],
                'value' => function($model){
                    return $model->author->user_fio
                        ."<br><small><i>" .
                        date('d.m.Y H:i',$model->created_at) .
                        "</i></small>";
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'created_by',
                    'data' => \app\modules\base\models\WhDocument::getAuthorList(),
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('wh-document/sell/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('wh-document/sell/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('wh-document/sell/delete') && $model->status !== $model::STATUS_SAVED;
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
                            'class'=>"btn btn-xs btn-default"
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
                    if ($action === 'return') {
                        $url = Url::to(["return",'id' => $model->id,'slug' => $model::DOC_TYPE_RETURN_LABEL]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>


</div>
