<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 11.01.20 23:55
 */



/* @var $this \yii\web\View */
/* @var $item null|static */
/* @var $kg  */
/* @var $count  */
/* @var $samo string */

use app\modules\toquv\models\ToquvDocuments;
use yii\helpers\Html; ?>
<table id="new_table">
    <tr class="tr_<?=$count?> tr_thread_<?=$count?>_<?=$item['id']?>">
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][quantity]", $kg, ['class' => 'qty_'.$count,'percentage'=>$item->percentage]);?>
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][percentage]", $item['percentage']);?>
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][ne_id]", $item['ne_id']);?>
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][thread_id]", $item['thread_id']);?>
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][thread_name]", null, ['id' => "instructionItemText_{$count}_{$key}"]);?>
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][own_quantity]", (!empty($kg)&&$kg>0)?$kg*$item->percentage/100:"0");?>
        <?= Html::hiddenInput("ToquvInstructionRm[$count][child][{$key}][lot]", null, ['id' => "instructionItemLot_{$count}_{$key}"]); ?>
        <td><?= $samo ?></td>
        <td><?= $item->toquvRawMaterial->name." - <span class='material_".$count."'>". $kg."</span> kg" ?></td>
        <td><?php $percentage = (!empty($kg)&&$kg>0)?$kg*$item->percentage/100:"0"; echo $item->threadNeName." - <span class='percentage_".$count."' percentage='".$item->percentage."'>".$percentage."</span> kg" ?></td>
        <td style="width: 350px;">
            <?= \kartik\select2\Select2::widget([
                'name' => "ToquvInstructionRm[$count][child][{$key}][entity_id]",
                'data' => ToquvDocuments::searchEntityInstructionStatic($item['ne_id'], $item['thread_id'], 1, null)['list'],
                'options' => [
                    'placeholder' => Yii::t('app','Ip tanlash ...'),
                    'options' => ToquvDocuments::searchEntityInstructionStatic($item['ne_id'], $item['thread_id'], 1, null)['options'],
                    'multiple' => false,
                    'data-ne' => $item['ne_id'],
                    'data-thread' => $item['thread_id'],
                    'required' => true,
                    'class' => 'threadSelect',
                    'id' => 'threadSelect_'.$count.'_'.$key,
                    'data-item-text' => "instructionItemText_{$count}_{$key}",
                    'data-item-lot' => "instructionItemLot_{$count}_{$key}",
                ],
            ])?>
        </td>
        <td>
            <?= Html::input('text', "ToquvInstructionRm[$count][child][{$key}][fact]", $percentage, ['class' => 'form-control number qty_'.$count,'percentage'=>$item->percentage]);?>
        </td>
        <td>
            <?= Html::textarea("ToquvInstructionRm[$count][child][{$key}][add_info]",'',['rows'=>1])?>
        </td>
        <td>
            <button type="button" class="btn btn-success copy copy_<?=$count?>" data-count="<?=$count?>" data-num="<?=$key?>" data-id="<?=$item['id']?>" style="margin-top: 1px;"><i class="fa fa-plus"></i></button>
            <button type="button" data-count="<?=$count?>" data-id="<?=$item['id']?>" class="btn btn-danger delete_row" style="margin-top: 1px;"><i class="fa fa-close"></i></button>
        </td>
    </tr>
</table>
