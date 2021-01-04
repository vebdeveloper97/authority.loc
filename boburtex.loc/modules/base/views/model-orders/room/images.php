<?php
    use yii\helpers\Url;
    $images_pechat = \app\modules\base\models\ModelOrders::getImages('pechat', $model->id);
    $images_naqsh = \app\modules\base\models\ModelOrders::getImages('naqsh', $model->id);
    $images = \app\modules\base\models\ModelOrders::getImages('model', $model->id);
    ?>
<?php if(!empty($images)): ?>
    <div class="row">
        <div class="col-12">
            <h5 class="alert alert-warning"><?=Yii::t('app', 'Model rasmlari')?></h5>
        </div>
        <?php foreach($images as $item): ?>
            <div class="col-sm-3">
                <img src="<?=$item['path']?>" alt="" style="height: 150px;" class="thumbnail imgPreview round">
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    <hr>
<?php if(!empty($images_pechat)): ?>
<div class="row">
    <div class="col-12">
        <h5 class="alert alert-warning"><?=Yii::t('app', 'Pechat rasmlari')?></h5>
    </div>
    <?php foreach($images_pechat as $item): ?>
        <div class="col-sm-3">
            <img src="<?=$item['path']?>" alt="" style="height: 150px;" class="thumbnail imgPreview round">
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<hr>
<?php if(!empty($images_naqsh)): ?>
<div class="row">
    <div class="col-12">
        <h5 class="alert alert-warning"><?=Yii::t('app', 'Naqsh rasmlari')?></h5>
    </div>
    <?php foreach($images_naqsh as $item): ?>
        <div class="col-sm-3">
            <img src="<?=$item['path']?>" alt="" style="height: 150px;" class="thumbnail imgPreview round">
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>