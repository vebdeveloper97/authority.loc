<?php

use app\modules\bichuv\models\BichuvDocSearch;
use app\modules\hr\models\HrDepartments;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->is_returned == 1){
                return ['class' => 'info'];
            }else{
                if($model->status == 1){
                    return ['class' => 'warning'];
                }else{
                    return ['class' => 'success'];
                }
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app','Hujjat'),
                'value' => function($model){
                    return '<b>№ '.$model->doc_number.'</b><br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'from_hr_department',
                'label' => Yii::t('app','Qayerdan'),
                'value' => function($model){
                    return "<b>".$model->fromHrDepartment->name ."</b><br><small><i>". $model->fromEmployee->user_fio . "</i></small>";
                },
                'format' => 'raw',
                'filter' => \kartik\tree\TreeViewInput::widget([
                    'model' => $searchModel,
                    'attribute' => 'from_hr_department',
                    'query' => HrDepartments::find()->addOrderBy('root, lft'),
                    'headingOptions' => ['label' => Yii::t('app', "To department")],
                    'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                    'fontAwesome' => true,
                    'asDropdown' => true,
                    'multiple' => false,
                    'options' => ['disabled' => false],
                    'dropdownConfig' => [
                        'input' => [
                            'placeholder' => Yii::t('app', 'Select...')
                        ]
                    ]
                ]),
                'format' => 'html',
            ],
            /*[
                'attribute' => 'musteri_id',
                'label' => Yii::t('app','Musteri ID'),
                'value' => function($model){
                    return $model->musteri->name;
                },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'created_by',
                    'data' => $searchModel->getMusteries(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'headerOptions' => [
                    'width' => '150px'
                ]
            ],*/
            [
                'attribute' => 'party',
                'label' => Yii::t('app','Partiya No'),
                'value' => function($model){
                    return $model->getMovingParties();
                }
            ],
            [
                'attribute' => 'musteri_party',
                'label' => Yii::t('app','Musteri Partiya No'),
                'value' => function($model){
                    return $model->getMovingParties(true);
                }
            ],
            [
                'attribute' => 'bichuv_nastel_list_id',
                'label' => Yii::t('app','Nastel No'),
                'value' => 'bichuvNastelList.name'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{accept-rm}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'accept-rm' => Yii::$app->user->can('doc/qabul_mato/view'),

                ],
                'buttons' => [

                    'accept-rm' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                                   ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $slug = Yii::$app->request->get('slug');

                    if ($action === 'accept-rm') {
                        $url = Url::to(["accept-rm",'id'=> $model->id,'slug' => $slug]);
                        return $url;
                    }

                }
            ],
        ],
    ]); ?>
</div>
