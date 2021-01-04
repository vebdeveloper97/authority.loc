<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 16.04.20 17:04
 */

use app\modules\base\models\ModelOrders;
use app\modules\base\models\MoiRelDept;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\components\PermissionHelper as P;

/* @var $this View */
/* @var $model ModelOrders|ActiveRecord */
/* @var $models MoiRelDept */
?>
<?php
if($model->getChildDepts(MoiRelDept::TYPE_MATO_AKS)){?>
    <div class="model-planning-toquv">
        <div class="pull-right" style="margin-top: -22px;">
            <?php if (!P::can('model-orders/reg-toquv-aks')): ?>
                <?php  if ($model->status < $model::STATUS_PLANNED_TOQUV_AKS): ?>
                    <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-planned-toquv-aks", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['reg-toquv-aks', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php foreach ($model->modelOrdersItems as $key => $item):?>
            <div class="document-items">
                <div class="row">
                    <div class="col-md-6">
                        <!--<div class="col-md-2">
                            <?php /*echo ($item->modelsList->image)?"<img src='/web/".$item->modelsList->image."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'> ":'';*/?>
                        </div>-->
                        <div class="col-md-7">
                            <label class="control-label"><?=Yii::t('app','Model')?></label>
                            <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$item->modelsList->name. " (".$item->modelsList->article .")"?>">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label"><?=Yii::t('app','Variant')?></label>
                            <input type="text" class="form-control" disabled value="<?=$item->modelVar->name. ' ' .$item->modelVar->code?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('app','O`lchovlar miqdori')?></label>
                            <div class="row">
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Buyurtma')?> </div>
                                <div class="col-md-9 "><?=$item->getSizeCustomList('customDisabled','')?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                                <div class="col-md-9 "><?=$item->getSizeCustomListPercentage('customDisabled alert-success','',$item->percentage)?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('app','Buyurtma miqdori')?></label>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Buyurtma')?> : </div>
                                <div class="col-md-8"> <span class="customDisabled" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                                <div class="col-md-8">
                                    <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$item->getAllCountPercentage($item->percentage)?></span>
                                </div>
                            </div>
                            <input type="hidden" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 plan_size">
                        <label><?=Yii::t('app','Size')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Rang')?></label>
                    </div>
                    <!--<div class="col-md-1">
                        <label><?/*=Yii::t('app',"Rang (Bo'yoqxona)")*/?></label>
                    </div>-->
                    <div class="col-md-2">
                        <label><?=Yii::t('app','Aksessuar nomi')?></label>
                    </div>
                    <div class="col-md-1 text-center mini-width">
                        <label><?=Yii::t('app','Uzunligi')?></label>
                    </div>
                    <div class="col-md-1 text-center mini-width">
                        <label><?=Yii::t('app','Eni')?></label>
                    </div>
                    <div class="col-md-1 text-center mini-width">
                        <label><?=Yii::t('app','Qavati')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Miqdori(kg)')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Miqdori(dona)')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Boshlash sanasi')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Tayyorlanish sanasi')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Add Info')?></label>
                    </div>
                </div>
                <div class="parentDiv">
                    <?php foreach ($item->getChildDepts(MoiRelDept::TYPE_MATO_AKS) as $n => $m){
                        $mop = $m->modelOrdersPlanning;
                    ?>
                        <div class="row planParent">
                            <div class="col-md-1 plan_size">
                                <input type="text" disabled class="form-control" value="<?=$m->size->name?>">
                            </div>
                            <div class="col-md-1 color_pantone text-center">
                                <?php $color = $mop->colorPantone?>
                                <span style="background: rgb(<?=$color['r']?>,<?=$color['g']?>,<?=$color['b']?>);width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                <span style="padding-left: 5px;">
                                    <?=$color['code']?>
                                </span>
                            </div>
                            <!--<div class="col-md-1 color_boyoq">
                                <?php /*$color = $mop->color*/?>
                                <span style="background: <?/*=$color->color*/?>;width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                <span style="padding-left: 5px;">
                                    <?/*=$color['color_id']*/?>
                                </span>
                            </div>-->
                            <div class="col-md-2 plan_mato">
                                <input type="text" disabled class="form-control" value="<?=$mop->toquvRawMaterials->name?>">
                            </div>
                            <div class="col-md-1 thread_length">
                                <input type="text" disabled class="form-control" value="<?=$mop['thread_length']?>">
                            </div>
                            <div class="col-md-1 finish_en">
                                <input type="text" disabled class="form-control" value="<?=$mop['finish_en']?>">
                            </div>
                            <div class="col-md-1 finish_gramaj">
                                <input type="text" disabled class="form-control" value="<?=$mop['finish_gramaj']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['quantity']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['count']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['start_date']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['end_date']?>">
                            </div>
                            <div class="col-md-1">
                                <textarea rows="1" disabled class="form-control"><?=$m['add_info']?></textarea>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
<?php }else{ ?>
    <div class="container-fluid">
        <?= Html::a(
            '<i class="fa fa-plus"></i>&nbsp;'
            .Yii::t('app', "Yangi qo'shish"),
            ['reg-toquv-aks', 'id' => $model->id],
            ['class' => 'btn btn-lg btn-success'])
        ?>
    </div>
<?php } ?>