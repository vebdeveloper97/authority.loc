<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 12.01.20 13:12
 */

/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 10.01.20 15:27
 */



/* @var $this \yii\web\View */
/* @var $item array|false */
/* @var $key integer|false */

use app\modules\base\models\ModelOrdersItems;
use app\modules\toquv\models\ToquvDocuments;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression; ?>
<?php
$count = $key+1;
if ($item['own_qty'] > 0) {
    $isOwn = 1;
    $Qty = $item['own_qty'];
    $isOwnLabel = Yii::t('app', 'O\'zimizniki');
} else {
    $isOwn = 2;
    $Qty = $item['their_qty'];
    $isOwnLabel = Yii::t('app', 'Mijozniki');
}
?>
<table id="new_table">
    <tr class="tr_<?=$item['tro_id']?> tr_thread_<?=$item['troi_id']?>">
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][quantity]", $Qty, ['class'=>'thread_quantity']); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][thread_name]", null, ['id' => "instructionItemText_{$key}"]); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][is_own]", $isOwn); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][rm_item_id]", $item['troi_id']); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][musteri_id]", null, ['id' => "instructionItemMusteri_{$key}"]); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][lot]", null, ['id' => "instructionItemLot_{$key}"]); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][percentage]", $item['percentage'],['class' => 'thread_percentage']); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][toquv_ne]", $item['nename'],['class' => 'thread_toquv_ne']); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][toquv_thread]", $item['thrname'],['class' => 'thread_toquv_thread']); ?>
        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][toquv_ip_color]", $item['tclname'],['class' => 'thread_toquv_ip_color']); ?>
        <td><?= $count; ?></td>
        <td>
            <?php $musteri = (!empty($item['order_musteri']))?" ({$item['order_musteri']})":'';?>
            <?php $moi = (!empty($item['moi_id'])&&ModelOrdersItems::findOne($item['moi_id']))?ModelOrdersItems::findOne($item['moi_id'])->info:'';?>
            <?= "{$item['ca']} {$musteri} <br>{$moi}"; ?>
        </td>
        <td><?= $isOwnLabel; ?></td>
        <td class="thread_mato_name"><?= $item['mato'] . " - <span class='material_" . $item['tro_id'] . "'>" . $item['qty'] . "</span> kg" ?></td>
        <td class="thread_ip_name"><?= $item['nename'] . "-" . $item['thrname'] . " - " ?> <span
                class='percentage_<?= $item['tro_id'] ?>'
                percentage='<?= $item['percentage'] ?>'><?= $Qty ?></span> kg
        </td>
        <td style="width: 350px;">
            <?= Select2::widget([
                'name' => "ItemsRM[{$item['tro_id']}][child][{$key}][entity_id]",
                'data' => ToquvDocuments::searchEntityInstructionStatic($item['neid'], $item['ttid'], $isOwn, $item['mid'])['list'],
                'options' => [
                    'placeholder' => Yii::t('app', 'Ip tanlash ...'),
                    'options' => ToquvDocuments::searchEntityInstructionStatic($item['neid'], $item['ttid'], $isOwn, $item['mid'])['options'],
                    'multiple' => false,
                    'data-ne' => $item['neid'],
                    'data-thread' => $item['ttid'],
                    'data-id' => $key,
                    'required' => true,
                    'class' => 'tabularSelectEntity',
                    'id' => 'tabular_select_'.$key
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'escapeMarkup' => new JsExpression('function (markup) { 
                                        return markup; 
                                    }'),
                    'templateResult' => new JsExpression('function(ip) { return ip.text; }'),
                    'templateSelection' => new JsExpression(
                        "function (ip) { 
                                    if(ip.id){
                                        let element = ip.element;
                                        $('#instructionItemText_{$key}').val(ip.text);
                                        $('#instructionItemLot_{$key}').val($(element).attr('lot'));
                                        $('#instructionItemMusteri_{$key}').val($(element).attr('musteri_id'));
                                    }
                                    return ip.text;
                             }"),
                ],
                'pluginEvents' => []
            ]) ?>
        </td>
        <td>
            <?= Html::input('text', "ItemsRM[{$item['tro_id']}][child][{$key}][fact]", null, ['class' => 'form-control number new_thread_qty required qty_' . $item['tro_id'], 'percentage' => $item['percentage']]); ?>
        </td>
        <td>
            <?= Html::textarea("ItemsRM[{$item['tro_id']}][child][{$key}][add_info]", '', ['class' => 'form-control']) ?>
        </td>
        <td>
            <button type="button" class="btn btn-success copy" data-id="<?=$item['troi_id']?>" data-num="<?=$key?>" data-order="<?=$item['tro_id']?>" style="margin-top: 1px;"><i class="fa fa-plus"></i></button>
            <button type="button" data-id="<?=$item['troi_id']?>" class="btn btn-danger delete_row" style="margin-top: 1px;"><i class="fa fa-close"></i></button>
        </td>
    </tr>
</table>
