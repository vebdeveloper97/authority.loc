<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvIp */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Ips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-ip-view">

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
            'name',
            'ne.name',
//            [
//                'attribute' => 'ne_id',
//                'value' => function($model){
//                    return $model->ne->name;
//                }
//            ],
            'thread.name',
            'color.name',
            'barcode',
            'status',
            'created_at:datetime',
            'updated_at:datetime',

            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->getUserName();
                }
            ],
        ],
    ]) ?>
    <hr>
    <?= Html::label(Yii::t('app','Content'))?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Type</th>
            <th scope="col">Quantity</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=1; foreach ($model->toquvIpTarkibis as $item):?>
            <tr>
                <th scope="row"><?=$i++; ?></th>
                <td><?= \app\modules\toquv\models\FabricTypes::getName($item['fabric_type_id'])?></td>
                <td><?=$item['quantity'] ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

</div>
