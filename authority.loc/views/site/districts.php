<?php
/* @var $model \app\modules\admin\models\CategoriesUz */

use yii\widgets\LinkPager;

?>
<?php if(!empty($model)): ?>
    <?php foreach($model as $key => $val): ?>
        <div class="col-lg-3">
            <div class="thumbnail" style="height: 450px;">
                <img src="<?=\yii\helpers\Url::base().$val['path']?>" style="height: 200px; width: 100%;" alt="">
                <div class="caption" style="background: #f0f0f0; height: 240px">
                    <strong style="display: block" align="center"><?=$val['title']?></strong>
                    <p style="margin-top: 15px; text-align: justify"><?=substr($val['content'], 0, 200).'...'?></p>
                    <p><a class="btn btn-success btn-xs" href="<?=\yii\helpers\Url::to(['site/news', 'id' => $val['id']])?>"><?=Yii::t('app', 'View Demo')?> &raquo;</a></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-lg-3">
        <div class="alert alert-danger">
            <?=Yii::t('app', 'Data Not Found'); ?>
        </div>
    </div>
<?php endif; ?>
<?php
echo LinkPager::widget([
    'pagination' => $this->params['pages'],
]);
