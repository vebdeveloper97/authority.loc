<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvMatoSkladSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Documents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-mato-sklad-index">
    <?php Pjax::begin(); ?>
        <p class="pull-right no-print">
            <?php if(Yii::$app->user->can('toquv-documents/kirim_aksessuar/create')):?>
                <?= Html::a('<span class="fa fa-plus"></span>', ["create",'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function($model){
                    switch ($model->status){
                        case 1:
                            return [
                                'style' => 'background:#aff29a'
                            ];
                            break;
                        case 2:
                            return [
                                'style' => 'background:#ff0a0a'
                            ];
                            break;
                    }
                },
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
                        return $model->toDepartment->name;
                    },
                    'filter' => $searchModel->getDepartments()
                ],
                'add_info',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'contentOptions' => ['style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' =>  Yii::$app->user->can('toquv-documents/kirim_aksessuar/view'),
                        /*'update' => function($model) {
                            return Yii::$app->user->can('toquv-documents/kirim_aksessuar/update') && $model->status < $model::STATUS_SAVED;
                        },*/
                    ],
                    'buttons' => [
                        /*'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class'=>"btn btn-xs btn-success"
                            ]);
                        },*/
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class'=>"btn btn-xs btn-primary"
                            ]);
                        },
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        $slug = Yii::$app->request->get('slug');
                        /*if ($action === 'update') {
                            $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug]);
                            return $url;
                        }*/
                        if ($action === 'view') {
                            $url = Url::to(["view",'id'=> $model->id, 'slug' => $slug]);
                            return $url;
                        }
                    }
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
