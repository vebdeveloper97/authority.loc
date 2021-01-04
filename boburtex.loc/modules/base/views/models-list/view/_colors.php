<?php

/* @var $this yii\web\View */
/* @var $colors app\modules\base\models\ModelsVariationColors[] */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="container-fluid">
    <div class="row">
        <?php $i = 1; foreach ($colors as $color) {?>
        <div class="col-md-2 col-lg-2" style="margin-bottom: 0;padding-left:0;padding-right: 5px">
            <div class="btn btn-default" style="width: 100%;padding: 3px 5px;">
                <div style="min-width: 100px;" title="<?= $color->colorPantone['code']?>">
                    <span class="badge pull-left"><?=$i?></span><?=$color->wmsColor->fullname?><b><?= $color->colorPantone['code']?></b><?=($i==1)?"<i class='fa fa-check'></i>":''?>
                </div>
                <div class="text-center" style="clear:both;">
                    <span class="btn" style="background: <?=$color->wmsColor->fullColor?>;width: 90%;border: 1px solid #000;padding: 0;">
                        <span style="opacity: 0;">
                            rgb
                        </span>
                    </span>
                    <!--<div style="min-width: 100px;" title="<?/*= $color->colorBoyoqhona['color_id']; */?>">
                        <span class="pull-left"></span><b><?/*=$color->colorBoyoqhona['name']*/?></b>
                    </div>
                    <span class="btn" style="background: <?/*= $color->colorBoyoqhona['color'];*/?>;width: 90%;border: 1px solid #000;padding: 0;">
                        <span style="opacity: 0;">
                            rgb
                        </span>
                    </span>-->
                </div>

                <div class="variation-color_detail" style="font-size: 0.9em;">
                    <span class="text-bold"><?= Yii::t('app','Detail Name');?>:</span>
                    <?= (!empty($color->baseDetailList))?$color->baseDetailList->name:''; ?>
                </div>
                <div class="variation-color_detail">
                    <span class="text-bold"><?= Yii::t('app','Mato');?>:</span>
                    <?= (!empty($color->rawMaterial))?$color->rawMaterial->name:''; ?>
                </div>
            </div>

        </div>
        <?php $i++; }?>
    </div>
</div>