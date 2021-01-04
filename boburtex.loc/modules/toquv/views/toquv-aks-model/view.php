<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvAksModel */
/* @var $models app\modules\toquv\models\ToquvAksModelItem[] */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Aks Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-aks-model-view">
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="pull-right" style="margin-bottom: 15px;">
            <?php if (Yii::$app->user->can('toquv-aks-model/update')): ?>
                <?php if ($model->status != $model::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('toquv-aks-model/delete')): ?>
                <?php if ($model->status != $model::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?= Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
        </div>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'code',
            [
                'attribute' => 'image',
                'value' => function ($model) {
                    $image = ($model->image) ? "<img src='/web/" . $model->image . "' class='thumbnail imgPreview round' style='width:80px;border-radius: 100px;height:80px;'> " : '';
                    return $image;
                },
                'format' => 'html'
            ],
            'width',
            'height',
            'qavat',
            [
                'attribute' => 'trm.name',
                'label' => Yii::t('app', 'Turi'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'rawMaterialConsist',
                'format' => 'raw',
            ],
            [
                'attribute' => 'rawMaterialIp',
                'format' => 'raw',
            ],
            'palasa',
            /*'price',
            'pb_id',
            'musteri_id',
            'color_pantone_id',
            'color_boyoq_id',
            'raw_material_type',
            'color_type',*/
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return (app\modules\toquv\models\ToquvAksModel::getStatusList($model->status)) ? app\modules\toquv\models\ToquvAksModel::getStatusList($model->status) : $model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return (\app\models\Users::findOne($model->created_by)) ? \app\models\Users::findOne($model->created_by)->user_fio : $model->created_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return (time() - $model->created_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->created_at), 'relativeTime') : date('d.m.Y H:i', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return (time() - $model->updated_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->updated_at), 'relativeTime') : date('d.m.Y H:i', $model->updated_at);
                }
            ],
        ],
    ]) ?>
    <table class="multiple-input-list table table-condensed table-renderer">
        <thead>
            <tr>
                <th class="list-cell__toquv_ne_id">Ne</th>
                <th class="list-cell__toquv_thread_id">Iplik turi</th>
                <th class="list-cell__toquv_ip_color_id">Ip rangi</th>
                <th class="list-cell__color_pantone_id">Rang</th>
                <th class="list-cell__height">Eni(sm)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $item) {?>
            <tr id="row0" class="multiple-input-list__item" data-row-index="0">
                <td class="list-cell__toquv_ne_id">
                    <?=$item->ne->name?>
                </td>
                <td class="list-cell__toquv_thread_id">
                    <?=$item->thread->name?>
                </td>
                <td class="list-cell__toquv_ip_color_id">
                    <?=$item->ipColor->name?>
                </td>
                <td class="list-cell__color_pantone_id">
                    <?php
                    $color = $item->colorPantone;
                    echo "<span style='background:rgb(".$color['r'].",
                            ".$color['g'].",".$color['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>bbb</span></span> {$color['code']}";
                    ?>
                </td>
                <td class="list-cell__height">
                    <?=$item->height?>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
