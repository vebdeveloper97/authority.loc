<?php

use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsListSearch;
use app\modules\toquv\models\ToquvMusteri;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelsListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Models Lists');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="models-list-index">
        <div class="row no-print" style="padding-left: 20px;">
            <form action="<?=\yii\helpers\Url::current()?>" method="GET">
                <div class="">
                    <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                    <div class="input-group" style="width: 100px">
                        <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=isset($_GET['per-page'])?$_GET['per-page']:20?>">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                    </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </form>
        </div>
        <?php Pjax::begin()?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['style' => 'font-size:11px;'],
            'rowOptions' => function($model){
                if($model->status == $model::STATUS_INACTIVE)
                    return [
                        'class' => 'danger'
                    ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
                    'value' => function($model){
                        $image = ($model->image)?"<img alt='".$model->article."' src='/web/".$model->image."' class='thumbnail imgPreview round' style='width:auto;border-radius:100px;height:80px;'> ":'';
                        return $image .$model->name;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'article',
                    'value' => function($model){
                        return "<code>". $model->article."</code>";
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'brend_id',
                    'contentOptions' => ['style' => 'width:10%;'],
                    'value' => function($model){
                        return $model->brend->name;
                    },
                    'filter' => Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'brend_id',
                        'data' => ModelsList::getAllBrend(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                [
                    'attribute' => 'view_id',
                    'value' => function($model){
                        return $model->view->name;
                    },
                    'filter' => Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'view_id',
                        'data' => ModelsList::getAllModelViews(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => Yii::t('app', 'View ID'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                [
                    'attribute' => 'type_id',
                    'value' => function($model){
                        return $model->type->name;
                    },
                    'filter' => Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'type_id',
                        'data' => ModelsList::getAllTypes(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => Yii::t('app', 'Type ID'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]),
                ],
                [
                    'attribute' => 'rawMaterials',
                    'contentOptions' => ['style' => 'width:15%;'],
                    'value' => function($model){
                        $rms = $model->modelsRawMaterials;
                        if (!empty($rms)) {
                            $return = '';
                            foreach ($rms as $k => $rm) {
                                $return .= "<code>" . $rm->rm->code . "</code> " .
                                    $rm->rm->name . "<br><small><i>" .
                                    $rm->rm->rawMaterialThread . "<br>" .
                                    $rm->rm->rawMaterialConsist . "</i></small><br>" ;
                            }
                            return $return;
                        }
                        return '';
                    },
                    'format' => 'raw',
                ],
                'add_info:ntext',
                [
                    'attribute' => 'created_by',
                    'contentOptions' => ['style' => 'width:10%;'],
                    'value' => function($model){
                        return $model->author->user_fio
                            ."<br><small><i>" .
                            date('d.m.Y H:i',$model->created_at) .
                            "</i></small>";
                    },
                    'format' => 'html',
                    'filter' => Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'created_by',
                        'data' => ModelsList::getAuthorList(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                [
                    'attribute' => 'updated_by',
                    'contentOptions' => ['style' => 'width:10%;'],
                    'value' => function($model){
                        return $model->updatedBy->user_fio
                            ."<br><small><i>" .
                            date('d.m.Y H:i',$model->updated_at) .
                            "</i></small>";
                    },
                    'format' => 'html',
                    'filter' => Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'updated_by',
                        'data' => ModelsList::getUpdatedByList(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                //'washing_notes:ntext',
                //'finishing_notes:ntext',
                //'packaging_notes:ntext',

                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return (\app\modules\base\models\BaseModel::getStatusList($model->status)) ? \app\modules\base\models\BaseModel::getStatusList($model->status) : $model->status;
                    },
                    'filter' => \app\modules\base\models\BaseModel::getStatusList()
                ],
                //'created_by',
                //'created_at',
                //'updated_at',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['style' => 'width:100px;'],
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class'=>"btn btn-xs btn-default"
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
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
                    'visibleButtons' => [
                        'update' => function($model) {
                            return (Yii::$app->user->can('models-list/update')
                                    && $model->status != $model::STATUS_SAVED)
                                || Yii::$app->user->can('models-list/activate');
                        },
                        'delete' => function($model) {
                            return Yii::$app->user->can('models-list/delete') && Yii::$app->user->can('models-list/activate');
                        },
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end()?>
    </div>
<?php
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$css = <<< Css
.select2-container--krajee strong.select2-results__group{
    display:none;
}

.select2-container--krajee ul.select2-results__options>li.select2-results__option[aria-selected] {
    font-size: 11px;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
    right: 5px;
    opacity: 0.5;
    z-index: 999;
    font-size: 18px;
    top: -7px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
    top: 60%;
}
Css;
$this->registerCss($css);