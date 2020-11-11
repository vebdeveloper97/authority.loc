<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<?php if(!empty($model)): ?>
    <?php foreach($model as $key => $val): ?>
        <div class="col-lg-3">
            <div class="thumbnail">
                <img src="<?=\yii\helpers\Url::base().$val['path']?>" style="height: 200px; width: 100%;" alt="">
                <div class="caption" style="background: #f0f0f0">
                    <strong align="center"><?=$val['title']?></strong>
                    <p style="margin-top: 15px; text-align: justify"><?=substr($val['content'], 0, 200).'...'?></p>
                    <p><a class="btn btn-success btn-xs" href="<?=\yii\helpers\Url::to(['site/news', 'id' => $val['id']])?>"><?=Yii::t('app', 'View Demo')?> &raquo;</a></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
