<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\ToquvUserDepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv User Departments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-user-department-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="pull-right" style="margin-top: -30px;">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'username',
                'value' => function($model){
                    return  $model->user->username;
                }
            ],
            [
               'attribute' => 'department_id',
               'label' => Yii::t('app','Departments'),
               'value' => function($model){
                    return $model->getBelongToDepartments();
               },
               'format' => 'raw',
               'filter' => $searchModel->departments
            ],
            [
               'attribute' => 'department_id',
               'label' => Yii::t('app','Departments'),
               'value' => function($model){
                    return $model->getBelongToDepartments2();
               },
               'format' => 'raw',
               'filter' => $searchModel->departments
            ],
            [
               'attribute' => 'created_by',
               'value' => function($model){
                    return $model->getUserName();
               }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatusList($model->status);
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class' => "btn btn-xs btn-primary",
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'Delete'),
                                'class' => 'btn btn-xs btn-danger',
                                'data-confirm' => 'Are you sure you want to delete?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>

<?php
$css = <<<CSS
        span.each-department-tags {
            display:inline-block;
            font-size: 80%;
            background-color:#3c8dbc;
            margin: 1px 5px;
            border-radius: 3px;
            color:#fff;
            padding:3px;
        }
CSS;
$this->registerCss($css)
?>
</div>
