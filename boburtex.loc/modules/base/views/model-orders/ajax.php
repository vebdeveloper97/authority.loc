<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\models\PulBirligi;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $models app\modules\base\models\ModelOrdersItems */
/* @var $form yii\widgets\ActiveForm */
$url = Url::to(['model-orders/get-model-variations']);
$url_size = Url::to(['model-orders/size']);
?>
<?php $form = ActiveForm::begin(); ?>
<div id="rmContent">
    <div class="document-items row" style="padding-top:5px">
        <div class="pull-right removeButtonParent"><button class="btn btn-danger removeButton"><span class="fa fa-trash"></span></button></div>
        <div class="rmParent" data-row-index="<?=$i?>">
            <div class="col-md-3 rmOrderId">
                <?php echo $form->field($models, 'models_list_id')->widget(Select2::classname(), [
                    'data' => [],
                    'language' => 'ru',
                    'options' => [
                        'class' => 'rm_order',
                        'name' => 'ModelOrdersItems['.$i.'][models_list_id]',
                        'prompt' => Yii::t('app', 'Model tanlang'),
                        'indeks' => $i,
                        'id' => 'model_orders_item-model-list_'.$i,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => $urlRemain,
                            'dataType' => 'json',
                            'data' => new JsExpression(
                                "function(params) {
                                            return { 
                                                q:params.term
                                            };
                                    
                                        }"),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression(
                            "function (markup) { 
                                                return markup;
                                            }"
                        ),
                        'templateResult' => new JsExpression(
                            "function(data) {
                                                   return data.text;
                                             }"
                        ),
                        'templateSelection' => new JsExpression(
                            "function (data) { return data.text; }"
                        ),
                    ],
                    'pluginEvents' => [
                        'select2:select' => new JsExpression(
                            "function(e){
                                        if(e.params.data){
                                            let t = $(this);
                                            let parent = t.parents('.rmParent');
                                            if(e.params.data.baski==0){
                                                parent.find('.baski').addClass('hidden');
                                            }else{
                                                parent.find('.baski').removeClass('hidden');
                                            }
                                            if(e.params.data.prints==0){
                                                parent.find('.print').addClass('hidden');
                                            }else{
                                                parent.find('.print').removeClass('hidden');
                                            }
                                            if(e.params.data.stone==0){
                                                parent.find('.stone').addClass('hidden');
                                            }else{
                                                parent.find('.stone').removeClass('hidden');
                                            }
                                        }
                                    }
                                "),
                    ]
                ]); ?>
                <div class="rmSpan"></div>
            </div>
            <div class="col-md-1 col-w-12">
                <?/*= $form->field($models, 'model_var_id')->widget(Select2::className(), [
                    'data' => [],
                    'options' => [
                        'id' => 'modelVar'.$i,
                        'name' => 'ModelOrdersItems['.$i.'][model_var_id]',
                        'indeks' => $i,
                        'class' => 'rmModelVar'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                */?>
                <div class="form-group field-modelordersitems-model_var_id">
                    <label><?= Yii::t('app', 'Variant') ?></label>
                    <div class="input-group">
                        <span type="text" class="form-control var_name" id="var_<?=$i?>" aria-describedby="var-addon_<?=$i?>" disabled></span>
                        <?=$form->field($models, 'model_var_id')->hiddenInput(['id'=>'model-var-'.$i,'class'=>'model_var_id','name'=>'ModelOrdersItems['.$i.'][model_var_id]'])->label(false)?>
                        <span class="input-group-addon btn btn-success" id="var-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#var-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                    </div>
                </div>
                <div id="var-modal_<?=$i?>" class="fade modal var-modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <p class="modal-top" style="padding-right: 10px;padding-top: 10px;text-align: right;">
                                <button type="button" class="btn btn-success btn-lg form-variation" data-url="<?=Yii::$app->urlManager->createUrl('base/models-variations/create')?>" style="padding: 3px 6px;font-size: 14px;">
                                    <i class="fa fa-plus"></i>
                                </button> &nbsp;&nbsp;
                                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
                            </p>
                            <div class="modal-header">
                            </div>
                            <div class="search_div">
                                <input type="text" class="form-control search_var" placeholder="<?php echo Yii::t('app','Qidirish uchun shu yerga yozing')?>">
                            </div>
                            <div class="modal-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-w-12 aksessuar acs" style="width: 90px">
                <div class="form-group field-modelordersitems-model_acs_id">
                    <label><?= Yii::t('app', 'Aksessuarlar') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control acs_count input_count" id="acs_<?=$i?>" aria-describedby="basic-addon_<?=$i?>">
                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#acs-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                    </div>
                </div>
                <div id="acs-modal_<?=$i?>" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3><?php echo Yii::t('app','Aksessuarlar')?></h3>
                            </div>
                            <div class="modal-body">
                                <table id="table_acs_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
                                    <thead>
                                    <tr>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul"><?=Yii::t('app','Artikul / Kodi')?></th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Nomi')?></th>
                                        <th class="list-cell__turi">
                                            <?=Yii::t('app','Turi')?>
                                        </th>
                                        <th class="list-cell__qty">
                                            <?=Yii::t('app',"Miqdori")?>
                                        </th>
                                        <th class="list-cell__unit_id">
                                            <?=Yii::t('app',"O'lchov birligi")?>
                                        </th>
                                        <th class="list-cell__barcod">
                                            <?=Yii::t('app','Barkod')?>
                                        </th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                            <?=Yii::t('app','Add Info')?>
                                        </th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__acs_attachments">
                                            <?=Yii::t('app','Rasmlar')?>
                                        </th>
                                        <th class="list-cell__button">
                                            <div class="add_acs btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-w-12" style="width: 85px;">
                <?= $form->field($models, 'load_date')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Yii::t('app', 'Sana'),
                        'name' => 'ModelOrdersItems['.$i.'][load_date]',
                        'id' => 'model_orders_item-load_date_'.$i,
                        'class' => 'customRequired'
                    ],
                    'language' => 'ru',
                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                    ]
                ]); ?>
            </div>
            <div class="col-md-1 col-w-12" style="width: 70px;">
                <?= $form->field($models, 'brend_id')->dropDownList(\app\modules\base\models\ModelsList::getAllBrend(),[
                    'id' => 'model_brend_id_'.$i,
                    'name' => 'ModelOrdersItems['.$i.'][brend_id]',
                    'indeks' => $i,
                    'encodeSpaces' => false,
                    'encode' => false,
                    'prompt' => Yii::t('app', 'Brend tanlang'),
                    'class' => 'form-control brend_id customRequired'
                ]);
                ?>
            </div>
            <div class="col-md-1 col-w-12 priority_div" style="width: 70px;">
                <?= $form->field($models, 'priority')->dropDownList($models->priorityList,[
                    'options'=>$models->getPriorityList('options'),
                    'id' => 'model_priority_'.$i,
                    'name' => 'ModelOrdersItems['.$i.'][priority]',
                    'indeks' => $i,
                ]);
                ?>
            </div>
            <div class="col-md-1 col-w-12" style="width: 80px;">
                <?= $form->field($models, 'season')->textInput([
                    'id' => 'model_season_'.$i,
                    'name' => 'ModelOrdersItems['.$i.'][season]',
                    'indeks' => $i,
                ]);
                ?>
            </div>
            <div class="col-md-1 col-w-12" style="width: 80px;">
                <?= $form->field($models, 'add_info')->textInput([
                    'id' => 'model_add_info_'.$i,
                    'name' => 'ModelOrdersItems['.$i.'][add_info]',
                    'indeks' => $i,
                ]);
                ?>
            </div>
            <div class="col-md-1 col-w-12" style="width: 30px;">
                <?= $form->field($models, 'percentage')->textInput([
                    'id' => 'model_percentage_'.$i,
                    'name' => 'ModelOrdersItems['.$i.'][percentage]',
                    'indeks' => $i,
                    'value' => 0,
                    'class' => 'number form-control'
                ]);
                ?>
            </div>
            <div class="col-md-1 col-w-12 aksessuar baski hidden" style="width: 90px">
                <div class="form-group field-modelordersitems-model_baski_id">
                    <label><?= Yii::t('app', 'Baski') ?></label>
                    <?php
                    echo Select2::widget([
                        'name' => 'ModelOrdersItems['.$i.'][baski_id]',
                        'data' => $model->baskiList,
                        'language' => 'ru',
                        'options' => [
                            'prompt' => Yii::t('app', 'Select'),
                            'indeks' => $i,
                            'id' => 'model_orders_item-model-baski_'.$i,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { 
                                                    return markup;
                                                }"
                            ),
                        ],
                    ])
                    ?>
                </div>
            </div>
            <div class="col-md-1 col-w-12 aksessuar print hidden" style="width: 90px">
                <div class="form-group field-modelordersitems-model_print_id">
                    <label><?= Yii::t('app', 'Print') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control print_count" id="print_<?=$i?>" aria-describedby="basic-addon_<?=$i?>">
                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#print-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                    </div>
                </div>
                <div id="print-modal_<?=$i?>" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3><?php echo Yii::t('app','Printlar')?></h3>
                            </div>
                            <div class="modal-body">
                                <table id="table_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
                                    <thead>
                                    <tr>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Nomi')?></th>
                                        <th class="list-cell__desen_no">
                                            <?=Yii::t('app','Desen No')?>
                                        </th>
                                        <th class="list-cell__code">
                                            <?=Yii::t('app','Code')?>
                                        </th>
                                        <th class="list-cell__brend">
                                            <?=Yii::t('app','Brend')?>
                                        </th>
                                        <th class="list-cell__width">
                                            <?=Yii::t('app','Width')?>
                                        </th>
                                        <th class="list-cell__height">
                                            <?=Yii::t('app','Height')?>
                                        </th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                            <?=Yii::t('app','Add Info')?>
                                        </th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__prints_attachments">
                                            <?=Yii::t('app','Rasmlar')?>
                                        </th>
                                        <th class="list-cell__button">
                                            <div class="add_prints btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-w-12 aksessuar stone hidden" style="width: 90px">
                <div class="form-group field-modelordersitems-model_stone_id">
                    <label><?= Yii::t('app', 'Naqsh/Tosh') ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control stone_count" id="stone_<?=$i?>" aria-describedby="basic-addon_<?=$i?>">
                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#stone-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                    </div>
                </div>
                <div id="stone-modal_<?=$i?>" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3><?php echo Yii::t('app','Naqshlar')?></h3>
                            </div>
                            <div class="modal-body">
                                <table id="table_stone_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
                                    <thead>
                                    <tr>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Nomi')?></th>
                                        <th class="list-cell__desen_no">
                                            <?=Yii::t('app','Desen No')?>
                                        </th>
                                        <th class="list-cell__code">
                                            <?=Yii::t('app','Code')?>
                                        </th>
                                        <th class="list-cell__brend">
                                            <?=Yii::t('app','Brend')?>
                                        </th>
                                        <th class="list-cell__width">
                                            <?=Yii::t('app','Width')?>
                                        </th>
                                        <th class="list-cell__height">
                                            <?=Yii::t('app','Height')?>
                                        </th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                            <?=Yii::t('app','Add Info')?>
                                        </th>
                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__stones_attachments">
                                            <?=Yii::t('app','Rasmlar')?>
                                        </th>
                                        <th class="list-cell__button">
                                            <div class="add_stones btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-w-12 price" style="width: 70px">
                <div class="form-group field-modelordersitems-price">
                    <?= $form->field($models, 'price')->textInput([
                        'id' => 'model_price_'.$i,
                        'name' => 'ModelOrdersItems['.$i.'][price]',
                        'indeks' => $i,
                        'class' => 'number form-control'
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-md-1 col-w-12 pb_id" style="width: 65px">
                <div class="form-group field-modelordersitems-pb_id">
                    <?= $form->field($models, 'pb_id')->dropDownList(PulBirligi::getPbList(),[
                        'id' => 'model_pb_id_'.$i,
                        'name' => 'ModelOrdersItems['.$i.'][pb_id]',
                        'indeks' => $i,
                        'class' => 'number form-control'
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-md-1 col-w-12 sizeParent" style="width: 90px">
                <div class="form-group field-modelordersitems-model_size_type">
                    <label><?= Yii::t('app', 'Size Type') ?></label>
                    <?php
                    echo Select2::widget([
                        'name' => 'ModelOrdersItems['.$i.'][size_type]',
                        'data' => $model->sizeList,
                        'language' => 'ru',
                        'options' => [
                            'class' => 'rm_size',
                            'prompt' => Yii::t('app', 'Check size type'),
                            'indeks' => $i,
                            'id' => 'model_orders_item-model-size_'.$i,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { 
                                            return markup;
                                        }"
                            ),
                        ],
                    ])
                    ?>
                </div>
            </div>
            <div class="sizeDiv" style="padding-right: 15px;padding-left: 15px;float: left;"></div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>