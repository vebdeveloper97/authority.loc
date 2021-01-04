<?php

use app\modules\bichuv\models\BaseModel;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvGivenRollsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bichuv Given Rolls');
$this->params['breadcrumbs'][] = $this->title;
$t = Yii::$app->request->get('t',1)
?>
<ul class="nav nav-tabs tab__ul">
    <li class="active">
        <a href="#">
            <?= Yii::t('app', 'Ishlab chiqarish')?>
        </a>
    </li>
    <li>
        <a href="<?= Url::to('/bichuv/bichuv-plan/rm-list')?>">
            <?= Yii::t('app', 'Navbat')?>
        </a>
    </li>
</ul>
<br>
<div class="bichuv-given-rolls-index">
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
    <?php if (Yii::$app->user->can('bichuv-given-rolls/create')): ?>
        <p class="pull-right no-print">
            <!--Html::a('<span class="fa fa-plus"></span>', ['create','t' => $t], ['class' => 'btn btn-sm btn-success'])-->
            <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                ['export-excel?'.Yii::$app->request->queryString], ['class' => 'btn btn-sm btn-info']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            $rm = $model->checkRemainRm();
            if($rm){
                return ['class' => 'danger'];
            }else{
                return ['class' => 'success'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'doc_number',
            'reg_date',
            'nastel_party',
            [
               'attribute' => 'party_no',
               'label' => Yii::t('app','Partiya No'),
               'value' => function($model){
                    return $model->getPartyFields('party_no');
               }
            ],
            [
                'attribute' => 'musteri_party_no',
                'label' => Yii::t('app','Musteri Partiya No'),
                'value' => function($model){
                    return $model->getPartyFields('musteri_party_no');
                }
            ],
            [
                'label' => Yii::t('app','Model'),
                'value' => function($m){
                    $data = $m->getModelListInfo();
                    return $data['model'];
                }
            ],
            [
                'label' => Yii::t('app','Model rangi'),
                'value' => function($m){
                    $data = $m->getModelListInfo();
                    return $data['model_var_code'];
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('bichuv-given-rolls/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('bichuv-given-rolls/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('bichuv-given-rolls/delete') && $model->status !== $model::STATUS_SAVED;
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
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id, 't' => $model->type]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id, 't' => $model->type]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model->id, 't' => $model->type]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>


</div>
