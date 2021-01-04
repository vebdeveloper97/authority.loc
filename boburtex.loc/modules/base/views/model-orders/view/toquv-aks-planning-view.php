<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 21.05.20 1:29
 */
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $models app\modules\base\models\ModelOrdersPlanning */
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersPlanning;
use yii\helpers\Html;
use yii\web\View;
use app\components\PermissionHelper as P;
$this->registerJsVar('departments',ModelOrders::getDeptList());
$this->registerJsVar('empty_message',Yii::t('app', 'Bo\'limlar tanlanmagan'));
$user_id = Yii::$app->user->id;
?>
<?php if ($model->modelOrdersPlanning){?>
    <div class="pull-right" style="margin-top: -22px;">
        <?php if (P::can('model-orders/update')): ?>
            <?php  if ($model->status < $model::STATUS_PLANNED_TOQUV_AKS): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['toquv-aks-planning', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="model-planning">
        <?php foreach ($model->modelOrdersItems as $key => $item):?>
            <div class="document-items <?=($item->status==2)?'customDisabled bg-danger':''?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-2">
                            <?php if($item->modelVar->image){
                                echo "<img src='/web/".$item->modelVar->image."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'> ";
                            }elseif($item->modelsList->image){
                                echo "<img src='/web/".$item->modelsList->image."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'> ";
                            }?>
                        </div>
                        <div class="col-md-7 form-group">
                            <label class="control-label"><?=Yii::t('app','Model')?></label>
                            <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$item->modelsList->name. " (".$item->modelsList->article .")"?>">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label"><?=Yii::t('app','Variant')?></label>
                            <input type="text" class="form-control" disabled value="<?=$item->modelVar->name. ' ' .$item->modelVar->code?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <?php /*if (P::can('model-orders/update') && $item->status != 2): */?><!--
                        <?php /*echo Html::a(
                                '<i class="fa fa-tasks"></i>',
                                ['#!'],
                                [
                                    'class' => 'btn btn-primary showDepartment',
                                    'id' => 'dept_id_'.$item->id,
                                    'data-url' => Yii::$app->urlManager->createUrl(['base/model-orders/save-department','id'=>$item->id]),
                                    'data-toggle' => "modal",
                                    'data-target' => "#modalPlanning"])*/?>
                        --><?php /*endif; */?>
                    </div>

                    <div class="pull-right">
                        <?php if($item->status==2){?>
                            <span class="btn btn-lg btn-danger"><?php echo Yii::t('app','Bekor qilingan')?></span>
                        <?php }?>
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
                                <div class="col-md-7"> <span class="customDisabled" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                                <div class="col-md-7">
                                    <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$item->getAllCountPercentage($item->percentage)?></span>
                                </div>
                            </div>
                            <input type="hidden" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
                        </div>
                    </div>
                </div>
                <?php if($item->status!=2):?>
                    <div class="row">
                        <div class="col-md-1 plan_size">
                            <label><?=Yii::t('app','Size')?></label>
                        </div>
                        <div class="col-md-1">
                            <label><?=Yii::t('app','Rang')?></label>
                        </div>
                        <div class="col-md-1">
                            <label><?=Yii::t('app',"Rang (Bo'yoqxona)")?></label>
                        </div>
                        <div class="col-md-2 text-center">
                            <label><?=Yii::t('app','Aksessuar nomi')?></label>
                        </div>
                        <div class="col-md-1 text-center">
                            <label><?=Yii::t('app','Work Weight')?></label>
                        </div>
                        <!--<div class="col-md-1 text-center">
                            <label><?/*=Yii::t('app','Finished Fabric')*/?></label>
                        </div>-->
                        <div class="col-md-1 text-center">
                            <label><?=Yii::t('app','Miqdori(kg)')?></label>
                        </div>
                        <div class="col-md-1 text-center">
                            <label><?=Yii::t('app','Miqdori(dona)')?></label>
                        </div>
                        <div class="col-md-1 text-center">
                            <label><?=Yii::t('app','Uzunligi')?></label>
                        </div>
                        <div class="col-md-1 text-center">
                            <label><?=Yii::t('app','Eni')?></label>
                        </div>
                        <div class="col-md-1 text-center">
                            <label><?=Yii::t('app','Qavati')?></label>
                        </div>
                        <div class="col-md-1">
                            <label><?=Yii::t('app','Add Info')?></label>
                        </div>
                    </div>
                    <?php $aksPlan = $item->getPlanningAks();
                        if($aksPlan!=null) :
                            foreach ($aksPlan as $n => $m) :?>
                        <div class="row">
                            <div class="col-md-1 plan_size">
                                <input type="text" disabled class="form-control" value="<?=$m->size->name?>">
                            </div>
                            <div class="col-md-1">
                                <div class="list">
                                    <?php $color = $m->colorPantone?>
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
                            </div>
                            <div class="col-md-1">
                                <div class="list">
                                    <?php $color = $m->color?>
                                    <span style="background: <?=$color->color?>;width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                    <span style="padding-left: 5px;">
                                    <?=$color['color_id']?>
                                </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type='text' value='<?=$m->toquvRawMaterials->name?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['work_weight']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['raw_fabric']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['count']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['thread_length']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['finish_en']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['finish_gramaj']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?=$m['add_info']?>' class='form-control' disabled>
                            </div>
                        </div>
                        <?php endforeach;
                    endif;
                endif;?>
            </div>
        <?php endforeach;?>
    </div>
    <div class="modal fade" id="modalPlanning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?=Yii::t('app','Bo\'limlarga biriktirish')?></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php }else{ ?>
    <div class="container-fluid">
        <?= Html::a(
            '<i class="fa fa-plus"></i>&nbsp;'
            .Yii::t('app', "To'quv aksessuar plan yaratish"),
            ['toquv-aks-planning', 'id' => $model->id],
            ['class' => 'btn btn-lg btn-success'])
        ?>
    </div>
<?php } ?>
<?php
$css = <<< CSS
.plan_size{
    width: 80px;
}
CSS;
$this->registerCss($css);
