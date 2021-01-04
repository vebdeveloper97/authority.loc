<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="toquv-documents-view">

        <div class="pull-right" style="margin-bottom: 15px;">
            <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
            <?php if (Yii::$app->user->can('doc/kirim_acs/update')): ?>
                <?php if($model->status != $model::STATUS_SAVED):?>
                    <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                        ['class' => 'btn btn-success']) ?>
                <?php endif;?>
            <?php endif;?>
            <?php if (Yii::$app->user->can('doc/kirim_acs/delete')): ?>
                <?php if($model->status != $model::STATUS_SAVED):?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif;?>
            <?php endif;?>
        </div>

        <table class="table table-bordered table-responsive">
            <tr>
                <td><strong><?= Yii::t('app','From department')?></strong>: <?= $model->musteri->name ?></td>
                <td><strong><?= Yii::t('app','To department')?></strong>: <?= $model->toHrDepartment->name ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('app','Responsible person')?></strong>: <?= $model->musteri_responsible ?></td>
                <td><strong><?= Yii::t('app','Responsible person')?></strong>: <?= $model->toHrEmployee->fish ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
                <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            </tr>
            <tr>
                <td colspan="2"><strong><?= Yii::t('app', 'Asos')?></strong>: <?= $model->add_info ?></td>
            </tr>
        </table>
        <div class="center-text">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => '{items}{pager}',
                'showFooter' => true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'entity_id',
                        'label' => Yii::t('app','Name'),
                        'value' => function($model){
                            return $model->getAccessories($model->entity_id);
                        },
                        'contentOptions' => ['style' => 'width:30% !important;'],
                    ],
                    [
                        'attribute' => 'price_sum',
                        'label' => Yii::t('app','Narx (UZS)'),
                        'footer' => BichuvDocItems::getTotalPrice($dataProvider->models,['price_sum'], true),
                        'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                    ],
                    [
                        'attribute' => 'price_usd',
                        'label' => Yii::t('app','Narx ($)'),
                        'footer' => BichuvDocItems::getTotalPrice($dataProvider->models,['price_usd'], true),
                        'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                    ],
                    [
                        'attribute' => 'quantity',
                        'label' => Yii::t('app','Quantity'),
                        /*'value' => function($model) {
                            return $model->. " ". $model->unit->name;
                        },*/
                        'footer' => BichuvDocItems::getTotal($dataProvider->models,['quantity']),
                        'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                    ],
                    [
                        'label' => Yii::t('app','Summa (UZS)'),
                        'value' => function($model){
                            return $model->getSum();
                        },
                        'footer' => BichuvDocItems::getTotalSum($dataProvider->models, ['price_sum','quantity']),
                        'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                    ],
                    [
                        'label' => Yii::t('app','Summa ($)'),
                        'value' => function($model){
                            return $model->getSum(2);
                        },
                        'footer' => BichuvDocItems::getTotalSum($dataProvider->models, ['price_usd','quantity']),
                        'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                    ]
                ],
            ]); ?>
        </div>
        <!--<div class="payment-box no-print">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <strong>
                            <?/*= Yii::t('app','To\'lov ma\'lumoti')*/?>:
                        </strong>
                    </td>
                    <td>
                        <?/*= \app\models\PaymentMethod::getData($model->payment_method) */?>
                    </td>
                    <td>
                        <strong>
                        <?/*= number_format($model->paid_amount, 2, ".", " ") */?>
                        </strong>
                        <small><i><?/*= $model->pbId->name */?></i></small>
                    </td>
                </tr>
            </table>
        </div>
        <div class="expenses-box no-print">
            <table class="table table-bordered">
                <tr>
                    <td><?/*= Yii::t('app','Qo\'shimcha harajatlar')*/?>:</td>
                    <td style="color: red;">
                        <strong>
                            <?php
/*                            if(!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])){
                                echo $model->bichuvDocExpenses[0]->price.' '.$model->bichuvDocExpenses[0]->pb->name;
                            }
                            */?>
                        </strong>
                    </td>
                    <td>
                        <?php
/*                        if(!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])){
                            echo $model->bichuvDocExpenses[0]->add_info;
                        }
                        */?>
                    </td>
                </tr>
            </table>
        </div>-->
    </div>
<?php
