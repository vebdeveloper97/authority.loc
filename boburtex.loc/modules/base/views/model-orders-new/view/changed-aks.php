<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 26.07.20 2:16
 */

use app\components\PermissionHelper as P;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\MoiRelOrdersItems;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ModelOrders*/
/* @var $models MoiRelOrdersItems[] */
?>

<div class="pull-right" style="margin-top: -22px;">
    <div class="pull-right" style="margin-top: -22px;">
        <?php if (P::can('model-orders/save-and-planned-toquv')): ?>
            <?php  if ($model->status == $model::STATUS_CHANGED_AKS): ?>
                <?= Html::a(Yii::t('app', 'Tasdiqlash'), ["save-and-checked-toquv-aks", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php if (P::can('model-orders/save-and-planned-toquv')): ?>
    <?php  if ($model->status == $model::STATUS_CHANGED_AKS): ?>
        <form action="<?=\yii\helpers\Url::to(["cancelled-toquv-aks", 'id' => $model->id])?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <label><?php echo Yii::t('app','Izox')?></label>
            <textarea name="add_info" class="form-control customRequired" rows="10" style="height: auto;"></textarea>
            <div class="pull-right" style="">
                <?= Html::submitButton(Yii::t('app', 'Bekor qilish'), ['class' => 'btn btn-danger']) ?>
            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>
<div class="model-planning">
    <?php foreach ($model->modelOrdersItems as $key => $item):?>
        <div class="document-items <?=($item->status==2)?'customDisabled bg-danger':''?>">
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
            <?php if($item->status!=2):?>
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
                    <div class="col-md-2">
                        <label><?=Yii::t('app',"O'zgargan mato nomi")?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finished Fabric')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Raw Fabric')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish En')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish Gramaj')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Add Info')?></label>
                    </div>
                </div>
                <?php $matoPlan = $item->getPlanningAks();
                if($matoPlan!=null) :
                    foreach ($matoPlan as $n => $m) :
                        $changed_mato = \app\modules\toquv\models\ToquvRmOrderChangeList::findOne([
                            'from_trm_id' => $m->toquv_raw_materials_id,
                            'color_pantone_id' => $m->color_pantone_id,
                            'color_id' => $m->color_id,
                            'model_orders_id' => $m->model_orders_id,
                            'status' => 1
                        ]);
                        ?>
                        <div class="row <?=($changed_mato)?'bg-danger':''?>">
                            <div class="col-md-1">
                                <div class="list">
                                    <?php $color = (!empty($color_pantone_list))?$color_pantone_list[$m['color_pantone_id']]:[] ?>
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
                                    <div class="list">
                                        <?php $color_boyoq = (!empty($color_boyoq_list))?$color_boyoq_list[$m['color_id']]:[] ?>
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
                            </div>
                            <div class="col-md-2">
                                <code><span class='bordered' style="background-color: #eeeeee;border: 1px solid #d2d6de;color: #555555;transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;"><?=($changed_mato)?$changed_mato->fromTrm->name:$m->toquvRawMaterials->name ?></span></code>
                            </div>
                            <div class="col-md-2">
                                <span class='bordered' style="background-color: #eeeeee;border: 1px solid #d2d6de;color: #555555;transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;"><?= $changed_mato->toTrm->name ?></span>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?= $m['finished_fabric'] ?>' class='form-control'
                                       disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?= $m['raw_fabric'] ?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?= $m['thread_length'] ?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?= $m['finish_en'] ?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <input type='text' value='<?= $m['finish_gramaj'] ?>' class='form-control' disabled>
                            </div>
                            <div class="col-md-1">
                                <textarea class='form-control' disabled><?= $changed_mato['add_info'] ?></textarea>
                            </div>
                        </div>
                    <?php endforeach;
                endif;
            endif;?>
        </div>
    <?php endforeach;?>
</div>