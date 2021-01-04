<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelsList;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */
/* @var $rawMaterials \app\modules\base\models\ModelOrdersItemsMaterial */

$this->registerCss("
.acs-img {
    width: 50px;
    height: auto;
}
table.table {
    font-size:12px;
}
");
?>
<div class="row form-group" style="padding-top: 20px">
    <div class="col-md-12">
        <div class="materials-items">
            <h3 style="padding: 0 0 10px 20px">
                <?=Yii::t('app','Matolar')?>
                <small>
                    (<?=Yii::t('app','Add Info')?>:
                    <i class='fa fa-check'></i> -
                    <?=Yii::t('app','Asosiy')?>)
                </small>
            </h3>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 40px">№</th>
                        <th><?=Yii::t('app','Image')?></th>
                        <th><?=Yii::t('app','Name')?></th>
                        <th><?=Yii::t('app','Raw Material Consists')?></th>
                        <th><?=Yii::t('app','Thread ID')?></th>
                        <th><?=Yii::t('app','Raw Material Type ID')?></th>
                        <th><?=Yii::t('app','Fin.gr/bo\'yi')?></th>
                        <th><?=Yii::t('app','Fin.en/eni')?></th>
<!--                        <th>--><?//=Yii::t('app',"O'lchamlar")?><!--</th>-->
                        <th>
                            <?=Yii::t('app','Add Info')?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php if($model->modelsRawMaterials){
                $i = 1;
                foreach ($model->modelsRawMaterials as $key){?>
                    <tr class="">
                        <td>
                            <?=($i==1)?"<i class='fa fa-check'></i>":$i?>
                        </td>
                        <td class="text-center">
                            <?php
                                if($key->rm->type == $key->rm::ACS) {
                                    echo $key->rm->imageOne ?
                                        "<img class='image acs-img imgPreview' src='/web/". $key->rm->imageOne ."' alt='". $key->rm['code'] ."'>" :
                                         "";
                                }
                            ?>
                        </td>
                        <td style="padding-left: 10px;">
                            <b><?=$key->rm['name']?></b>
                        </td>
                        <td style="padding-left: 10px;">
                            <?=$key->rm->rawMaterialConsist?>
                        </td>
                        <td style="padding-left: 10px;">
                            <?=$key->rm->rawMaterialIp?>
                        </td>
                        <td style="padding-left: 10px;">
                            <?=$key->rm->rawMaterialType['name']?>
                        </td>
                        <td style="padding-left: 10px;">
                            <?=$key->thread_length?>
                        </td>
                        <td style="padding-left: 10px;">
                            <?=$key->finish_en?>
                        </td>
                        <!--<td style="padding-left: 10px;max-width: 250px">
                            <?/*=$key->getSizeList(true);*/?>
                        </td>-->
                        <td>
                            <b><?=$key['add_info']?></b>
                        </td>
                    </tr>
                <?php $i++; }
            }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="acs-items">
            <h3 style="padding: 0 0 10px 20px">
                <?=Yii::t('app','Toquv Acs ID')?>
            </h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 40px">№</th>
                    <th><?=Yii::t('app','Code')?></th>
                    <th><?=Yii::t('app','Name')?></th>
                    <th><?=Yii::t('app','One unit quantity')?></th>
                    <th><?=Yii::t('app',"O'lchamlar")?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($model->modelsToquvAcs)){
                    $i = 1;
                    foreach ($model->modelsToquvAcs as $key){
                        ?>
                        <tr>
                            <td>
                                <?=$i?>
                            </td>
                            <td style="padding-left: 10px;">
                                <?=$key['wmsMatoInfo']['toquvRawMaterials']['code']?>
                            </td>
                            <td>
                                <b><?=$key['wmsMatoInfo']['toquvRawMaterials']['name']?></b>
                            </td>
                            <td>
                                <b><?=number_format($key['qty'], 3)?></b>
                                <?php /*echo $key->bichuvAcs->unitName */?>
                            </td>
                            <td style="padding-left: 10px;max-width: 250px">
                                <?php
                                    if($key['sizes']){
                                        ?>
                                        <?php foreach($model->getSizes($key['sizes']) as $size): ?>
                                            <code ><?=$size['name']?></code>
                                        <?php endforeach; ?>
                                        <?php
                                    }
                                    else{
                                        ?>
                                            <code><?=Yii::t('app', "Barcha o'lchamlar")?></code>
                                        <?php
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php $i++; }
                }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="acs-items">
            <h3 style="padding: 0 0 10px 20px">
                <?=Yii::t('app','Bichuv Acs ID')?>
            </h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 40px">№</th>
                    <th><?=Yii::t('app','Sku')?></th>
                    <th><?=Yii::t('app','Name')?></th>
                    <th><?=Yii::t('app','Property ID')?></th>
                    <th><?=Yii::t('app','One unit quantity')?></th>
                    <th><?=Yii::t('app',"O'lchamlar")?></th>
                    <th><?=Yii::t('app','Add Info')?></th>
                </tr>
                </thead>
                <tbody>
                    <?php if($model->modelsAcs){
                    $i = 1;
                    foreach ($model->modelsAcs as $key){?>
                        <tr>
                            <td>
                                <?=$i?>
                            </td>
                            <td style="padding-left: 10px;">
                                <?=$key->bichuvAcs['sku']?>
                            </td>
                            <td>
                                <b><?=$key->bichuvAcs['name']?></b>
                            </td>
                            <td>
                                <?php if($key->bichuvAcs->properties): ?>
                                    <?php foreach($key->bichuvAcs->properties as $k => $v): ?>
                                        <code><?=$v['value']?></code>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <b><?=number_format($key['qty'], 3)?></b>
                                <?=$key->bichuvAcs->unitName ?>
                            </td>
                            <td style="padding-left: 10px;max-width: 250px">
                                <?=$key->getSizeList(true);?>
                            </td>
                            <td>
                                <b><?=$key['add_info']?></b>
                            </td>
                        </tr>
                        <?php $i++; }
                }?>
                </tbody>
            </table>
        </div>
</div>

</div>


