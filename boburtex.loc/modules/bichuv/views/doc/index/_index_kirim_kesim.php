<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 28.05.20 17:01
 */

use app\modules\bichuv\models\BichuvDocSearch;
use kartik\select2\Select2;
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type} (ishlab chiqarishdan)', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kirim-mato-tab">
    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Ishlab chiqarishdan'),
                'active' => ($this->context->slug == BichuvDoc::DOC_TYPE_ACCEPTED_SLICE_LABEL),
                'url' => Yii::$app->urlManager->createUrl(['bichuv/doc/qabul_kesim/index'])
            ],
            [
                'label' => Yii::t('app', 'Boshqalardan'),
                'active' => ($this->context->slug == BichuvDoc::DOC_TYPE_INCOMING_SLICE_LABEL),
            ]
        ]]);
    ?>
</div>
<div class="toquv-documents-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->is_returned == 1){
                return ['class' => 'info'];
            }else{
                if($model->status == 1){
                    return ['class' => 'danger'];
                }else{
                    return ['class' => 'success'];
                }
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
                'attribute' => 'party',
                'label' => Yii::t('app','Partiya No'),
                'value' => function($model){
                    return $model->getPartyNoNames();
                }
            ],
            [
                'attribute' => 'nastel_party',
                'label' => Yii::t('app','Nastel No'),
                'value' => function($model){
                    return $model->getNastelParty();
                }
            ],
            [
                    'attribute' => 'from_department',
                    'label' => Yii::t('app','Qayerdan'),
                    'value' => function($model){
                        return "<b>".$model->fromHrDepartment->name ."</b><br><small><i>". $model->fromHrEmployee->fish . "</i></small>";
                    },
                    'format' => 'raw',
                    'filter' => $searchModel->getDepartments()
            ],
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
                    $result = $model->getWorkCount('slice',true);
                    return "<div><div class='text-center'><small>{$result['size']}</small></div><div class='text-center'><b>{$result['count']}</b></div></div>";
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('doc/qabul_kesim/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('doc/qabul_kesim/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('doc/qabul_kesim/delete') && $model->status !== $model::STATUS_SAVED;
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
                'urlCreator' => function ($action, $model, $key, $index) {
                    $slug = Yii::$app->request->get('slug');
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'slug' => $slug]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model->id,'slug' => $slug]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
