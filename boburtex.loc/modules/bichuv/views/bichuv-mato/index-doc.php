<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.05.20 11:41
 */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\bichuv\controllers\BichuvMatoDocSearch */
/* @var $dataProvider  */
/* @var $exportConfig bool */
/* @var $heading string */
?>
<div class="bichuv-mato-doc-index">
    <?php if(Yii::$app->user->can('doc/kochirish_mato/create')):?>
        <p class="pull-left">
            <?= Html::a('<span class="fa fa-plus"></span>', Url::to(['create', 'id' => $id, 'slug' => \app\modules\bichuv\models\BichuvDoc::DOC_TYPE_MOVING_MATO_LABEL]), ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>
    <?= GridView::widget([
        'id' => 'kv-grid-mato-doc',
        'dataProvider' => $dataProvider,
        'rowOptions'=>function($model){
            if($model->status == 3){
                return ['style' => 'font-weight:bold'];
            }else{
                return ['style' => "background:#B2FF59"];
            }
        },
        'summaryOptions' => [
            'class' => 'pull-right'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app','Hujjat'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'enableSorting' => false,
                'format' => 'raw',
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return "<b>".$model->toDepartment->name ."</b><br><small><i>". $model->toEmployee->user_fio . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getDepartments(true),
                'enableSorting' => false
            ],
            [
                'attribute' => 'model_id',
                'label' => Yii::t('app','Model'),
                'value' => function($model){
                    return $model->getProductModelList();
                },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'model_id',
                    'data' => $searchModel->getProductModels(),
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
                'attribute' => 'party',
                'label' => Yii::t('app','Partiya No'),
                'value' => function($model){
                    return $model->bichuvDocItems[0]->party_no;
                }
            ],
            [
                'attribute' => 'musteri_party',
                'label' => Yii::t('app','Musteri Partiya No'),
                'value' => function($model){
                    return $model->bichuvDocItems[0]->musteri_party_no;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view-doc}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view-doc' => Yii::$app->user->can('bichuv-mato/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('bichuv-mato/update') &&  $model->status < $model::STATUS_INACTIVE;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('bichuv-mato/delete') && $model->status < $model::STATUS_INACTIVE;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class' => "btn btn-xs btn-success"
                        ]);
                    },
                    'view-doc' => function ($url, $model) {
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
