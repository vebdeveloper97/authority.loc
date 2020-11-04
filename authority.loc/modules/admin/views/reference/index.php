<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'References');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reference-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['class'=>'grid-view','id'=>'grid_id_1'],
        'summaryOptions'=>['style'=>'text-align:right;'],
        'summary' => Yii::t('app', 'Showing <strong>{begin}-{end}</strong> of <strong>{totalCount}</strong> items'),
        'export' => false,
        'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fullname',
            'address',
            'phone',
            'reference_message:ntext',
            [
                'attribute' => 'status',
                'class' => '\kartik\grid\EditableColumn',
                'editableOptions' => function($model,$key,$index){
                    return [
                        'value' => $model->status,
                        'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                        'formOptions'   => [
                            'action'    => [
                                'reference/changes-status'
                            ],
                        ],
                        'data' => [
                            3 => Yii::t('app', 'Complete'),
                            2 => Yii::t('app', 'Continued'),
                            1 => Yii::t('app', 'Active'),
                        ],
                        'displayValueConfig'=> [
                            '1' => '<i class="fa fa-spinner fa-spin text-warning"></i><span> '.Yii::t('app', 'Active').'</span>',
                            '2' => '<i class="fa fa-question text-danger"></i><span> '.Yii::t('app', 'Continued').'</span>',
                            '3' => '<i class="glyphicon glyphicon-ok text-success"></i><span> '.Yii::t('app', 'Complate').'</span>',
                        ],
                    ];
                },

                'format' => 'html',
                'headerOptions' => ['width' => '150'],
                'filterType' => GridView::FILTER_SELECT2,
            ],
            'date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                ],

            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>

