<?php

use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrHiringEmployees;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrHiringEmployeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hiring employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-hiring-employees-index">
    <?php if (Yii::$app->user->can('hr-hiring-employees/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'employee_id',
                'value' => function ($model) {
                    $employeeInfo = HrHiringEmployees::getEmployeeInfo($model->id);
                    return $employeeInfo[0]['fish'] ?? '' ;
                }
            ],
            [
                'attribute' => 'staff_id',
                'value' => function ($model) {
                    return $model->getStaffInfoById($model->staff_id)['staff_info'] ?? '';
                }
            ],
            [
                'attribute' => 'reg_date',
                'value' => function ($model) {
                    return date('m.d.Y', strtotime($model->reg_date));
                }
            ],
            [
                'attribute' => 'end_date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->end_date, 'php: d.m.Y');
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel($model->status);
                }
            ],
            //'status',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('hr-hiring-employees/view'),
                    'update' => function($model) {
                        return false; //Yii::$app->user->can('hr-hiring-employees/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return false; //Yii::$app->user->can('hr-hiring-employees/delete') && $model->status !== $model::STATUS_SAVED;
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
            ],
        ],
    ]); ?>


</div>
