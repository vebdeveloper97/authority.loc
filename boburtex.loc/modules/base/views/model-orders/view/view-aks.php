<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 07.05.20 12:27
 */

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelsList;
use app\modules\base\models\MoiRelDept;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;
use app\components\PermissionHelper as P;

/* @var $this View */
/* @var $model ModelOrders|ModelsList|ActiveRecord */
/* @var $models MoiRelDept */
?>
<div class="model-planning-aks">
    <div class="pull-right" style="margin-top: -22px;">
        <?php if (P::can('model-orders/update')): ?>
            <?php  if ($model->status < $model::STATUS_PLANNED_TOQUV): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['change-aks', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php foreach ($model->modelOrdersItems as $key => $item):?>
        <div class="document-items">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-7">
                        <label class="control-label"><?=Yii::t('app','Model')?></label>
                        <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$item->modelsList->name. " (".$item->modelsList->article .")"?>">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label"><?=Yii::t('app','Variant')?></label>
                        <input type="text" class="form-control" disabled value="<?=$item->modelVar->name. ' ' .$item->modelVar->code?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label"><?=Yii::t('app','O`lchovlar miqdori')?></label>
                        <div class="row">
                            <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Buyurtma')?> </div>
                            <div class="col-md-9 "><?=$item->getSizeCustomList('customDisabled','')?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                            <div class="col-md-9 "><?=$item->getSizeCustomListPercentage('customDisabled alert-success','',$item->percentage)?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label"><?=Yii::t('app','Buyurtma miqdori')?></label>
                        <div class="row">
                            <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Buyurtma')?> : </div>
                            <div class="col-md-8"> <span class="customDisabled" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                            <div class="col-md-8">
                                <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$item->getAllCountPercentage($item->percentage)?></span>
                            </div>
                        </div>
                        <input type="hidden" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
                    </div>
                </div>
            </div>
            <div class="parentDiv">
                <table id="table_acs_<?=$key?>" class="multiple-input-list table table-condensed table-renderer">
                    <thead>
                    <tr>
                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul"><?=Yii::t('app','Artikul / Kodi')?></th>
                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Nomi')?></th>
                        <th class="list-cell__turi">
                            <?=Yii::t('app','Turi')?>
                        </th>
                        <th class="list-cell__qty">
                            <?=Yii::t('app',"Miqdori")?>
                        </th>
                        <th class="list-cell__unit_id">
                            <?=Yii::t('app',"O'lchov birligi")?>
                        </th>
                        <th class="list-cell__barcod">
                            <?=Yii::t('app','Barkod')?>
                        </th>
                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                            <?=Yii::t('app','Add Info')?>
                        </th>
                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__acs_attachments">
                            <?=Yii::t('app','Rasmlar')?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($item->modelOrdersItemsAcs)){
                        foreach ($item->modelOrdersItemsAcs as $row => $item_acs) {?>
                            <tr class="multiple-input-list__item row_<?=$item_acs->bichuvAcs['id']?>" data-row-index="<?=$row?>">
                                <td class="list-cell__artikul"> <span type="text" class="form-control" disabled=""><?=$item_acs->bichuvAcs['sku']?></span> </td>
                                <td class="list-cell__name"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs['name']?></span>
                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][id]" value="<?=$item_acs->bichuvAcs['id']?>">
                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][unit_id]" value="<?=$item_acs->bichuvAcs['unit_id']?>"> </td>
                                <td class="list-cell__turi"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs->property['name']?></span> </td>
                                <td class="list-cell__qty">
                                    <input type="text" class="acs_input form-control number" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][qty]" value="<?=$item_acs['qty']?>" readonly> </td>
                                <td class="list-cell__unit_id"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs->unit['name']?></span> </td>
                                <td class="list-cell__barcod"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs['barcode']?></span> </td>
                                <td class="list-cell__add_info">
                                    <input type="text" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][add_info]" value="<?=$item_acs['add_info']?>" readonly> </td>
                                <td class="list-cell__acs_image"> <img class="imgPreview pr_image" src="<?=$item_acs->bichuvAcs->imageOne?>"> </td>
                            </tr>
                        <?php }
                    }?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach;?>
</div>
