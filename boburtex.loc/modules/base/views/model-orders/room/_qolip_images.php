<?php
    /* @var $model \app\modules\base\models\ModelOrders */
    use yii\helpers\Html;

    /** Qoliplarni fayllarini ko'rish */
    $patternFiles = $model->getPatternsFiles($model->id);
?>

<?php if($patternFiles): ?>
    <div class="container">
        <div class="row">
            <?php foreach ($patternFiles as $k => $v): ?>
                <div class="col-sm-3">
                    <img width="100%" src="<?=$v['path']?>" class="thumbnail imgPreview" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <p class="alert alert-warning">
        <?=Yii::t('app', 'Fayllar mavjud emas'); ?>
    </p>
<?php endif; ?>
