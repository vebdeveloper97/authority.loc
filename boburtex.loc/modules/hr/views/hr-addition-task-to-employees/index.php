<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrAdditionTaskToEmployeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Assigned tasks');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="hr-addition-task-to-employees-index">
    <?php if (Yii::$app->user->can('hr-addition-task-to-employees/create')): ?>
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
                    'attribute' => 'hr_employee_id',
                    'value' => 'hrEmployee.fish'
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \app\modules\hr\models\HrAdditionTaskToEmployees::getStatusList($model->status);
                },
                'filter' => \app\modules\hr\models\HrAdditionTaskToEmployees::getStatusList()
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{history}{delete}{create}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('hr-addition-task-to-employees/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('hr-addition-task-to-employees/update');
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('hr-addition-task-to-employees/delete');
                    },
                     'create' => function($model) {
                        return Yii::$app->user->can('hr-addition-task-to-employees/create');
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
                            'class'=>"btn btn-xs btn-primary view-dialog"
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
                    'create' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url."&hr_employee_id=".$model->hr_employee_id, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-warning"
                        ]);
                    },
                    'history' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary history"
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<?php Modal::begin([
    'id' => 'modal-history',
    'size' => 'modal-md',
]); ?>
    <div id="modal-content"></div>
<?php Modal::end();?>
<?php
$js = <<< JS
    $('.history').on('click',function(e) {
        e.preventDefault();
      $('#modal-history').modal('show').find('#modal-content').load($(this).attr('href'));
    });
JS;
$this->registerJs($js,yii\web\View::POS_READY);
?>