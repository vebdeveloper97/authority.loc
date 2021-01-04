<?php

/* @var $this \yii\web\View */
/* @var arary $sizes \app\modules\base\models\Size
 */
?>
<table class="table-bordered table table-responsive text-center">
    <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app',"O'lcham Nomi");?></th>
            <th><?= Yii::t('app','Reja boyicha miqdor');?></th>
        </tr>
    </thead>
    <tbody>
    <?php $count = 1; foreach ($sizes as $size):?>
        <tr>
            <td>
                <?= $count;?>
            </td>
            <td>
                <div class="form-group field-bichuvnastelitems-size_id required">
                    <input type="text" readonly="readonly" id="bichuvnastelitems-size_id_<?= $size['id']?>" class="form-control" name="BichuvNastelDetailItems[<?= $size['id']?>][size_id]" value="<?= $size['name']?>" aria-required="true">
                </div>
            </td>
            <td>
                <div class="form-group field-bichuvnastelitems-required_count required">
                    <input type="number" tabindex="<?= $count;?>" id="bichuvnastelitems-required_count_<?= $size['id']?>" class="form-control" name="BichuvNastelDetailItems[<?= $size['id']?>][required_count]"  aria-required="true">
                </div>
            </td>
        </tr>
    <?php $count++; endforeach;?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2"><?= Yii::t('app','Jami');?></th>
            <th></th>
        </tr>
    </tfoot>
</table>
