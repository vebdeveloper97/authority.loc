<?php

use app\modules\bichuv\models\BichuvGivenRolls;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\ClearNastelForm */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clear Nastel Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="clear-nastel-form-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('clear-nastel-form/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('clear-nastel-form/delete')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nastel_party',
            'reg_date',
            'add_info:ntext',
            [
                'label' => Yii::t('app', 'Yaratgan shaxs'),
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return (\app\models\Users::findOne($model->created_by)) ? \app\models\Users::findOne($model->created_by)->user_fio : $model->created_by;
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return (app\modules\bichuv\models\BichuvGivenRolls::getStatusList($model->status)) ? app\modules\bichuv\models\BichuvGivenRolls::getStatusList($model->status) : $model->status;
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

    <?php
    $items = $model->getRollItems(false, true);
    $details = $model->getDetails();
    $modelLists = $model->modelRelProductions;
    ?>
    <h4 class="text-blue"><?= Yii::t('app','Model va ranglari');?>:</h4>
    <table class="table-responsive table-bordered table">
        <?php if(!empty($modelLists)):?>
            <?php foreach ($modelLists as $modelList):?>
                <tr>
                    <td><?= $modelList->modelsList->article." - ".$modelList->modelsList->name; ?></td>
                    <?php if(!empty($modelList->model_var_part_id)):?>
                        <td><?= $modelList->modelVarPart->basePatternPart->name." ".$modelList->modelVariation->colorPan->code." ".$modelList->modelVariation->name; ?></td>
                    <?php else:?>
                        <td><?= $modelList->modelVariation->colorPan->code." ".$modelList->modelVariation->name; ?></td>
                    <?php endif;?>
                    
                </tr>
            <?php endforeach;?>
        <?php endif;?>
    </table>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app', 'Mato Nomi'); ?></th>
            <th><?= Yii::t('app', 'En/gramaj'); ?></th>
            <th><?= Yii::t('app', 'Rang'); ?></th>
            <th><?= Yii::t('app', 'Partya № / Mijoz №'); ?></th>
            <th><?= Yii::t('app', 'Buyurtmachi'); ?></th>
            <th><?= Yii::t('app', 'Kerakli miqdor(dona)'); ?></th>
            <th><?= Yii::t('app', 'Rulon soni'); ?></th>
            <th><?= Yii::t('app', 'Miqdori(kg)'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($items)): ?>
            <?php
            $totalKg = 0;
            $totalRoll = 0;
            $totalPlan = 0;
            foreach ($items as $key => $item):?>
                <tr>
                    <td><?= ($key + 1); ?></td>
                    <td><?php
                        if ($item['is_accessory'] && $item['is_accessory'] != 1) {
                            echo "{$item['mato']}-{$item['thread']}";
                        } else {
                            echo "{$item['mato']}-{$item['ne']}-{$item['thread']}|{$item['pus_fine']}";
                        }
                        ?></td>
                    <td><?php
                        if ($item['is_accessory'] && $item['is_accessory'] != 1) {
                            echo Yii::t('app', 'Aksessuar');
                        } else {
                            echo "{$item['en']} sm/{$item['gramaj']} gr/m<sup>2</sup>";
                        }
                        ?>
                    </td>
                    <td><?= "{$item['ctone']} {$item['color_id']} {$item['pantone']}"; ?></td>
                    <td><?= $item['party_no'] . " / " . $item['musteri_party_no']; ?></td>
                    <td><?= $item['name']; ?></td>
                    <td><?= $item['required_count']; ?></td>
                    <td><?= $item['rulon_count']; ?></td>
                    <td><?= $item['rulon_kg']; ?></td>
                </tr>
                <?php
                $totalKg += $item['rulon_kg'];
                $totalRoll += $item['rulon_count'];
                $totalPlan += $item['required_count'];
            endforeach; ?>
        <?php endif; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="6" class="text-center"><?= Yii::t('app', 'Jami'); ?></th>
            <th><?= $totalPlan; ?></th>
            <th><?= $totalRoll; ?></th>
            <th><?= $totalKg; ?></th>
        </tr>
        </tfoot>
    </table>
    <?php if(false):?>
        <h4 class="text-blue"><?= Yii::t('app', 'Reja boyicha detallar'); ?>:</h4>
        <br/>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Bichuv Detail Type ID'); ?></th>
                <th><?= Yii::t('app', 'Nomi'); ?></th>
                <th><?= Yii::t('app', 'Size'); ?></th>
                <th><?= Yii::t('app', 'Reja'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($details)): ?>
                <?php
                $totalAcsPlan = 0;
                $totalDetail = [];
                $groupBy = false;
                $count = count($details);
                $index = 1;
                $isLast = false;
                $i = 0;
                foreach ($details as $key => $item):?>
                    <?php
                    $detailId = $item['detail_id'];
                    if (!empty($totalDetail) && !array_key_exists($detailId, $totalDetail)) {
                        $groupBy = true;
                    }
                    if ($count == $index) {
                        $isLast = true;
                    }
                    if (array_key_exists($detailId, $totalDetail)) {
                        $totalDetail[$detailId]['qty'] += $item['required_count'];
                        $totalDetail[$detailId]['name'] = $item['detail'];
                    } else {
                        $totalDetail[$detailId]['qty'] = $item['required_count'];
                        $totalDetail[$detailId]['name'] = $item['detail'];
                    }
                    ?>
                    <?php if ($groupBy && !$isLast): $key = key($totalDetail);?>
                        <tr>
                            <td colspan="4" class="text-center"><?= Yii::t('app','Jami')." {$totalDetail[$key]['name']}";?></td>
                            <td>
                                <?= $totalDetail[$key]['qty']; ?>
                            </td>
                        </tr>
                        <?php
                        $groupBy = false;
                        unset($totalDetail[$key]);
                    endif;
                    ?>
                    <tr>
                        <td><?= ($key + 1); ?></td>
                        <td><?= $item['detail']; ?></td>
                        <td><?= $item['name']; ?></td>
                        <td><?= $item['size_name']; ?></td>
                        <td class="text-red"><?= $item['required_count']; ?></td>
                    </tr>
                    <?php if ($isLast): $key = key($totalDetail);?>
                        <tr>
                            <td colspan="4" class="text-center"><?= Yii::t('app','Jami')." {$totalDetail[$key]['name']}";?></td>
                            <td class="text-bold">
                                <?= $totalDetail[$key]['qty'];?>
                            </td>
                        </tr>
                    <?php endif;
                    $totalAcsPlan += $item['required_count'];
                    $index++;
                endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" class="text-center">
                    <?= Yii::t('app','Jami'); ?>
                </th>
                <th style="font-size: 1.2em;">
                    <?= $totalAcsPlan; ?>
                </th>
            </tr>
            </tfoot>
        </table>
    <?php endif;?>
</div>
<div id="modal" class="fade modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

</div>
