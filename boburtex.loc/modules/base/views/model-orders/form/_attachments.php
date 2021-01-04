<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 16.03.20 18:35
 */



/* @var $this \yii\web\View */
/* @var $attachments \app\modules\toquv\models\ToquvDocumentItems[]|array|\yii\db\ActiveRecord[] */
/* @var $form \yii\widgets\ActiveForm|static */
?>


<div class="col-md-1 col-w-12 aksessuar toquv_acs" style="width: 90px">
    <?php $item_toquv_acs = $models[$i]->modelOrdersItemsAcs;?>
    <div class="form-group field-modelordersitems-model_toquv_acs_id">
        <label><?= Yii::t('app', 'Aksessuarlar') ?></label>
        <div class="input-group">
            <input type="text" class="form-control toquv_acs_count input_count" id="toquv_acs_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($item_toquv_acs)?>">
            <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#toquv_acs-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
        </div>
    </div>
    <div id="toquv_acs-modal_<?=$i?>" class="fade modal toquv_acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app',"To'quv aksessuarlar")?></h3>
                </div>
                <div class="modal-body">
                    <table id="table_toquv_acs_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul"><?=Yii::t('app','Artikul / Kodi')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Aksessuar')?></th>
                            <th class="list-cell__turi">
                                <?=Yii::t('app','Turi')?>
                            </th>
                            <!--<th class="list-cell__qty">
                                <?/*=Yii::t('app',"Miqdori")*/?>
                            </th>-->
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="list-cell__button">
                                <div class="add_toquv_acs btn btn-success" data-row-index="<?=$i?>"><i class="glyphicon glyphicon-plus"></i></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($item_toquv_acs)){
                            foreach ($item_toquv_acs as $key => $item_toquv_acs) {?>
                                <tr class="multiple-input-list__item row_<?=$item_toquv_acs->bichuvAcs['id']?>" data-row-index="<?=$key?>">
                                    <td class="list-cell__artikul"> <span type="text" class="form-control" disabled=""><?=$item_toquv_acs->bichuvAcs['sku']?></span> </td>
                                    <td class="list-cell__name"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs['name']?></span>
                                        <input type="hidden" class="toquv_acs_input form-control" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][id]" value="<?=$item_toquv_acs->bichuvAcs['id']?>">
                                        <input type="hidden" class="toquv_acs_input form-control" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][unit_id]" value="<?=$item_toquv_acs->bichuvAcs['unit_id']?>"> </td>
                                    <td class="list-cell__turi"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs->property['name']?></span> </td>
                                    <td class="list-cell__qty">
                                        <input type="text" class="toquv_acs_input form-control number" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][qty]" value="<?=$item_toquv_acs['qty']?>"> </td>
                                    <td class="list-cell__unit_id"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs->unit['name']?></span> </td>
                                    <td class="list-cell__barcod"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs['barcode']?></span> </td>
                                    <td class="list-cell__add_info">
                                        <input type="text" class="toquv_acs_input form-control" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][add_info]" value="<?=$item_toquv_acs['add_info']?>"> </td>
                                    <td class="list-cell__toquv_acs_image"> <img class="imgPreview pr_image" src="<?=$item_toquv_acs->bichuvAcs->imageOne?>"> </td>
                                    <td class="list-cell__button">
                                        <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr"> <i class="glyphicon glyphicon-remove"></i> </div>
                                    </td>
                                </tr>
                            <?php }
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
