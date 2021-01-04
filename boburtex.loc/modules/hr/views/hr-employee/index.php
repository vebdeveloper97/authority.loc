<?php

use yii\helpers\Html;
use yii\grid\GridView;
use muhsamsul\treeimage\TreeImage;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrEmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employee');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-employee-index">
    <?php if (Yii::$app->user->can('hr-employee/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<span class="fa fa-file-excel-o"></span>', ['excel-create'], ['class' => 'btn btn-sm btn-success']) ?>
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
                'attribute' => 'path',
                'label' => Yii::t('app', 'Image'),
                'value' => function($model){
                    if(!empty($model->getEmployeeAvatar($model['id'])['path'])){
                        $path = $model->getEmployeeAvatar($model['id'])['path'];
                    }else{
                        $path = '/img/user.jfif';
                    }
                    $image = "<img alt='".''."' src='/web/".$path."' 
                    class='thumbnail imgPreview round' style='width:auto;border-radius:100px;height:80px;'> ";
                    return $image;
                },
                'format' => 'html'
            ],
            'fish',
            'address',
            'phone',
            [
                'attribute' => 'birth_date',
                'format' => ['date', 'php:d.m.Y'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('hr-employee/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('hr-employee/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('hr-employee/delete') && $model->status !== $model::STATUS_SAVED;
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
<?php
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>