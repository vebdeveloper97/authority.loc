<?php

use yii\helpers\Html;
use app\modules\tikuv\models\TikuvOutcomeProducts;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $items array*/
/* @var $item array */
?>
<?php if($items): foreach ($items as $key => $item):?>
<?php $quantity = number_format($item['quantity'],0,'.',' ');?>
<tr id="row<?=$key?>" class="multiple-input-list__item" data-row-index="<?=$key?>">
    <td class="list-cell__barcode">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-barcode">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][goods_id]" value="<?=$item['good_id']?>">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][model_no]" value="<?=$item['model_no']?>">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][color_code]" value="<?=$item['code']?>">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][size_type_id]" value="<?=$item['size_type_id'];?>">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][amount]" value="<?=$item['quantity']?>">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][size_id]" value="<?=$item['size_id']?>">
            <input type="hidden" name="TikuvOutcomeProducts[<?=$key?>][barcode]" tabindex="1" value="<?=$item['barcode']?>">
            <input type="text" class="form-control" disabled="" value="<?=$item['barcode']?>">
        </div>
    </td>
    <td class="list-cell__barcode1">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-barcode1">
            <input type="text" class="form-control" disabled="" value="<?=$item['barcode1']?>">
        </div>
    </td>
    <td class="list-cell__barcode2">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-barcode2">
            <input type="text" class="form-control" disabled="" value="<?=$item['barcode2']?>">
        </div>
    </td>
    <td class="list-cell__size_id">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-size_id form-group">
            <input type="text" class="form-control" value="<?=$item['size_name']?>" disabled>
        </div>
    </td>
    <td class="list-cell__count">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-count form-group">
            <input type="text" class="form-control" value="<?= $quantity; ?>" disabled>
        </div>
    </td>
    <td class="list-cell__quantity">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-quantity form-group">
            <input type="text" value="<?= $quantity; ?>" id="tikuvoutcomeproducts-<?=$key?>-quantity" class="tabular-cell quantity number form-control" name="TikuvOutcomeProducts[<?=$key?>][quantity]" field="quantity" onkeyup="changeMouse('quantity')" tabindex="1">
        </div>
    </td>
    <td class="list-cell__sort_type_id">
        <div class="field-tikuvoutcomeproducts-<?=$key?>-sort_type_id form-group">
            <?=Html::dropDownList("TikuvOutcomeProducts[{$key}][sort_type_id]",'',TikuvOutcomeProducts::getSortTypes(),['class' => 'form-control', "id" => "tikuvoutcomeproducts-{$key}-sort_type_id", "tabindex" => "1"])?>
        </div>
    </td>
    <td class="list-cell__cp">
        <div class="field-tikuvoutcomeproducts-0-cp-button form-group">
            <span id="tikuvoutcomeproducts-0-cp-button-copy">
                <button type="button" class="multiple-input-list__btn js-input-cloned btn btn-info">
                    <i class="glyphicon glyphicon-duplicate"></i>
                </button>
            </span>
            <span id="tikuvoutcomeproducts-0-cp-button-remove">
                <button type="button" class="multiple-input-list__btn js-input-remove btn btn-danger">
                    <i class="fa fa-close"></i>
                </button>
            </span>
        </div>
    </td>
</tr>
<?php endforeach; endif;?>
