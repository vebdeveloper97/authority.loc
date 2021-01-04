<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 29.05.20 16:48
 */

/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 15.05.20 18:47
 */



/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvOutcomeProductsPackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tikuv Outcome Products Packs');
$this->params['breadcrumbs'][] = $this->title;

use yii\grid\GridView;
use yii\helpers\Html; ?>
<div class="tikuv-outcome-products-pack-index">
    <p class="pull-right no-print">
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'musteri_id',
                'value' => function($model) {
                    return "<b>" . $model->musteri->name . "</b>";
                },
                'format' => 'raw',
                'filter' => false,
            ],
            [
                'attribute' => 'from_musteri',
                'value' => function($model){
                    return $model->fromMusteri->name;
                }
            ],
            'nastel_no',
            [
                'attribute' => 'model_and_variations',
                'label' => Yii::t('app','Model va ranglari'),
                'value' =>  function($model){
                    $modelData = $model->getModelListInfo();
                    return "<p class='text-bold'>".$modelData['model']."</p>".$modelData['model_var_code'];
                },
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['style' => 'white-space: normal;width:20%'],
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"O'lcham / Soni"),
                'value' => function($model){
                    $result = $model->getWorkCount();
                    return "<div class='text-center'>{$result['size']}</div><div class='text-center'><b>{$result['count']}</b></div>";
                },
                'format' => 'raw'
            ],
            'add_info:ntext',
            [
                'attribute' => 'created_by',
                'value' => function($model) {
                    return $model->user->user_fio . "<br><small><i>" . date('d.m.y H:i', $model->updated_at) . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getUsers(),
                'contentOptions' => [
                    'style' => 'width:8%',
                ]
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $class = $model->status == 3 ? 'btn btn-xs btn-default' : 'btn btn-xs btn-success';
                    return Html::button($model->status == 3 ? Yii::t('app', 'Qabul qilingan') : Yii::t('app', 'Qabul qilinmagan'), ['class' => $class]);
                },
                'format' => 'raw',
                'filter' => [1=>Yii::t('app', 'Qabul qilinmagan'), 4=>Yii::t('app', 'Qabul qilingan')],
                'contentOptions' => [
                    'style' => 'width:8%',
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{usluga-view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('tikuv-outcome-products-pack/view'),
                ],
                'buttons' => [
                    'usluga-view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary mr1"
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
