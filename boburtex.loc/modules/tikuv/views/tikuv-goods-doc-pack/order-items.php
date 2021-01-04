<?php
/* @var $this yii\web\View */
/* @var $items app\modules\tikuv\models\TikuvGoodsDoc */
?>
<?php if($items){ foreach ($items as $key => $item){ ?>
    <?php
    $remain = 0;
    if(!empty($item['moving']) && $item['moving'] > 0){
        $remain = $item['accepted'] - $item['moving'];
    }else{
        $remain = $item['accepted'];
    }
    ?>
    <?php if($remain > 0):?>
        <tr id="row<?=$key?>" class="multiple-input-list__item" data-row-index="<?=$key?>">
             <td class="list-cell__goods_id">
                    <div class="field-tikuvgoodsdoc-<?=$key?>-goods_id">
                        <input type="hidden" name="TikuvGoodsDoc[<?= $key?>][goods_id]" value="<?= $item['gid']?>">
                        <?php if($item['type'] == 1):?>
                            <input type="text" class="form-control" disabled value="<?php echo "{$item['model_no']} - {$item['color']} - ({$item['sizeName']})"?>">
                        <?php else:?>
                            <input type="text" class="form-control" disabled value="<?=$item['name']?>">
                        <?php endif;?>
                    </div>
                </td>
             <td class="list-cell__remain">
                    <div class="field-tikuvgoodsdoc-<?=$key?>-remain form-group">
                        <input type="text" id="tikuvgoodsdoc-<?=$key?>-remain" class="form-control" value="<?= $remain; ?>" disabled>
                    </div>
                </td>
             <td class="list-cell__quantity">
                    <div class="field-tikuvgoodsdoc-<?=$key?>-quantity form-group">
                        <input type="text" value="<?=$remain?>" name="TikuvGoodsDoc[<?= $key?>][quantity]" id="tikuvgoodsdoc-<?=$key?>-quantity" class="tabular-cell quantity quantityMoving number form-control" tabindex="<?= ($key+1)?>">
                    </div>
                </td>
            <td class="list-cell__weight">
                <div class="field-tikuvgoodsdoc-<?=$key?>-weight form-group">
                    <input type="text" class="form-control" value="<?= $item['weight']; ?>" disabled>
                </div>
            </td>
            <td class="list-cell__unit_id">
                <div class="field-tikuvgoodsdoc-<?=$key?>-unit_id form-group">
                    <input type="text" class="form-control" value="<?= $item['unitName']; ?>" disabled>
                </div>
            </td>
        </tr>
    <?php endif;?>
<?php }}?>
