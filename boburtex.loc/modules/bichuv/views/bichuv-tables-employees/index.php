<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvTablesEmployeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bichuv Tables Employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-tables-employees-index">
    <?php if (P::can('bichuv-tables-employees/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'bichuv-tables-employees_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'rowOptions' => [
                'class' => 'success'
                ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',

//            'hr_employee_id',
            [
                'attribute' => 'hr_employee_id',
                'value' => 'hrEmployee.fish',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'hr_employee_id',
                    'data' => \app\modules\hr\models\HrEmployee::getListMap(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options'=> [
                        'prompt'=>Yii::t('app','Select...'),
                    ],
                ])
            ],
            [
                'attribute' => 'bichuv_table_id',
                'value' => function($model){
                    return \app\modules\bichuv\models\BichuvTablesEmployees::getTableListByEmployee($model['hr_employee_id']);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'bichuv_table_id',
                    'data' => $searchModel->getTableList(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],

                    'options'=> [
                        'prompt'=>Yii::t('app','Select...'),
                    ],
                ]),
                'format' => 'raw'
            ],
            'from_date',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (\app\modules\bichuv\models\BaseModel::getStatusList($model->status))?\app\modules\bichuv\models\BaseModel::getStatusList($model->status):$model->status;
                },
                'filter' => \app\modules\bichuv\models\BaseModel::getStatusList()
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('bichuv-tables-employees/view'),
                    'update' => function($model) {
                        return P::can('bichuv-tables-employees/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return P::can('bichuv-tables-employees/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }
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
    'model' => 'bichuv-tables-employees',
    'crud_name' => 'bichuv-tables-employees',
    'modal_id' => 'bichuv-tables-employees-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Bichuv Tables Employees') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-tables-employees_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham yo\'q qilmoqchimisiz?')
]); ?>
