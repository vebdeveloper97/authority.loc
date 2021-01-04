<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\TayyorlovSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Preparation') . ' (' . Yii::t('app', 'Accept slice') .')' ;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tayyorlov-index">
    <?php if (Yii::$app->user->can('tayyorlov/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'tayyorlov_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->is_returned == 1){
                return ['class' => 'info'];
            }else{
                if($model->status == 1){
                    return ['class' => 'danger'];
                }else{
                    return ['class' => 'success'];
                }
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'doc_number_and_date',
                'label' => Yii::t('app','Document number and date'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
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
                    $modelData = $model->getModelListInfoOld();
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
                'template' => '{update}{view-get}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view-get' => Yii::$app->user->can('tayyorlov/qabul_kesim/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('tayyorlov/qabul_kesim/update') && $model->status != $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return false;//Yii::$app->user->can('tayyorlov/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Url::to(['update','slug'=>$this->context->slug, 'id' => $model->id]),
                            [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'btn btn-xs btn-success mr1',
                            'data-form-id' => $model->id,
                            'data-pjax' => 0
                        ]);
                    },
                    'view-ajax' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Url::to(['view','slug'=>$this->context->slug, 'id' => $model->id]),
                            [
                                'title' => "Modal oynada ko'rish",//Yii::t('app', 'View'),
                                'class'=> 'btn btn-xs btn-default view-dialog mr1',
                            ]
                        );
                    },
                    'view-get' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            Url::to(['view','slug'=>$this->context->slug, 'id' => $model->id]),
                            [
                                'title' => Yii::t('app', 'View'),
                                'class'=> 'btn btn-xs btn-default mr1',
                                'data-pjax' => "0",
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'bichuv-doc',
    'crud_name' => 'tayyorlov',
    'modal_id' => 'tayyorlov-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Tayyorlov') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'tayyorlov_pjax',
    'pretty_url' => true,
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
