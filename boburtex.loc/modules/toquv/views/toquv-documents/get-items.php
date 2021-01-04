<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 03.12.19 22:39
 */



/* @var $this \yii\web\View */
/* @var $items array */
?>
<?php foreach ($items as $key => $item) {
    $ip = ($dept&&$item['lot']&&$item['is_own'])?\app\modules\toquv\models\ToquvDocuments::searchEntitiesOne(['is_own'=>$item['is_own'],'department_id'=>$dept,'musteri'=>$item['musteri_id'],'lot'=>$item['lot'],'entity_type'=>1]):[];
    if($ip){ ?>

<tr id="row<?=$key?>" class="multiple-input-list__item" data-row-index="<?=$key?>">
    <td class="list-cell__tib_id">
        <input type="hidden" id="toquvdocumentitems-<?=$key?>-is_own" name="ToquvDocumentItems[<?=$key?>][is_own]" value="<?=$ip['is_own']?>">
        <input type="hidden" id="toquvdocumentitems-<?=$key?>-unit_id" name="ToquvDocumentItems[<?=$key?>][unit_id]" value="2">
        <input type="hidden" id="toquvdocumentitems-<?=$key?>-entity_id" name="ToquvDocumentItems[<?=$key?>][entity_id]" value="<?=$ip['entity_id']?>">
        <input type="hidden" id="toquvdocumentitems-<?=$key?>-lot" name="ToquvDocumentItems[<?=$key?>][lot]" value="<?=$ip['lot']?>">
        <input type="hidden" id="toquvdocumentitems-<?=$key?>-document_qty" name="ToquvDocumentItems[<?=$key?>][document_qty]" value="<?=$item['fact']?>">
        <input type="hidden" id="toquvdocumentitems-<?=$key?>-tib_id" name="ToquvDocumentItems[<?=$key?>][tib_id]" value="<?=$ip['id']?>">
        <div class="field-toquvdocumentitems-<?=$key?>-tib_id form-group">
            <input type="text" value="<?="{$ip['ipname']} - {$ip['nename']} - {$ip['thrname']} - {$ip['clname']}"?>" class="form-control" disabled>
        </div>
    </td>
    <td class="list-cell__remain">
        <div class="field-toquvdocumentitems-<?=$key?>-remain form-group">
            <input type="text" id="toquvdocumentitems-<?=$key?>-remain" class="form-control" name="ToquvDocumentItems[<?=$key?>][remain]" value="<?=$ip['summa']?>" disabled="" tabindex="1">
        </div>
    </td>
    <td class="list-cell__quantity">
        <div class="field-toquvdocumentitems-<?=$key?>-quantity form-group">
            <input type="text" id="toquvdocumentitems-<?=$key?>-quantity" class="tabular-cell quantityMoving form-control" name="ToquvDocumentItems[<?=$key?>][quantity]" value="<?=$item['fact']?>" field="price_sum" tabindex="1">
        </div>
    </td>
</tr>
<?php }}?>