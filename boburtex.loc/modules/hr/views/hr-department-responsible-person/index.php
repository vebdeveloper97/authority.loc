<?php

use app\modules\hr\models\HrDepartmentResponsiblePerson;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrDepartmentResponsiblePersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Responsible persons (Department)');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-department-responsible-person-index">
    <?php if (Yii::$app->user->can('hr-department-responsible-person/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'hr-department-responsible-person_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' =>'hr_department_id',
                'value' => function($model) {
                    return $model->hrDepartment->name;
                },
                'filter' => \kartik\tree\TreeViewInput::widget([
                    'model' => $searchModel,
                    'attribute' => 'hr_department_id',
                    'query' => HrDepartments::find()->addOrderBy('root, lft'),
                    'headingOptions' => ['label' => Yii::t('app', "To department")],
                    'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                    'fontAwesome' => true,
                    'asDropdown' => true,
                    'multiple' => false,
                    'options' => ['disabled' => false],
                    'dropdownConfig' => [
                        'input' => [
                            'placeholder' => Yii::t('app', 'Select...')
                        ]
                    ]
                ]),
                'format' => 'html',
            ],
            [
                'attribute' =>'hr_employee_id',
                'value' => function($model) {
                    return $model->hrEmployee->fish;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'hr_employee_id',
                    'data' => HrEmployee::getListMap(),
                    'options' => [
                        'placeholder' => Yii::t('app', "Responsible person (Department)")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                    ]
                ]),
                'format' => 'html',
            ],
            [
                'attribute' => 'start_date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'start_date',
                    'options' => [
                        'autocomplete' => 'off',
                    ],
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'autoclose'=>true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]),
                'format' => 'html',
            ],
            [
                'attribute' => 'end_date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'end_date',
                    'options' => [
                        'autocomplete' => 'off',
                    ],
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'autoclose'=>true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]),
                'format' => 'html',
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusList($model->status);
                },
                'filter' => HrDepartmentResponsiblePerson::getStatusList()
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('hr-department-responsible-person/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('hr-department-responsible-person/update') && $model->status != $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('hr-department-responsible-person/delete') && $model->status != $model::STATUS_SAVED;
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
    'model' => 'hr-department-responsible-person',
    'crud_name' => 'hr-department-responsible-person',
    'modal_id' => 'hr-department-responsible-person-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Responsible persons (Department)') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'hr-department-responsible-person_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
