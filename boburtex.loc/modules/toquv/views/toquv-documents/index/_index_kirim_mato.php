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
    <?php Pjax::begin(['id' => 'toquv-documents_pjax']);  ?>
        <p class="pull-right no-print">
            <?php if(Yii::$app->user->can('toquv-documents/kirim_mato/create')):?>
                <?= Html::a('<span class="fa fa-plus"></span>', ["create",'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
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
                    'template' => '{view}{save-and-finish}',
                    'contentOptions' => ['style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' =>  Yii::$app->user->can('toquv-documents/kirim_mato/view'),
                        /*'update' => function($model) {
                            return Yii::$app->user->can('toquv-documents/kirim_mato/update') && $model->status < $model::STATUS_SAVED;
                        },*/
                        'save-and-finish' => function($model) {
                            return Yii::$app->user->can('toquv-documents/kirim_mato/update') && $model->status < $model::STATUS_SAVED;
                        },
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
                                'class'=> 'btn btn-xs btn-primary view-dialog',
                                'data-form-id' => $model->id,
                            ]);
                        },
                        'save-and-finish' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-save"></span>', $url, [
                                'title' => Yii::t('app', 'Save and finish'),
                                'class' => "btn btn-xs btn-success",
                                'target' => '_blank'
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
                        if ($action === 'save-and-finish') {
                            $url = Url::to(["save-and-finish",'id'=> $model->id, 'slug' => $slug]);
                            return $url;
                        }
                    }
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'toquv-documents',
    'crud_name' => 'toquv-documents/kirim_mato',
    'modal_id' => 'toquv-documents-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Batafsil') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'toquv-kalite_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?'),
    'pretty_url' => true
]);
$js = <<< JS
    $('body').delegate('.print-content','click',function () {
        printDivById('print-content');
    });
    function printDivById(content_id) {
        let new_content = document.getElementById(content_id).innerHTML;
        $('.wrapper').hide();
        $('body').append("<div id='new_content_print'>"+new_content+"</div>");
        window.print();
        $('#new_content_print').remove();
        $('.wrapper').show();
        return false;
    }
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
?>
