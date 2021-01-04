<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvItemBalance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Item Balances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-item-balance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'entity_id',
            'entity_type',
            'count',
            'inventory',
            'reg_date',
            'department_id',
            'is_own',
            'price_uzs',
            'price_usd',
            'sold_price_uzs',
            'sold_price_usd',
            'sum_uzs',
            'sum_usd',
            'document_id',
            'document_type',
            'version',
            'comment:ntext',
            'created_by',
            'status',
            'created_at',
            'updated_at',
            'price_rub',
            'sold_price_rub',
            'price_eur',
            'sold_price_eur',
            'lot',
        ],
    ]) ?>

</div>
