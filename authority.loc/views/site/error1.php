<?php
    /* @var $model \app\modules\admin\models\MessageUz */
?>
<div class="col-lg-6">
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only"><?=Yii::t('app', 'Error')?>:</span>
        <?=Yii::t('app', "Siz qidirgan ma\'lumot topilmadi!")?>
        - <strong><?=$model?></strong>
    </div>
</div>