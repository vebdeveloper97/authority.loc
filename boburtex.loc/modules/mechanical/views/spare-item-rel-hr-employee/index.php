<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\mechanical\models\search\SpareItemRelHrEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mashine liability');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-rel-hr-employee-index">
    <?php if (P::can('spare-item-rel-hr-employee/create')): ?>
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
                    'attribute' => 'spare_item_id',
                    'value' => 'spareItem.name'
            ],
            'inv_number',
            [
                'attribute' => 'hr_department_id',
                'value' => 'hrDepartment.name'
            ],
            'add_info',
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:10%'],
                'value' => function($model){
                    return (!empty($model->status)) ? $model->getStatusList($model->status) : '';
                },
                'filter' => $searchModel::getStatusList(),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{copy} {update} {view} {delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('spare-item-rel-hr-employee/view'),
                    'update' => function($model) {
                        return P::can('spare-item-rel-hr-employee/update') && $model->status !== $model::STATUS_ENDED;
                    },
                    'delete' => function($model) {
                        return P::can('spare-item-rel-hr-employee/delete') && $model->status !== $model::STATUS_ENDED;
                    }
                ],
                'buttons' => [
                    'copy' => function ($url, $model) {
                        return Html::a('<i class="fa fa-files-o"></i>', $url, [
                            'title' => Yii::t('app', 'Copy'),
                            'class' => "btn btn-xs btn-info copy-dialog",
                            'data-form-id' => $model->id,
                        ]);
                    },
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

<?php Modal::begin([
        'id' => 'copy-modal',
        'size' => Modal::SIZE_LARGE,
])?>
    <div id="modal-content"></div>
<?php Modal::end()?>
<?php
$js = <<< JS
    $('.copy-dialog').on('click',function(e) {
      e.preventDefault();
      $('#copy-modal').modal('show');
      $('#copy-modal').find('#modal-content').load($(this).attr('href'));
    });
JS;
$this->registerJs($js,yii\web\View::POS_READY);
?>