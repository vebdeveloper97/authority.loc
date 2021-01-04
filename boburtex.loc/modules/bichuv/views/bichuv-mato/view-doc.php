<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 29.04.20 16:23
 */

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use yii\bootstrap\Collapse;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvMatoOrderItems */
/* @var $responsible app\modules\bichuv\models\BichuvDocResponsible */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $is_view boolean */
if(!$is_view) {
    $slug = Yii::$app->request->get('slug');
    $this->title = Yii::t('app', '{doc_type}  №{number} - {date}', [
        'number' => $model->doc_number,
        'date' => date('d.m.Y', strtotime($model->reg_date)),
        'doc_type' => $model->getSlugLabel()
    ]);
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
        ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
    $this->params['breadcrumbs'][] = $this->title;
}
\yii\web\YiiAsset::register($this);
?>
<?= (!empty($models))?Collapse::widget([
    'items' => [
        [
            'label' => Yii::t('app', "Tayyor bo'lmagan aksessuarlar"),
            'content' => $this->render('aks-list', [
                'models' => $models,
                'model' => $model,
                'responsible' => $responsible
            ]),
            'contentOptions' => ['class' => 'out']
        ]
    ]
]):'';
?>
<div class="toquv-documents-view">
    <?=($responsible)?'<h4><small>'.Yii::t('app', "Tayyor bo'lmagan aksessuarlarga javobgar shaxs").':</small> '.$responsible->users->user_fio.'<p><small>'.Yii::t('app', 'Izox').':</small>'.$responsible->add_info:''?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if(!$is_view){?>
            <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?php }?>
        <?php if (Yii::$app->user->can('doc/kochirish_mato/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= (!empty($models)&&$responsible === null)?'':Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
                <?php if (Yii::$app->user->can('doc/kochirish_mato/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif;?>
            <?php endif;?>
        <?php endif;?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name ?></td>
            <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'entity_id',
                    'label' => Yii::t('app','Name'),
                    'value' => function($model){
                        return $model->getRollInfo($model->entity_id);
                    },
                    'contentOptions' => ['style' => 'text-align:left;'],
                    'format' => 'raw',
                    'footer' => "<b>".Yii::t('app', 'Jami')."</b>"
                ],
                [
                    'label' => Yii::t('app','Musteri ID'),
                    'value' => function($model){
                        return $model->bichuvDoc->musteri->name;
                    }
                ],
                [
                    'attribute' => 'model_id',
                    'label' => Yii::t('app','Model'),
                    'value' => function($model){
                        return $model->productModel->name;
                    },
                    'contentOptions' => ['style' => 'text-align:left;'],
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'party_no',
                    'label' => Yii::t('app','Partiya No / Musteri Partiya No'),
                    'value' => function($model){
                        return (int)$model->party_no.' / '.$model->musteri_party_no;
                    }
                ],
                [
                    'attribute' => 'roll_count',
                    'label' => Yii::t('app','Roll Count'),
                    'format' => 'raw',
                    'footer' => BichuvDocItems::getTotal($dataProvider->models,['roll_count']),
                ],
                [
                    'attribute' => 'quantity',
                    'label' => Yii::t('app','Quantity'),
                    'contentOptions' => ['style' => 'width:150px;'],
                    'footer' => BichuvDocItems::getTotal($dataProvider->models,['quantity']),
                    'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                ],
            ],
        ]); ?>
    </div>
</div>
