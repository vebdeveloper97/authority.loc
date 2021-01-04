<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 18.03.20 9:40
 */

use app\modules\base\models\ModelOrders;
use app\modules\base\models\MoiRelDept;
use app\modules\toquv\models\ToquvOrders;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;
use app\components\PermissionHelper as P;

/* @var $this View */
/* @var $model ModelOrders|ActiveRecord */
/* @var $toquv MoiRelDept */
/* @var $toquv_orders ToquvOrders[]|mixed */
?>
<?php
if($model->getChildDepts(MoiRelDept::TYPE_MATO)){?>
    <div class="model-planning-toquv">
        <div class="pull-right" style="margin-top: -2px;">
            <?php if (P::can('model-orders/save-and-planned-toquv')): ?>
                <?php  if ($model->status == $model::STATUS_PLANNED): ?>
                    <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-planned-toquv", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update-toquv', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php foreach ($model->modelOrdersItems as $key => $item):?>
            <div class="document-items">
                <div class="row">
                    <?php $list = $model_list[$item['id']];?>
                    <div class="col-md-6">
                        <div class="col-md-7 form-group">
                            <label class="control-label"><?=Yii::t('app','Model')?></label>
                            <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$list['model']. " (".$list['article'] .")"?>">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label"><?=Yii::t('app','Variant')?></label>
                            <input type="text" class="form-control" disabled value="<?=$list['var']. ' ' .$list['var_code']?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('app','O`lchovlar miqdori')?></label>
                            <div class="row">
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Buyurtma')?> </div>
                                <div class="col-md-9 "><?=$size_custom_list[$item['id']]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                                <div class="col-md-9 "><?=$size_custom_list_percentage[$item['id']]?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('app','Buyurtma miqdori')?></label>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Buyurtma')?> : </div>
                                <div class="col-md-8"> <span class="customDisabled" style="padding: 0 20%;"><?=$size_custom_list_all[$item['id']]?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                                <div class="col-md-8">
                                    <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$size_custom_list_percentage_all[$item['id']]?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Rang')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app',"Rang (Bo'yoqxona)")?></label>
                    </div>
                    <div class="col-md-2">
                        <label><?=Yii::t('app','Mato nomi')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Thread Length')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish En')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish Gramaj')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Kerakli miqdor')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Buyurtma miqdori')?></label>
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
                    <?php foreach ($item->getChildDepts(MoiRelDept::TYPE_MATO) as $n => $m){
                        $planning = $m->modelOrdersPlanning;
                    ?>
                        <div class="row planParent">
                            <div class="col-md-1">
                                <div class="list">
                                    <?php $color = (!empty($color_pantone_list))?$color_pantone_list[$planning['color_pantone_id']]:[] ?>
                                    <span style="background: rgb(<?= $color['r'] ?>,<?= $color['g'] ?>,<?= $color['b'] ?>);width: 10%">
                                            <span style="opacity: 0;">
                                                <span class="badge">
                                                    r
                                                </span>
                                            </span>
                                        </span>
                                    <span style="padding-left: 5px;">
                                            <?= $color['code'] ?>
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="list">
                                    <?php $color_boyoq = (!empty($color_boyoq_list))?$color_boyoq_list[$planning['color_id']]:[] ?>
                                    <span style="background: <?= $color_boyoq['color'] ?>;width: 10%">
                                            <span style="opacity: 0;">
                                                <span class="badge">
                                                    r
                                                </span>
                                            </span>
                                        </span>
                                    <span style="padding-left: 5px;">
                                            <?= $color_boyoq['color_id'] ?>
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-2 plan_mato">
                                <input type='text' value='<?=(array_key_exists($m['modelOrdersPlanning']['toquv_raw_materials_id'],$mato_list))?$mato_list[$m['modelOrdersPlanning']['toquv_raw_materials_id']]['name']:$m['modelOrdersPlanning']['toquvRawMaterials']['name']?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['thread_length']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['finish_en']?>">
                            </div>
                            <div class="col-md-1 finish_gramaj">
                                <input type="text" disabled class="form-control" value="<?=$m['finish_gramaj']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['modelOrdersPlanning']['raw_fabric']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['quantity']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['start_date']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['end_date']?>">
                            </div>
                            <div class="col-md-1">
                                <textarea disabled class="form-control" rows="1"><?=$m['add_info']?></textarea>
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
            ['reg-toquv', 'id' => $model->id],
            ['class' => 'btn btn-lg btn-success'])
        ?>
    </div>
<?php } ?>
