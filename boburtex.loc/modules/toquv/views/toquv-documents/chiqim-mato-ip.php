<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 07.12.19 21:13
 */



/* @var $this \yii\web\View */
/* @var $items \app\modules\toquv\models\ToquvDocumentItems[]|\app\modules\toquv\models\ToquvDocuments[]|\app\modules\toquv\models\ToquvInstructionItems[]|array|\yii\db\ActiveRecord[] */
?>
<?php if($items){?>
    <div class="row" style="padding-top: 5px;">
        <div class="col-md-12 text-center">
            <h5><?=$text?></h5>
        </div>
        <div class="col-md-12">
            <?php foreach ($items as $key => $item) {?>
                <div class="row" style="padding-bottom: 5px;">
                    <div class="col-md-6">
                        <input type="hidden" name="ToquvDocumentItems[<?=$index?>][child][<?=$key?>][entity_id]" value="<?=$item->entity_id?>">
                        <input type="hidden" name="ToquvDocumentItems[<?=$index?>][child][<?=$key?>][lot]" value="<?=$item->lot?>">
                        <input type="hidden" name="ToquvDocumentItems[<?=$index?>][child][<?=$key?>][musteri_id]" value="<?=$item->musteri_id?>">
                        <input type="text" class="form-control" value="<?=$item->thread_name?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="number form-control" name="ToquvDocumentItems[<?=$index?>][child][<?=$key?>][quantity]">
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
<?php }?>
