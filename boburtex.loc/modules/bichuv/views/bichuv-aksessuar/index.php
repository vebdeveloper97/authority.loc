<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvAksessuarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bichuv Aksessuars');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-aksessuar-index">
    <p class="pull-right no-print">
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php Pjax::begin(['id' => 'bichuv-aksessuar_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->checkAks() == true){
                return ['style' => "background:#B2FF59"];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'doc_number',
            'reg_date',
            [
                'attribute' => 'musteri_id',
                'label' => Yii::t('app', 'Model buyurtmachisi'),
                'value' => function($model){
                    return ($model->musteri)?$model->musteri->name:'';
                },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'musteri_id',
                    'data' => $searchModel->getMusteriList(),
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
                'attribute' => 'info',
                'label' => Yii::t('app', 'Buyurtma'),
                'value' => function($model){
                    return ($model->moi)?$model->moi->info:'';
                },
                'format' => 'raw',
                'filter' => false,
            ],
            'add_info:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('bichuv-aksessuar/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('bichuv-aksessuar/update') && $model->status == $model::STATUS_SAVED;
                    },
                    /*'delete' => function($model) {
                        return Yii::$app->user->can('bichuv-aksessuar/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }*/
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-default view-dialog mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    /*'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },*/
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'bichuv-mato-orders',
    'crud_name' => 'bichuv-aksessuar',
    'modal_id' => 'bichuv-aksessuar-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Bichuv Aksessuar') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'bichuv-aksessuar_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?'),
    'array_model' => ['BichuvDocResponsible']
]); ?>
