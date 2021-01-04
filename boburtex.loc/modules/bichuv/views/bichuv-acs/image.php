<?php
use yii\helpers\Html;
?>

<div class="bichuv-acs-form">
    <?php echo \app\widgets\snapshoot\SnapShoot::widget([
        'buttonClass' => 'rasm hidden'
    ]);?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id'=>'saveImage']) ?>
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app','Bekor qilish')?></button>
    </div>
</div>