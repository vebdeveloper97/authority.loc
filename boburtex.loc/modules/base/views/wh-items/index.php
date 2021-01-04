<?php

    use kartik\select2\Select2;
    use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\WhItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Wh Items');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row no-print" style="padding-left: 20px;">
    <form action="<?=\yii\helpers\Url::current()?>" method="GET">
        <div class="">
            <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
            <div class="input-group" style="width: 100px">
                <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=($_GET['per-page'])?$_GET['per-page']:20?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                </span>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
    </form>
</div>
<div class="wh-items-index">
    <?php if (Yii::$app->user->can('wh-items/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?per-page='.$_GET['per-page']], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'options' => ['style' => 'font-size:11px;'],
        'rowOptions' => function($model){
            if($model->status == $model::STATUS_INACTIVE)
                return [
                    'class' => 'danger'
                ];
        },
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'name',
            [
                'attribute' => 'type_id',
                'value' => function($model) {
                    return $model->type->name;
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'type_id',
                    'data' => \app\modules\base\models\WhItemTypes::getList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Select'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'category_id',
                'value' => function($model) {
                    return $model->category->name;
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'category_id',
                    'data' => \app\modules\base\models\WhItemCategory::getList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Select'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'unit_id',
                'value' => function($model) {
                    return $model->unit->name;
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'unit_id',
                    'data' => \app\modules\base\models\Unit::getList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Select'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            //'barcode',
            [
                'attribute' => 'country_id',
                'value' => function($model) {
                    return $model->country->name;
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'country_id',
                    'data' => \app\modules\base\models\WhItemCountry::getList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Select'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model::getStatusList($model->status);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_by',
                'contentOptions' => ['style' => 'width:10%;'],
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))
                        ?\app\models\Users::findOne($model->created_by)->user_fio
                            . "<br><small><i>" .date('d.m.Y H:i',$model->created_at) . "</i></small>"
                        :$model->created_by;
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'created_by',
                    'data' => \app\models\Users::getUserList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Select'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('wh-items/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('wh-items/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('wh-items/delete') && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success mr1"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-default mr1"
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
$css = <<< Css
.select2-container--krajee strong.select2-results__group{
display:none;
}

.select2-container--krajee ul.select2-results__options>li.select2-results__option[aria-selected] {
font-size: 10px;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
right: 5px;
opacity: 0.5;
z-index: 999;
font-size: 18px;
top: -6px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
top: 60%;
}
Css;
$this->registerCss($css);
?>