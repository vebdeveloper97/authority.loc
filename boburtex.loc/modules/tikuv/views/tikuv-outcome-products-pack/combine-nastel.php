<?php

use app\modules\tikuv\models\DocSearch;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\DocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Nastelni birlashtirish');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->can('tikuv-outcome-products-pack/create-combine-nastel')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
<?php endif; ?>
<div class="toquv-documents-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            $rm = $model->is_change_model;
            if($rm == 1){
                return ['class' => 'danger'];
            }else{
                return ['class' => 'success'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'doc_number_and_date',
                'label' => Yii::t('app','Hujjat raqami va sanasi'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'nastel_no',
                'label' => Yii::t('app','Nastel No'),
                'value' => function($model){
                    return $model->getNastelParty('slice');
                }
            ],
            [
                'attribute' => 'model_and_variation',
                'label' => Yii::t('app','Model va ranglari'),
                'value' =>  function($model){
                    $modelData = $model->getModelListPartInfo();
                    return "<p class='text-bold'>".$modelData['model']."</p>".$modelData['part'];
                },
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['style' => 'white-space: normal;width:20%'],
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"Ish soni (dona)"),
                'value' => function($model){
                    return $model->getWorkCount();
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('tikuv-outcome-products-pack/view-combine-nastel'),
                ],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url = Url::to(['view-combine-nastel', 'id'=> $model->id]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
