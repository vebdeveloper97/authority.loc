<?php

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="row">
        <div class="col-md-12" style="padding-top: 15px">
            <span class="attachments-items">
                <?php if(!empty($attachments[0]->attachment->id)){
                    foreach ($attachments as $key){
                        if($key->attachment->id){
                 ?>
                <div class="imgPreview" src="/web/<?=$key->attachment->path?>" style="float:left;background-image: url('/web/<?=$key->attachment->path?>');
                                    width: 140px;height: 140px;
                                    background-size: 100% 100%;
                                    border: 1px solid;margin-right: 5px;">
                    <span class="hiddenAttachments">
                        <input type="hidden" name="ModelVarRelAttach[]" value="<?=$key->attachment->id?>">
                    </span>
                </div>
                <?php }}}?>
            </span>
        </div>
    </div>