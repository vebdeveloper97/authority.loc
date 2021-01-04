<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */
/* @var $makine \app\modules\toquv\models\ToquvMakine */

use yii\web\JsExpression; ?>
<div class="row parentDiv">
    <div class="makineTable">
        <?php if($model){?>
            <div class="col-md-12">
                <table class="table table-bordered text-center">
                    <tr>
                        <td>
                            <?=Yii::t('app','Buyurtmachi')?>
                        </td>
                        <th>
                            <?php $musteri = (!empty($model['order_musteri']))?" ({$model['order_musteri']})":'';?>
                            <?=$model['musteri'].$musteri?>
                        </th>
                        <td>
                            <?=Yii::t('app','Buyurtma')?>
                        </td>
                        <th>
                            <?=$model['doc_number']?>
                        </th>
                        <td>
                            <?=Yii::t('app','Aksessuar nomi')?>
                        </td>
                        <th>
                            <?=$model['aksessuar']?>
                        </th>
                        <td>
                            <?=Yii::t('app','Pus/Fine')?>
                        </td>
                        <th>
                            <?php
                            echo $model['pus_fine']?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?=Yii::t('app','Buyurtma miqdori')?>
                        </td>
                        <th>
                            <?=number_format($model['quantity'],2,'.',' ')?>
                        </th>
                        <td>
                            <?=Yii::t('app','Tayyor bo\'lgan miqdori')?>
                        </td>
                        <th>
                            <?php $ready = \app\modules\toquv\models\ToquvKalite::getOneKalite($model['id'],null,null,2)?>
                            <?=($ready)?number_format($ready['summa'],2,'.',' '):0?>
                        </th>
                        <td>
                            <?=Yii::t('app','Tayyorlanishi kerak bo\'lgan miqdor')?>
                        </td>
                        <th>
                            <?php $remain = $model['quantity'] - $ready['summa']?>
                            <?=($remain>0)?$remain:Yii::t('app', 'Buyurtma bajarildi');?>
                        </th>
                        <td>
                        </td>
                        <th>
                        </th>
                    </tr>
                </table>
            </div>
        <?php }?>
        <div class="col-md-6 col-sm-4 col-xs-12">
            <div class="form-group">
                <label><?=Yii::t('app','Buyurtmalar')?></label>
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <input type="hidden" value="<?=$makine->id?>" name="Kalite[toquv_makine_id]">
                <input type="hidden" value="<?=$model['ti_id']?>" name="Kalite[toquv_instructions_id]">
                <input type="hidden" value="<?=$model['tro_id']?>" name="Kalite[toquv_rm_order_id]">
                <input type="hidden" value="<?=$model['mato_id']?>" name="Kalite[toquv_raw_materials_id]">
                <input type="hidden" value="1" name="Kalite[sort_name_id]">
                <?= \kartik\select2\Select2::widget([
                    'name' => "Kalite[toquv_instruction_rm_id]",
                    'data' => ($makine->proccesAksList['list'])?$makine->proccesAksList['list']:[],
                    'value' => $model['id'],
                    'options' => [
                        'value' => $model['id'],
                        'class' => 'instructionsSelect customRequired',
                        'id' => 'instructionsSelect',
                        'parent' => 'instructionsSelect_'.$makine->id,
                        'prompt' => 'Zakaz bo\'lgan Partiyani Tanlang ',
                        'options' => ($makine->proccesAksList['options'])?$makine->proccesAksList['options']:[],
                        'makine' => $makine->id,
                    ],
                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression("function (markup) { 
                                return markup;
                            }"),
                        'templateResult' => new JsExpression("function(data) {
                                   return data.text;
                            }"),
                        'templateSelection' => new JsExpression("
                                function (data) { return data.text; }
                            "),
                    ],
                ])
                ?>
            </div>
        </div>
        <div class="col-md-6 col-sm-4 col-xs-12">
            <div class="form-group">
                <label><?=Yii::t('app','To\'quvchi')?></label>
                <?= \kartik\select2\Select2::widget([
                    'name' => "Kalite[user_id]",
                    'data' => \app\modules\toquv\models\ToquvMakine::getUserList(null,null,'TOQUV_AKSESSUAR'),
                    'value' => $model->user_id,
                    'options' => [
                        'class' => 'userSelect customRequired',
                        'id' => 'userSelect_'.$makine->id.'_'.$id,
                        'placeholder' => Yii::t('app',Yii::t('app','To\'quv masterini tanlang'))
                    ]
                ])
                ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="form-group">
                <label><?=Yii::t('app','Quantity')?></label>
                <input type="text" name="Kalite[quantity]" class="form-control customRequired write_number customHeight number" placeholder="<?=Yii::t('app','Quantity')?>">
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="form-group">
                <label><?=Yii::t('app','Count')?></label>
                <input type="text" name="Kalite[count]" class="form-control customRequired write_number customHeight number" placeholder="<?=Yii::t('app','Count')?>">
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="form-group">
                <label><?=Yii::t('app','Rulon soni')?></label>
                <input type="text" name="Kalite[roll]" class="form-control write_number customHeight number" placeholder="<?=Yii::t('app','Rulon soni')?>">
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <label><?php echo Yii::t('app','Smena')?></label>
            <div class="radio_div">
                <label>
                    <input type="radio" class="option-input radio" name="Kalite[smena]" checked value="A" />
                    A
                </label>
                <label>
                    <input type="radio" class="option-input radio" name="Kalite[smena]" value="B"/>
                    B
                </label>
                <label>
                    <input type="radio" class="option-input radio" name="Kalite[smena]" value="C"/>
                    C
                </label>
            </div>
        </div>
    </div>
</div>
<hr style="margin: 5px;border-color:tan">
<div class="row">
    <div class="col-md-10 text-center">
        <button type="submit" class="saveButton">
            <?=Yii::t('app','Save')?>
        </button>
    </div>
</div>