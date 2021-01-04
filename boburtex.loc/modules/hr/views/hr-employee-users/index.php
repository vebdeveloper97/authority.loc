<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrEmployeeUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employee Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-employee-users-index">
    <?php if (Yii::$app->user->can('hr-employee-users/create')): ?>
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
                'attribute' => 'users_id',
                'label' => Yii::t('app', 'Users'),
                'value' => function($data){
                    $sql = '';
                    $model = "SELECT * FROM users WHERE id IN (SELECT users_id FROM hr_employee_users WHERE hr_employee_id = $data->hr_employee_id)";
                    $query = Yii::$app->db->createCommand($model);
                    $result = $query->queryAll();
                    foreach ($result as $r){
                        $sql .= "<span class='badge badge-warning' style='background: darkgreen'>".$r['username']."</span>";
                    }
                    return $sql;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'hr_employee_id',
                'label' => Yii::t('app', 'Employee'),
                'value' => 'hrEmployee.fish',
            ],
            //'updated_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('hr-employee-users/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('hr-employee-users/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('hr-employee-users/delete') && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        $href = \yii\helpers\Url::to(['update', 'id' => $model->hr_employee_id]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $href, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        $href = \yii\helpers\Url::to(['delete', 'id' => $model->hr_employee_id]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $href, [
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
