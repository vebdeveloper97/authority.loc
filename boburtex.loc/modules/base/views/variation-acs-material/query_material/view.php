<?php

use app\modules\wms\models\WmsDocument;
use app\modules\wms\models\WmsMatoInfo;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $document app\modules\wms\models\WmsDocument */
/* @var $documentItems app\modules\wms\models\WmsDocumentItems */

$this->title = '№ ' . $document->doc_number . " ({$document->reg_date})";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="wms-document-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if($document->status == 1): ?>
            <?php if (Yii::$app->user->can('wms-document/incoming_general_order/update')): ?>
                <?php  if ($document->status != $document::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $document->id, 'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('wms-document/incoming_general_order/delete')): ?>
                <?php  if ($document->status != $document::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $document->id, 'slug' => $this->context->slug], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?=  Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $document->id, 'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $document,
        'attributes' => [
            'doc_number',
            [
                'attribute' => 'reg_date',
                'format' => ['date', 'php:d.m.Y  H:i:s'],
            ],
            [
                'attribute' => 'from_department',
                'value' => function($model) {
                    return $model->fromDepartment->name;
                }
            ],
            'fromEmployee.fish',
            [
                'attribute' => 'to_department',
                'value' => function($model) {
                    return $model->toDepartment->name;
                }
            ],
            'toEmployee.fish',
            'add_info:ntext',
        ],
    ]) ?>

    <table class="table table-bordered">

        <thead>
        <th>#</th>
        <th><?=Yii::t('app', 'Material')?></th>
        <th><?=Yii::t('app', 'Color')?></th>
        <th><?=Yii::t('app', 'En/gramaj')?></th>
        <th><?=Yii::t('app', 'Quantity') . '(kg)'?></th>
        </thead>
        <tbody>
        <?php
        $cnt = 1;

        ?>
        <?php foreach ($documentItems as $item): ?>
            <?php
            $material = WmsMatoInfo::getList($item['entity_id']);
            ?>
            <tr>
                <td>
                    <?=$cnt++?>
                </td>
                <td>
                    <?=WmsMatoInfo::getMaterialNameById($item['entity_id'])?>
                </td>
                <td>
                    <?=WmsMatoInfo::getMaterialColorById($item['entity_id'])?>
                </td>
                <td>
                    <?=$material['en'] . ' sm | ' . $material['gramaj'] . ' gr/sm<sup>2</sup>'?>
                </td>
                <td>
                    <?=$item['quantity']?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
