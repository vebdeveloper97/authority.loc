<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\models\PulBirligi;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Update Model Orders: {name}', [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $models app\modules\base\models\ModelOrdersItems[] */
/* @var $form yii\widgets\ActiveForm */
/* @var $all_prints app\modules\base\models\ModelVarPrints */
/* @var $all_stone app\modules\base\models\ModelVarStone */
/* @var $all_acs app\modules\bichuv\models\BichuvAcs*/
$url = Url::to(['model-orders-new/get-model-variations']);
$create_variation_url = Url::to(['models-variations/create']);
$url_size = Url::to(['model-orders/size']);
$urlRemain = Url::to('ajax-request');
$urlSaveItem = Url::to(['save-item', 'id' => $model->id]);
$urlDeleteItem = Url::to('delete-item');
$saqlash = Yii::t('app', 'Saqlash');
$tanlash = Yii::t('app', 'Tanlash');
$tanlang = Yii::t('app', 'Tanlang');
$ulcham = Yii::t('app', "O'lcham");
$required = Yii::t('app', "Iltimos ushbu maydonni to'ldiring!");
?>

    <div class="model-orders-form">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'musteri_id')->widget(Select2::classname(), ['data' => $model->musteriList, 'language' => 'ru', 'options' => [
                    'prompt' => Yii::t('app', 'Kontragent tanlang'),
                ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(Yii::t('app', 'Buyurtmachi')); ?>
                <?= $form->field($model, 'doc_number')->hiddenInput(['maxlength' => true])->label(false) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'responsible')->widget(Select2::classname(), ['data' => $model->usersList, 'language' => 'ru', 'options' => [
                    'prompt' => Yii::t('app', 'Mas\'ul shaxslarni tanlang'),
                ],
                    'pluginOptions' => [
                        'multiple' => true,
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'prepayment')->textInput(['class' => 'form-control number', 'data-max' => 100]); ?>
            </div>
            <div class="col-md-3 hidden">
                <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Yii::t('app', 'Sana'),
                        'value' => date('d.m.Y', strtotime($model['reg_date'])),
                    ],
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                    ]
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 3]) ?>
                <?= $form->field($model, 'id')->hiddenInput(['name' => 'id'])->label(false) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Saqlash va chiqish'), ['class' => 'btn btn-success customHeight','id'=>'saveButton']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="pull-right">
            <button type="button" class="btn btn-success pull-right rmButton"><span class="fa fa-plus"></span></button>
        </div>
        <br>
        <div class="rmParentDiv">
            <?php
            $iMax = count($models);
            if($iMax>0&&!empty($models[0])){
                for ($i = 0; $i < $iMax; $i++){

                     $orderItemId = (!$copy) ? $models[$i]['id'] : null;

                     $form = ActiveForm::begin([
                            'action' => $urlSaveItem,
                            'options' => [
                                'class' => 'orderItemForm',
                                'data-row-index' => $i
                            ],
                            'id' => 'order-form-'.$i
                        ]); ?>
                    <div class="document-items row parentRow">
                        <div class="pull-right removeButtonParent">
                            <button type="button" class="btn btn-success saveItem">
                                <?=$saqlash?>
                            </button>&nbsp;
                            <button type="button" class="btn btn-primary copyButton" data-id="<?=$orderItemId?>">
                                <span class="fa fa-copy"></span>
                            </button>&nbsp;
                            <button type="button" class="btn btn-danger removeButton" data-id="<?=$orderItemId?>">
                                <span class="fa fa-trash"></span>
                            </button>
                        </div>
                        <div class="rmParent" data-row-index="<?=$i?>">
                            <div class="col-md-3 rmOrderId">
                                <?php echo $form->field($models[$i], 'models_list_id')->widget(Select2::classname(), [
                                    'data' => $models[$i]->modelList,
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
                                                        parent.find('.baski').addClass('hidden').attr('data-hidden','hidden');
                                                        parent.find('.baski_count').removeClass('customRequired');
                                                    }else{
                                                        parent.find('.baski').removeClass('hidden').attr('data-hidden','');
                                                        parent.find('.baski_count').addClass('customRequired');
                                                    }
                                                    if(e.params.data.rotatsion==0){
                                                        parent.find('.rotatsion').addClass('hidden').attr('data-hidden','hidden');
                                                        parent.find('.rotatsion_count').removeClass('customRequired');
                                                    }else{
                                                        parent.find('.rotatsion').removeClass('hidden').attr('data-hidden','');
                                                        parent.find('.rotatsion_count').addClass('customRequired');
                                                    }
                                                    if(e.params.data.prints==0){
                                                        parent.find('.print').addClass('hidden').attr('data-hidden','hidden');
                                                        parent.find('.print_count').removeClass('customRequired');
                                                    }else{
                                                        parent.find('.print').removeClass('hidden').attr('data-hidden','');
                                                        parent.find('.print_count').addClass('customRequired');
                                                    }
                                                    if(e.params.data.stone==0){
                                                        parent.find('.stone').addClass('hidden').attr('data-hidden','hidden');
                                                        parent.find('.stone_count').removeClass('customRequired');
                                                    }else{
                                                        parent.find('.stone').removeClass('hidden').attr('data-hidden','');
                                                        parent.find('.stone_count').addClass('customRequired');
                                                    }
                                                    if(e.params.data.brend_id){
                                                        parent.find('.brend_id').val(e.params.data.brend_id).trigger('change');
                                                    }
                                                    if(e.params.data.toquv_acs){
                                                        let indeks = parent.attr('data-row-index');
                                                        let table = $('#table_toquv_acs_'+indeks);
                                                        let acs = $('#toquv_acs_'+indeks);
                                                        let dataList = e.params.data.toquv_acs;
                                                        table.find('tbody').html('');
                                                        let count_toquv_acs = 0;
                                                        Object.keys(dataList).map(function(index,key){
                                                            let pr_id = index;
                                                            let pr_name = dataList[index].name;
                                                            let pr_artikul = dataList[index].artikul;
                                                            let pr_turi = dataList[index].turi;
                                                            let pr_qty = dataList[index].qty ?? 0;
                                                            let check_row = table.find('.row_'+pr_id);
                                                            let tbody = table.find('tbody');
                                                            let last_tr = tbody.find('tr').last();
                                                            let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
                                                            if(check_row.length==0){
                                                                table.find('tbody').append('<tr class=\"multiple-input-list__item row_'+pr_id+'\" data-row-index=\"'+row_index+'\">' +
                                                                    '                                <td class=\"list-cell__artikul\">' +
                                                                    '                                    <span type=\"text\" class=\"form-control\" disabled=\"\">' +
                                                                                                            pr_artikul +
                                                                                                         '</span>'+
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__name\">' +
                                                                    '                                    <span type=\"text\" class=\"acs_input form-control\" disabled=\"\">'+pr_name+'</span>' +
                                                                    '                                    <input type=\"hidden\" class=\"acs_input form-control\" name=\"ModelOrdersItems['+indeks+'][acs]['+row_index+'][id]\" value=\"'+pr_id+'\">' +
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__turi\">' +
                                                                    '                                    <span type=\"text\" class=\"acs_input form-control\" disabled=\"\">'+pr_turi+'</span>' +
                                                                    '    <td class=\"list-cell__qty\">' +
                                                                    '        <input type=\"text\" class=\"acs_input form-control number\" name=\"ModelOrdersItems['+indeks+'][acs]['+row_index+'][qty]\" value=\"'+pr_qty+'\">' +
                                                                    '    </td>' +
                                                                    '                            </tr>');
                                                            }
                                                            count_toquv_acs++;
                                                        });
                                                        acs.val(count_toquv_acs);
                                                    }
                                                    if(e.params.data.acs){
                                                        let indeks = parent.attr('data-row-index');
                                                        let table = $('#table_acs_'+indeks);
                                                        let acs = $('#acs_'+indeks);
                                                        let dataList = e.params.data.acs;
                                                        table.find('tbody').html('');
                                                        let count_acs = 0;
                                                        Object.keys(dataList).map(function(index,key){
                                                            let pr_id = index;
                                                            let pr_name = dataList[index].name;
                                                            let pr_artikul = dataList[index].artikul;
                                                            let pr_turi = dataList[index].turi;
                                                            let pr_qty = dataList[index].qty ?? 0;
                                                            let pr_unit = dataList[index].unit;
                                                            let pr_unit_id = dataList[index].unit_id;
                                                            let pr_barcod = dataList[index].barcod;
                                                            let pr_add_info = dataList[index].add_info;
                                                            let pr_image = dataList[index].image;
                                                            let check_row = table.find('.row_'+pr_id);
                                                            let tbody = table.find('tbody');
                                                            let last_tr = tbody.find('tr').last();
                                                            let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
                                                            let image = (pr_image!=null)?'<img class=\"imgPreview pr_image\" src=\"'+pr_image+'\">':'';
                                                            if(check_row.length==0){
                                                                table.find('tbody').append('<tr class=\"multiple-input-list__item row_'+pr_id+'\" data-row-index=\"'+row_index+'\">' +
                                                                    '                                <td class=\"list-cell__artikul\">' +
                                                                    '                                    <span type=\"text\" class=\"form-control\" disabled=\"\">' +
                                                                                                            pr_artikul +
                                                                                                         '</span>'+
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__name\">' +
                                                                    '                                    <span type=\"text\" class=\"acs_input form-control\" disabled=\"\">'+pr_name+'</span>' +
                                                                    '                                    <input type=\"hidden\" class=\"acs_input form-control\" name=\"ModelOrdersItems['+indeks+'][acs]['+row_index+'][id]\" value=\"'+pr_id+'\"><input type=\"hidden\" class=\"acs_input form-control\" name=\"ModelOrdersItems['+indeks+'][acs]['+row_index+'][unit_id]\" value=\"'+pr_unit_id+'\">' +
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__turi\">' +
                                                                    '                                    <span type=\"text\" class=\"acs_input form-control\" disabled=\"\">'+pr_turi+'</span>' +
                                                                    '    <td class=\"list-cell__qty\">' +
                                                                    '        <input type=\"text\" class=\"acs_input form-control number\" name=\"ModelOrdersItems['+indeks+'][acs]['+row_index+'][qty]\" value=\"'+pr_qty+'\">' +
                                                                    '    </td>' +
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__unit_id\">' +
                                                                    '                                    <span type=\"text\" class=\"acs_input form-control\" disabled=\"\">'+pr_unit+'</span>' +
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__barcod\">' +
                                                                    '                                    <span type=\"text\" class=\"acs_input form-control\" disabled=\"\">'+pr_barcod+'</span>' +
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__add_info\">' +
                        '                                   <input type=\"text\" class=\"acs_input form-control\" name=\"ModelOrdersItems['+indeks+'][acs]['+row_index+'][add_info]\" value=\"'+pr_add_info+'\">' +
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__acs_image\">' +
                                                                                                         image+
                                                                    '                                </td>' +
                                                                    '                                <td class=\"list-cell__button\">' +
                                                                    '                                    <div class=\"multiple-input-list__btn js-input-remove btn btn-danger removeTr\">' +
                                                                    '                                        <i class=\"glyphicon glyphicon-remove\"></i>' +
                                                                    '                                    </div>' +
                                                                    '                                </td>' +
                                                                    '                            </tr>');
                                                            }
                                                            count_acs++;
                                                        });
                                                        acs.val(count_acs);
                                                    }
                                                }
                                            }
                                        "),
                                    ]
                                ]); ?>
                                <div class="rmSpan"></div>
                            </div>
                            <div class="col-md-1 col-w-12">

                                <div class="form-group field-modelordersitems-model_var_id">
                                    <label><?= Yii::t('app', 'Variant') ?></label>
                                    <div class="input-group">
                                        <span type="text" class="form-control var_name" id="var_<?=$i?>" aria-describedby="var-addon_<?=$i?>" disabled><?php if($models[$i]->modelVar){?><?=$models[$i]->modelVar->name?> <i><small>(<?=$models[$i]->modelVar->code?>)</small></i><?php }?></span>
                                        <?=$form->field($models[$i], 'model_var_id')->hiddenInput(['id'=>'model-var-'.$i,'class'=>'model_var_id','name'=>'ModelOrdersItems['.$i.'][model_var_id]'])->label(false)?>
                                        <span class="input-group-addon btn btn-success add_var" id="var-addon_<?=$i?>" style="padding: 3px 6px;" data-status="no_load" data-list-id="<?=$models[$i]['models_list_id']?>" data-id="<?=$models[$i]['model_var_id']?>"><i class="fa fa-plus"></i></span>
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
                                <?php $item_acs = $models[$i]->modelOrdersItemsAcs;?>
                                <div class="form-group field-modelordersitems-model_acs_id">
                                    <label><?= Yii::t('app', 'Aksessuarlar') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control acs_count input_count" id="acs_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($item_acs)?>">
                                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#acs-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </div>
                                <div id="acs-modal_<?=$i?>" class="fade modal acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
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
                                                            <div class="add_acs btn btn-success" data-row-index="<?=$i?>"><i class="glyphicon glyphicon-plus"></i></div>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($item_acs)){
                                                        foreach ($item_acs as $key => $item_acs) {?>
                                                            <tr class="multiple-input-list__item row_<?=$item_acs->bichuvAcs['id']?>" data-row-index="<?=$key?>">
                                                                <td class="list-cell__artikul"> <span type="text" class="form-control" disabled=""><?=$item_acs->bichuvAcs['sku']?></span> </td>
                                                                <td class="list-cell__name"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs['name']?></span>
                                                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$i?>][acs][<?=$key?>][id]" value="<?=$item_acs->bichuvAcs['id']?>">
                                                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$i?>][acs][<?=$key?>][unit_id]" value="<?=$item_acs->bichuvAcs['unit_id']?>"> </td>
                                                                <td class="list-cell__turi"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs->property['name']?></span> </td>
                                                                <td class="list-cell__qty">
                                                                    <input type="text" class="acs_input form-control number" name="ModelOrdersItems[<?=$i?>][acs][<?=$key?>][qty]" value="<?=$item_acs['qty']?>"> </td>
                                                                <td class="list-cell__unit_id"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs->unit['name']?></span> </td>
                                                                <td class="list-cell__barcod"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs['barcode']?></span> </td>
                                                                <td class="list-cell__add_info">
                                                                    <input type="text" class="acs_input form-control" name="ModelOrdersItems[<?=$i?>][acs][<?=$key?>][add_info]" value="<?=$item_acs['add_info']?>"> </td>
                                                                <td class="list-cell__acs_image"> <img class="imgPreview pr_image" src="<?=$item_acs->bichuvAcs->imageOne?>"> </td>
                                                                <td class="list-cell__button">
                                                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr"> <i class="glyphicon glyphicon-remove"></i> </div>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 aksessuar toquv_acs" style="width: 90px">
                                <?php $item_toquv_acs_list = $models[$i]->modelOrdersItemsToquvAcs;?>
                                <div class="form-group field-modelordersitems-model_toquv_acs_id">
                                    <label><?= Yii::t('app', "To'quv aksessuar") ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control toquv_acs_count input_count" id="toquv_acs_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($item_toquv_acs_list)?>">
                                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#toquv_acs-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </div>
                                <div id="toquv_acs-modal_<?=$i?>" class="fade modal toquv_acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3><?php echo Yii::t('app',"To'quv aksessuarlar")?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <table id="table_toquv_acs_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
                                                    <thead>
                                                    <tr>
                                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul"><?=Yii::t('app','Artikul / Kodi')?></th>
                                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Aksessuar')?></th>
                                                        <th class="list-cell__turi">
                                                            <?=Yii::t('app','Turi')?>
                                                        </th>
                                                        <th class="list-cell__miqdor">
                                                            <?=Yii::t('app','Miqdori')?>
                                                        </th>
                                                        <!--<th class="list-cell__qty">

                                                        </th>-->
                                                        <!--<th class="list-cell__button">
                                                            <div class="add_toquv_acs btn btn-success" data-row-index=" "><i class="glyphicon glyphicon-plus"></i></div>
                                                        </th>-->
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tbody">
                                                    <?php if(!empty($item_toquv_acs_list)){
                                                        foreach ($item_toquv_acs_list as $key => $item_toquv_acs) {?>
                                                            <tr data-row-index="<?=$key?>">
                                                                <td class="list-cell__artikul">
                                                                    <span type="text" class="form-control toquv_acs_code" disabled=""><?=$item_toquv_acs->toquvRawMaterials['code']?></span>
                                                                </td>
                                                                <td class="list-cell__name">
                                                                        <?php $toquv_acs = \app\modules\toquv\models\ToquvRawMaterials::getListWithType($item_toquv_acs->toquvRawMaterials['raw_material_type_id']);?>
                                                                        <?=Html::dropDownList("ModelOrdersItems[{$i}][toquv_acs][{$key}][id]",$item_toquv_acs->toquv_raw_materials_id, $toquv_acs['list'],[
                                                                                    'options' => $toquv_acs['option'],
                                                                                    'class' => 'form-control select_list'
                                                                                ])?>
                                                                </td>
                                                                <td class="list-cell__turi">
                                                                    <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->toquvRawMaterials->rawMaterialType['name']?></span>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12" style="width: 85px;">
                                <?= $form->field($models[$i], 'load_date')->widget(DatePicker::classname(), [
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Sana'),
                                        'value' => (!empty($models[$i]['load_date']))?date('d.m.Y', strtotime($models[$i]['load_date'])):'',
                                        'name' => 'ModelOrdersItems['.$i.'][load_date]',
                                        'id' => 'model_orders_item-load_date_'.$i,
                                        'class' => 'customRequired load_date'
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
                                <?= $form->field($models[$i], 'brend_id')->dropDownList(\app\modules\base\models\ModelsList::getAllBrend(),[
                                    'id' => 'model_brend_id_'.$i,
                                    'name' => 'ModelOrdersItems['.$i.'][brend_id]',
                                    'indeks' => $i,
                                    'encodeSpaces' => false,
                                    'encode' => false,
                                    'prompt' => Yii::t('app', 'Brend tanlang'),
                                    'class' => 'form-control brend_id customRequired select_list'
                                ]);
                                ?>
                            </div>
                            <div class="col-md-1 col-w-12 priority_div" style="width: 70px;">
                                <?= $form->field($models[$i], 'priority')->dropDownList($models[$i]->priorityList,[
                                    'options'=>$models[$i]->getPriorityList('options'),
                                    'class' => 'form-control priority',
                                    'id' => 'model_priority_'.$i,
                                    'name' => 'ModelOrdersItems['.$i.'][priority]',
                                    'indeks' => $i,
                                ]);
                                ?>
                            </div>
                            <div class="col-md-1 col-w-12" style="width: 80px;">
                                <?= $form->field($models[$i], 'season')->textInput([
                                    'id' => 'model_season_'.$i,
                                    'name' => 'ModelOrdersItems['.$i.'][season]',
                                    'class' => 'form-control model_season',
                                    'indeks' => $i,
                                ]);
                                ?>
                            </div>
                            <div class="col-md-1 col-w-12" style="width: 80px;">
                                <?= $form->field($models[$i], 'add_info')->textInput([
                                    'id' => 'model_add_info_'.$i,
                                    'name' => 'ModelOrdersItems['.$i.'][add_info]',
                                    'class' => 'form-control add_info',
                                    'indeks' => $i,
                                ]);
                                ?>
                            </div>
                            <div class="col-md-1 col-w-12 percentage_div" style="width: 30px;">
                                <?= $form->field($models[$i], 'percentage')->textInput([
                                    'id' => 'model_percentage_'.$i,
                                    'name' => 'ModelOrdersItems['.$i.'][percentage]',
                                    'indeks' => $i,
                                    'class' => 'number form-control percentage'
                                ]);
                                ?>
                            </div>
                            <div class="col-md-1 col-w-12 aksessuar baski <?=(!$models[$i]->modelsList->baski)?'hidden':''?>" data-hidden="<?=(!$models[$i]->modelsList->baski)?'hidden':''?>" style="width: 90px">
                                <?php $baski = $models[$i]->modelOrderItemsBaskis;?>
                                <div class="form-group field-modelordersitems-model_baski_id">
                                    <label><?= Yii::t('app', 'Baski') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control baski_count input_count" id="baski_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($baski)?>">
                                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#baski-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </div>
                                <div id="baski-modal_<?=$i?>" class="fade modal baski_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3><?php echo Yii::t('app','Baski')?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <table id="table_baski_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
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
                                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__baski_attachments">
                                                            <?=Yii::t('app','Rasmlar')?>
                                                        </th>
                                                        <th class="list-cell__button">
                                                            <div class="add_baski btn btn-success" data-row-index="<?=$i?>"><i class="glyphicon glyphicon-plus"></i></div>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($baski)){ foreach ($baski as $m => $key){
                                                        $baski = $key->modelVarBaski;?>
                                                        <tr class="multiple-input-list__item row_<?=$baski['id']?>" data-row-index="<?=$m?>">
                                                            <td class="list-cell__name">
                                                                <input type="text" class="baski_input form-control" disabled value="<?=$baski['name']?>">
                                                                <input type="hidden" class="baski_input form-control" name="ModelOrdersItems[<?=$i?>][baski][<?=$m?>][id]" value="<?=$baski['id']?>">
                                                            </td>
                                                            <td class="list-cell__desen_no">
                                                                <input type="text" class="baski_input form-control" disabled value="<?=$baski['desen_no']?>">
                                                            </td>
                                                            <td class="list-cell__code">
                                                                <input type="text" class="baski_input form-control" disabled value="<?=$baski['code']?>">
                                                            </td>
                                                            <td class="list-cell__brend">
                                                                <input type="text" class="baski_input form-control" disabled value="<?=$baski['brend']['name']?>">
                                                            </td>
                                                            <td class="list-cell__width">
                                                                <input type="text" class="baski_input form-control" disabled value="<?=$baski['width']?>">
                                                            </td>
                                                            <td class="list-cell__height">
                                                                <input type="text" class="baski_input form-control" disabled value="<?=$baski['height']?>">
                                                            </td>
                                                            <td class="list-cell__add_info">
                                                                <span><?=$baski['add_info']?></span>
                                                            </td>
                                                            <td class="list-cell__baski_attachments row">
                                                                <?php if(!empty($baski->imageOne)&&file_exists($baski->imageOne)){?>
                                                                    <img class="imgPreview" src="/web/<?=$baski->imageOne?>" style="width: 20px;min-height: 5vh;">
                                                                <?php }?>
                                                            </td>
                                                            <td class="list-cell__button">
                                                                <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTrBaski">
                                                                    <i class="glyphicon glyphicon-remove"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php }}?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 aksessuar rotatsion <?=(!$models[$i]->modelsList->baski_rotatsion)?'hidden':''?>" data-hidden="<?=(!$models[$i]->modelsList->baski_rotatsion)?'hidden':''?>" style="width: 90px">
                                <?php $rotatsion = $models[$i]->modelOrderItemsRotatsions;?>
                                <div class="form-group field-modelordersitems-model_rotatsion_id">
                                    <label><?= Yii::t('app', 'Model Var Rotatsion') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control rotatsion_count input_count" id="rotatsion_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($rotatsion)?>">
                                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#rotatsion-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </div>
                                <div id="rotatsion-modal_<?=$i?>" class="fade modal rotatsion_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3><?php echo Yii::t('app','Baski')?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <table id="table_rotatsion_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
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
                                                        <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__rotatsion_attachments">
                                                            <?=Yii::t('app','Rasmlar')?>
                                                        </th>
                                                        <th class="list-cell__button">
                                                            <div class="add_rotatsion btn btn-success" data-row-index="<?=$i?>"><i class="glyphicon glyphicon-plus"></i></div>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($rotatsion)){ foreach ($rotatsion as $m => $key){
                                                        $rotatsion = $key->modelVarRotatsion;?>
                                                        <tr class="multiple-input-list__item row_<?=$rotatsion['id']?>" data-row-index="<?=$m?>">
                                                            <td class="list-cell__name">
                                                                <input type="text" class="rotatsion_input form-control" disabled value="<?=$rotatsion['name']?>">
                                                                <input type="hidden" class="rotatsion_input form-control" name="ModelOrdersItems[<?=$i?>][rotatsion][<?=$m?>][id]" value="<?=$rotatsion['id']?>">
                                                            </td>
                                                            <td class="list-cell__desen_no">
                                                                <input type="text" class="rotatsion_input form-control" disabled value="<?=$rotatsion['desen_no']?>">
                                                            </td>
                                                            <td class="list-cell__code">
                                                                <input type="text" class="rotatsion_input form-control" disabled value="<?=$rotatsion['code']?>">
                                                            </td>
                                                            <td class="list-cell__brend">
                                                                <input type="text" class="rotatsion_input form-control" disabled value="<?=$rotatsion['brend']['name']?>">
                                                            </td>
                                                            <td class="list-cell__width">
                                                                <input type="text" class="rotatsion_input form-control" disabled value="<?=$rotatsion['width']?>">
                                                            </td>
                                                            <td class="list-cell__height">
                                                                <input type="text" class="rotatsion_input form-control" disabled value="<?=$rotatsion['height']?>">
                                                            </td>
                                                            <td class="list-cell__add_info">
                                                                <span><?=$rotatsion['add_info']?></span>
                                                            </td>
                                                            <td class="list-cell__rotatsion_attachments row">
                                                                <?php if(!empty($rotatsion->imageOne)&&file_exists($rotatsion->imageOne)){?>
                                                                    <img class="imgPreview" src="/web/<?=$rotatsion->imageOne?>" style="width: 20px;min-height: 5vh;">
                                                                <?php }?>
                                                            </td>
                                                            <td class="list-cell__button">
                                                                <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTrBaski">
                                                                    <i class="glyphicon glyphicon-remove"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php }}?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 aksessuar print <?=(!$models[$i]->modelsList->prints)?'hidden':''?>" data-hidden="<?=(!$models[$i]->modelsList->prints)?'hidden':''?>" style="width: 90px">
                                <?php $prints = $models[$i]->modelOrderItemsPrints;?>
                                <div class="form-group field-modelordersitems-model_print_id">
                                    <label><?= Yii::t('app', 'Print') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control print_count input_count" id="print_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($prints)?>">
                                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#print-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </div>
                                <div id="print-modal_<?=$i?>" class="fade modal print_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
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
                                                            <div class="add_prints btn btn-success" data-row-index="<?=$i?>"><i class="glyphicon glyphicon-plus"></i></div>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($prints)){ foreach ($prints as $m => $key){
                                                        $print = $key->modelVarPrints;?>
                                                        <tr class="multiple-input-list__item row_<?=$print['id']?>" data-row-index="<?=$m?>">
                                                            <td class="list-cell__name">
                                                                <input type="text" class="print_input form-control" disabled value="<?=$print['name']?>">
                                                                <input type="hidden" class="print_input form-control" name="ModelOrdersItems[<?=$i?>][print][<?=$m?>][id]" value="<?=$print['id']?>">
                                                            </td>
                                                            <td class="list-cell__desen_no">
                                                                <input type="text" class="print_input form-control" disabled value="<?=$print['desen_no']?>">
                                                            </td>
                                                            <td class="list-cell__code">
                                                                <input type="text" class="print_input form-control" disabled value="<?=$print['code']?>">
                                                            </td>
                                                            <td class="list-cell__brend">
                                                                <input type="text" class="print_input form-control" disabled value="<?=$print['brend']['name']?>">
                                                            </td>
                                                            <td class="list-cell__width">
                                                                <input type="text" class="print_input form-control" disabled value="<?=$print['width']?>">
                                                            </td>
                                                            <td class="list-cell__height">
                                                                <input type="text" class="print_input form-control" disabled value="<?=$print['height']?>">
                                                            </td>
                                                            <td class="list-cell__add_info">
                                                                <span><?=$print['add_info']?></span>
                                                            </td>
                                                            <td class="list-cell__prints_attachments row">
                                                                <?php if(!empty($print->imageOne)&&file_exists($print->imageOne)){?>
                                                                    <img class="imgPreview" src="/web/<?=$print->imageOne?>" style="width: 20px;min-height: 5vh;">
                                                                <?php }?>
                                                            </td>
                                                            <td class="list-cell__button">
                                                                <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">
                                                                    <i class="glyphicon glyphicon-remove"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php }}?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 aksessuar stone <?=(!$models[$i]->modelsList->stone)?'hidden':''?>" data-hidden="<?=(!$models[$i]->modelsList->stone)?'hidden':''?>" style="width: 90px">
                                <?php $stones = $models[$i]->modelOrderItemsStones;?>
                                <div class="form-group field-modelordersitems-model_stone_id">
                                    <label><?= Yii::t('app', 'Naqsh/tosh') ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control stone_count input_count" id="stone_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($stones)?>">
                                        <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#stone-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </div>
                                <div id="stone-modal_<?=$i?>" class="fade modal stone_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3><?php echo Yii::t('app','Naqsh/tosh')?></h3>
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
                                                            <div class="add_stones btn btn-success" data-row-index="<?=$i?>"><i class="glyphicon glyphicon-plus"></i></div>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($stones)){ foreach ($stones as $m => $key){
                                                        $stone = $key->modelVarStone;?>
                                                        <tr class="multiple-input-list__item row_<?=$stone['id']?>" data-row-index="<?=$m?>">
                                                            <td class="list-cell__name">
                                                                <input type="text" class="stone_input form-control" disabled value="<?=$stone['name']?>">
                                                                <input type="hidden" class="stone_input form-control" name="ModelOrdersItems[<?=$i?>][stone][<?=$m?>][id]" value="<?=$stone['id']?>">
                                                            </td>
                                                            <td class="list-cell__desen_no">
                                                                <input type="text" class="stone_input form-control" disabled value="<?=$stone['desen_no']?>">
                                                            </td>
                                                            <td class="list-cell__code">
                                                                <input type="text" class="stone_input form-control" disabled value="<?=$stone['code']?>">
                                                            </td>
                                                            <td class="list-cell__brend">
                                                                <input type="text" class="stone_input form-control" disabled value="<?=$stone['brend']['name']?>">
                                                            </td>
                                                            <td class="list-cell__width">
                                                                <input type="text" class="stone_input form-control" disabled value="<?=$stone['width']?>">
                                                            </td>
                                                            <td class="list-cell__height">
                                                                <input type="text" class="stone_input form-control" disabled value="<?=$stone['height']?>">
                                                            </td>
                                                            <td class="list-cell__add_info">
                                                                <span><?=$stone['add_info']?></span>
                                                            </td>
                                                            <td class="list-cell__stones_attachments row">
                                                                <?php if(!empty($stone->imageOne)&&file_exists($stone->imageOne)){?>
                                                                    <img class="imgPreview" src="/web/<?=$stone->imageOne?>" style="width: 20px;min-height: 5vh;">
                                                                <?php }?>
                                                            </td>
                                                            <td class="list-cell__button">
                                                                <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTrStone">
                                                                    <i class="glyphicon glyphicon-remove"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php }}?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 price" style="width: 70px">
                                <div class="form-group field-modelordersitems-model_price">
                                    <?= $form->field($models[$i], 'price')->textInput([
                                        'id' => 'model_price_'.$i,
                                        'name' => 'ModelOrdersItems['.$i.'][price]',
                                        'indeks' => $i,
                                        'class' => 'number form-control model_price customRequired'
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 pb_id" style="width: 65px">
                                <div class="form-group field-modelordersitems-model_pb_id">
                                    <?= $form->field($models[$i], 'pb_id')->dropDownList(PulBirligi::getPbList(),
                                        [
                                            'id' => 'model_pb_id_'.$i,
                                            'name' => 'ModelOrdersItems['.$i.'][pb_id]',
                                            'indeks' => $i,
                                            'class' => 'number form-control model_pb_id'
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-1 col-w-12 sizeParent" style="width: 90px">
                                <div class="form-group field-modelordersitems-model_size_collections_id">
                                    <label><?= Yii::t('app', 'Size Type') ?></label>
                                    <?php
                                    echo Select2::widget([
                                        'name' => 'ModelOrdersItems['.$i.'][size_collections_id]',
                                        'data' => $model->sizeCollectionList,
                                        'language' => 'ru',
                                        'options' => [
                                            'class' => 'rm_size',
                                            'prompt' => Yii::t('app', 'Check size type'),
                                            'indeks' => $i,
                                            'id' => 'model_orders_item-model-size_'.$i,
                                            'model_id' => $orderItemId,
                                        ],
                                        'value' => $models[$i]->modelOrdersItemsSizes[$i]->size->sizeType['id'],
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
                            <div class="sizeDiv" style="padding-right: 15px;padding-left: 15px;float: left;">
                                <?php foreach ($models[$i]->modelOrdersItemsSizes as $key){?>
                                    <div style="width: 49px;padding-right: 3px;float: left;">
                                        <div class="form-group field-model_orders_size_<?=$i?>">
                                            <label class="control-label text-center" style="width: 100%"
                                                   for="model_orders_size_<?=$key->size['id']?>_<?=$i?>"><?=$key->size['name']?>
                                            </label>
                                            <input type="text" id="model_orders_size_<?=$key->size['id']?>_<?=$i?>"
                                                   class="form-control number numberFormat" name="ModelOrdersItems[<?=$i?>][size][<?=$key->size['id']?>]"
                                                   indeks="<?=$i?>" style="padding-left: 2px;" value="<?=$key['count']?>">
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                        <input type="hidden" class="orderItemId" name="ModelOrdersItems[id]" value="<?=$orderItemId?>">
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php }?>
            <?php }?>
        </div>
        <br><br>
        <div class="pull-right">
            <button type="button" class="btn btn-success pull-right rmButton"><span class="fa fa-plus"></span></button>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Saqlash va chiqish'), ['class' => 'btn btn-success customHeight','id'=>'saveButtonFooter']) ?>
        </div>
    </div>
    <div id="acs-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Aksessuarlar')?></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="input-group" style="width: 300px;margin: 0 auto;">
                                <input type="text" class="form-control" id="search_acs" aria-describedby="search_button" style="height: 24px;">
                                <span class="input-group-addon btn btn-success" id="search_button_acs" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="list_acs flex-container">
                        <?php if (!empty($all_acs)){
                            foreach ($all_acs as $acs) {?>
                                <div class="acs_div" id="acs_div_<?=$acs['id']?>" data-id="<?=$acs['id']?>">
                                    <div class="media">
                                        <?php if(!empty($acs->imageOne)){?>
                                            <div class="media-left text-center">
                                                <img class="imgPreview" src="<?=$acs->imageOne?>" style="height: 9vh;max-width: 40px;">
                                                <!--<small class="pr_width"> </small>
                                            <small>x</small>
                                            <small class="pr_height"> </small>-->
                                            </div>
                                        <?php }?>
                                        <div class="media-body text-center">
                                            <h5 class="pr_artikul"><small><?=$acs['sku']?> </small></h5>
                                            <h5 class="media-heading pr_name"><?=$acs['name']?> </h5>
                                            <!--                                            <h5 class="pr_code"><small>--><?php //=$acs['code']?><!--</small></h5>-->
                                            <h5 class="pr_turi"><small><?=$acs->property['name']?> </small></h5>
                                            <h5 class="pr_unit"><small><?=$acs->unit['name']?> </small></h5>
                                            <h5 class="pr_barcod"><small><?=$acs['barcode']?> </small></h5>
                                            <h5 class="hidden pr_add_info"><?=$acs['add_info']?> </h5>
                                        </div>
                                    </div>
                                    <div class="text-center check_button">
                                        <span class="btn btn-success btn-xs check_acs" data-id="<?=$acs['id']?>" data-unit_id="<?=$acs['unit_id']?>"><?=$tanlash?></span>
                                    </div>
                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="prints-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Printlar')?></h3>
                    <?= Html::a('<span class="fa fa-plus"></span>', ['model-var-prints/create'],
                        ['class' => 'create-print btn btn-sm btn-success pull-right', 'id' => 'buttonAjax']) ?>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="input-group" style="width: 300px;margin: 0 auto;">
                                <input type="text" class="form-control" id="search_prints" aria-describedby="search_button" style="height: 24px;">
                                <span class="input-group-addon btn btn-success" id="search_button" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="list_prints flex-container">
                        <?php if (!empty($all_prints)){
                            foreach ($all_prints as $all_print) {?>
                                <div class="print_div" id="print_div_<?=$all_print['id']?>" data-id="<?=$all_print['id']?>">
                                    <div class="media">
                                        <div class="media-left text-center">
                                            <img class="imgPreview" src="/web/<?=$all_print->imageOne?>" style="height: 9vh;max-width: 40px;">
                                            <small class="pr_width"><?=$all_print['width']?></small>
                                            <small>x</small>
                                            <small class="pr_height"><?=$all_print['height']?></small>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading pr_name"><?=$all_print['name']?></h4>
                                            <h5 class="pr_desen"><small><?=$all_print['desen_no']?></small></h5>
                                            <h5 class="pr_code"><small><?=$all_print['code']?></small></h5>
                                            <h5 class="pr_brend"><small><?=$all_print->brend['name']?></small></h5>
                                            <h5 class="pr_musteri"><small><?=$all_print->musteri['name']?></small></h5>
                                            <h5 class="hidden pr_add_info"><?=$all_print['add_info']?></h5>
                                        </div>
                                    </div>
                                    <div class="text-center check_button">
                                        <span class="btn btn-success btn-xs check_print" data-id="<?=$all_print['id']?>"><?=$tanlash?></span>
                                    </div>
                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="model-var-prints-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Printlar')?></h3>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div id="stone-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Naqshlar')?></h3>
                    <?= Html::a('<span class="fa fa-plus"></span>', ['model-var-stone/create'],
                        ['class' => 'create-stone btn btn-sm btn-success pull-right', 'id' => 'buttonAjax']) ?>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="input-group" style="width: 300px;margin: 0 auto;">
                                <input type="text" class="form-control" id="search_stone" aria-describedby="search_button_stone" style="height: 24px;">
                                <span class="input-group-addon btn btn-success" id="search_button_stone" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="list_stone flex-container">
                        <?php if (!empty($all_stone)){
                            foreach ($all_stone as $stone) {?>
                                <div class="stone_div" id="stone_div_<?=$stone['id']?>" data-id="<?=$stone['id']?>">
                                    <div class="media">
                                        <div class="media-left text-center">
                                            <img class="imgPreview" src="/web/<?=$stone->imageOne?>" style="height: 9vh;max-width: 40px;">
                                            <small class="pr_width"><?=$stone['width']?></small>
                                            <small>x</small>
                                            <small class="pr_height"><?=$stone['height']?></small>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading pr_name"><?=$stone['name']?></h4>
                                            <h5 class="pr_desen"><small><?=$stone['desen_no']?></small></h5>
                                            <h5 class="pr_code"><small><?=$stone['code']?></small></h5>
                                            <h5 class="pr_brend"><small><?=$stone->brend['name']?></small></h5>
                                            <h5 class="pr_musteri"><small><?=$stone->musteri['name']?></small></h5>
                                            <h5 class="hidden pr_add_info"><?=$stone['add_info']?></h5>
                                        </div>
                                    </div>
                                    <div class="text-center check_button">
                                        <span class="btn btn-success btn-xs check_stone" data-id="<?=$stone['id']?>"><?=$tanlash?></span>
                                    </div>
                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="model-var-stones-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Naqshlar')?></h3>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div id="baski-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Naqshlar')?></h3>
                    <?= Html::a('<span class="fa fa-plus"></span>', ['model-var-baski/create'],
                        ['class' => 'create-baski btn btn-sm btn-success pull-right', 'id' => 'buttonAjax']) ?>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="input-group" style="width: 300px;margin: 0 auto;">
                                <input type="text" class="form-control" id="search_baski" aria-describedby="search_button_baski" style="height: 24px;">
                                <span class="input-group-addon btn btn-success" id="search_button_baski" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="list_baski flex-container">
                        <?php if (!empty($all_baski)){
                            foreach ($all_baski as $baski) {?>
                                <div class="baski_div" id="baski_div_<?=$baski['id']?>" data-id="<?=$baski['id']?>">
                                    <div class="media">
                                        <div class="media-left text-center">
                                            <img class="imgPreview" src="/web/<?=$baski->imageOne?>" style="height: 9vh;max-width: 40px;">
                                            <small class="pr_width"><?=$baski['width']?></small>
                                            <small>x</small>
                                            <small class="pr_height"><?=$baski['height']?></small>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading pr_name"><?=$baski['name']?></h4>
                                            <h5 class="pr_desen"><small><?=$baski['desen_no']?></small></h5>
                                            <h5 class="pr_code"><small><?=$baski['code']?></small></h5>
                                            <h5 class="pr_brend"><small><?=$baski->brend['name']?></small></h5>
                                            <h5 class="pr_musteri"><small><?=$baski->musteri['name']?></small></h5>
                                            <h5 class="hidden pr_add_info"><?=$baski['add_info']?></h5>
                                        </div>
                                    </div>
                                    <div class="text-center check_button">
                                        <span class="btn btn-success btn-xs check_baski" data-id="<?=$baski['id']?>"><?=$tanlash?></span>
                                    </div>
                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="model-var-baski-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Naqshlar')?></h3>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div id="rotatsion-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Naqshlar')?></h3>
                    <?= Html::a('<span class="fa fa-plus"></span>', ['model-var-rotatsion/create'],
                        ['class' => 'create-rotatsion btn btn-sm btn-success pull-right', 'id' => 'buttonAjax']) ?>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="input-group" style="width: 300px;margin: 0 auto;">
                                <input type="text" class="form-control" id="search_rotatsion" aria-describedby="search_button_baski" style="height: 24px;">
                                <span class="input-group-addon btn btn-success" id="search_button_rotatsion" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="list_rotatsion flex-container">
                        <?php if (!empty($all_rotatsion)){
                            foreach ($all_rotatsion as $baski) {?>
                                <div class="baski_div" id="baski_div_<?=$baski['id']?>" data-id="<?=$baski['id']?>">
                                    <div class="media">
                                        <div class="media-left text-center">
                                            <img class="imgPreview" src="/web/<?=$baski->imageOne?>" style="height: 9vh;max-width: 40px;">
                                            <small class="pr_width"><?=$baski['width']?></small>
                                            <small>x</small>
                                            <small class="pr_height"><?=$baski['height']?></small>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading pr_name"><?=$baski['name']?></h4>
                                            <h5 class="pr_desen"><small><?=$baski['desen_no']?></small></h5>
                                            <h5 class="pr_code"><small><?=$baski['code']?></small></h5>
                                            <h5 class="pr_brend"><small><?=$baski->brend['name']?></small></h5>
                                            <h5 class="pr_musteri"><small><?=$baski->musteri['name']?></small></h5>
                                            <h5 class="hidden pr_add_info"><?=$baski['add_info']?></h5>
                                        </div>
                                    </div>
                                    <div class="text-center check_button">
                                        <span class="btn btn-success btn-xs check_baski" data-id="<?=$baski['id']?>"><?=$tanlash?></span>
                                    </div>
                                </div>
                            <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="model-var-rotatsion-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Model Var Rotatsion')?></h3>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    <div class="hidden">
        <?=Html::dropDownList('size', '', \app\modules\base\models\ModelOrders::getSizeCollectionList(),['id'=>'size_collection_list','prompt'=>$tanlang])?>
        <?=Html::dropDownList('pb_id', '', PulBirligi::getPbList(),['id'=>'pb_id_list'])?>
        <?=Html::dropDownList('brend_id', '', \app\modules\base\models\ModelsList::getAllBrend(),['id'=>'brend_id_list','prompt'=>$tanlang])?>
        <?=Html::dropDownList('baski_id', '', $model->baskiList,['id'=>'baski_id_list','prompt'=>$tanlang])?>
    </div>
<?php
$ajax = Url::to(['ajax']);
$modelTanlang = Yii::t('app','Model tanlang');
$checkSize = Yii::t('app','Check size type');
$select = Yii::t('app', 'Select');
$createVariationUrl = Yii::$app->urlManager->createUrl('base/models-variations/create');
$listVariationUrl = Yii::$app->urlManager->createUrl('base/models-variations/list');
$js = <<< JS
var model_list_id_select2 = {
	"themeCss": ".select2-container--krajee",
	"sizeCss": "",
	"doReset": true,
	"doToggle": false,
	"doOrder": false
};
window.model_list_id_new_select2 = {
	"allowClear": true,
	"minimumInputLength": 3,
	"ajax": {
		"url": "{$urlRemain}",
		"dataType": "json",
		"data": function(params) {
			return {
				q: params.term
			};

		},
		"cache": true
	},
	"escapeMarkup": function(markup) {
		return markup;
	},
	"templateResult": function(data) {
		return data.text;
	},
	"templateSelection": function(data) {
		return data.text;
	},
	"theme": "krajee",
	"width": "100%",
	"placeholder": "{$modelTanlang}",
	"language": "ru"
};

window.model_var_id_select2 = {
	"allowClear": true,
	"escapeMarkup": function(markup) {
		return markup;
	},
	"theme": "krajee",
	"width": "100%",
	"language": "ru"
};

window.model_load_date_kvdatepicker = {
	"autoclose": true,
	"format": "dd.mm.yyyy",
	"language": "ru"
};

window.model_size_collections_id_select2 = {
	"allowClear": true,
	"escapeMarkup": function(markup) {
		return markup;
	},
	"theme": "krajee",
	"width": "100%",
	"placeholder": "{$checkSize}",
	"language": "ru"
};

window.aksessuar = {
    "allowClear":true,
    "escapeMarkup":function (markup) { 
        return markup; 
    },
    "theme":"krajee",
    "width":"100%",
    "placeholder":"{$select}",
    "language":"ru"};
JS;
$this->registerJs($js);
$shart = Yii::t('app', "To'ldirilish majburiy");
$model_name = Yii::t('app', "Model");
$variant = Yii::t('app', "Variant");
$qidirish_uchun = Yii::t('app', "Qidirish uchun shu yerga yozing");
$aksessuarlar = Yii::t('app', "Aksessuarlar");
$toquv_aksessuarlar = Yii::t('app', "To‘quv aksessuarlar");
$toquv_aksessuar = Yii::t('app', "To‘quv aksessuar");
$aksessuar = Yii::t('app', "Aksessuar");
$turi = Yii::t('app', "Turi");
$miqdori = Yii::t('app', "Miqdori");
$ulchov_birligi = Yii::t('app', "O'lchov birligi");
$barkod = Yii::t('app', "Barkod");
$izoh = Yii::t('app', "Izoh");
$rasmlar = Yii::t('app', "Rasmlar");
$yuklama_sanasi = Yii::t('app', "Yuklama sanasi");
$brend_name = Yii::t('app', "Brend");
$muhimliligi = Yii::t('app', "Muhimliligi");
$mavsum = Yii::t('app', "Mavsum");
$baski_name = Yii::t('app', "Baski");
$baskilar = Yii::t('app', "Baskilar");
$rotatsion_name = Yii::t('app', "Model Var Rotatsion");
$rotatsionlar = Yii::t('app', "Model Var Rotatsion");
$printlar = Yii::t('app', "Printlar");
$print_name = Yii::t('app', "Print");
$desen_no = Yii::t('app', "Desen No");
$kodi = Yii::t('app', "Kodi");
$width = Yii::t('app', "Width");
$height = addslashes(Yii::t('app', "Height"));
$stone_name = Yii::t('app', "Naqsh/Tosh");
$naqshlar = Yii::t('app', "Naqshlar");
$nomi = Yii::t('app', "Nomi");
$artikul_kodi = Yii::t('app', "Artikul / Kodi");
$narx = Yii::t('app', "Narx");
$confirm_delete = Yii::t('app', "Bu qatorni o`chirmoqchimisiz?");
$pul_birligi = Yii::t('app', "Pul birligi");
$url_toquv_acs = \yii\helpers\Url::to('toquv-acs');
$customScript = <<< JS
    $("body").delegate(".rm_order",'change',function(e){
        let t = $(this);
        t.next().find("img").hide();
        let model_list_id = t.val();
        if(t.val()!=0){
            t.next().find(".select2-selection").css("border-color","#d2d6de");
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
            $(this).parent().tooltip('destroy');
        }
        let parent = t.parents('.rmParent');
        parent.find('.baski').find('table tbody').html('');
        parent.find('.rotatsion').find('table tbody').html('');
        parent.find('.print').find('table tbody').html('');
        parent.find('.stone').find('table tbody').html('');
        parent.find('.baski_count').val('');
        parent.find('.rotatsion_count').val('');
        parent.find('.print_count').val('');
        parent.find('.stone_count').val('');
        let num = parent.attr('data-row-index');
        let modelVarModal = $('#var-modal_'+num);
        modelVarModal.find('.modal-header').html('');
        parent.find('.model_var_id').val('').trigger('change');
        parent.find('.var_name').html('');
        let add_var = parent.find('.add_var');
        add_var.attr('data-status','load').attr('data-list-id',t.val()).removeAttr('data-id');
        let variation = modelVarModal.find('.modal-body');
        if(t.val()!=0){
            modelVarModal.modal('show');
            variation.load('$listVariationUrl?id='+t.val(),{'list':'orders'}, function() {
            });
            $.ajax({
                url: '{$url_toquv_acs}?id='+t.val(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: 'id'
                },
                type: "POST",
                success: function (response) {
                    let tbody = $('#table_toquv_acs_'+num).find('tbody');
                    tbody.html('');
                    let toquv_acs_list = response.toquv;
                    if(toquv_acs_list){
                        parent.find('.toquv_acs_count').val(toquv_acs_list.length);
                        toquv_acs_list.map(function(index,key) {
                            let select = '<select id="toquv_acs_select_'+num+'_'+key+'" class="form-control select_list" style="width:100%" name="ModelOrdersItems['+num+'][toquv_acs]['+key+'][id]"></select>';
                            let tr = '<tr>' +
                                     '    <input type="hidden" value="'+toquv_acs_list[key].id+'" name="ModelOrdersItems['+num+'][toquv_acs]['+key+'][id]"><td><span class="form-control toquv_acs_code" disabled="">' +
                                                toquv_acs_list[key].code +
                                     '    </span></td>' +
                                     '    <td><input type="text" class="form-control toquv_acs_code" name="ModelOrdersItems['+num+'][toquv_acs]['+key+'][name]" readonly="true" value="'+toquv_acs_list[key].name+'">' +
                                                '</td>' +
                                     '    <td><span class="form-control" disabled="">' +
                                                toquv_acs_list[key].type +
                                     '    </span></td>' +
                                     '    <td><input type="text" class="form-control toquv_acs_quantity"></td>' +
                                     '</tr>';
                            tbody.append(tr);
                        });
                    }
                    
                    /*materials.map(function(index,key){
                        let select_list = $('#toquv_acs_select_'+num+'_'+key);
                        materials[index].list.map(function(list,val) {
                            let _true = (list.id==index)?true:false;
                            let newOption = new Option(list.name, list.id, _true, _true);
                            newOption.setAttribute("data-code",list.code);
                            select_list.append(newOption);
                        });
                    });*/
                }
           });
        }else{
            variation.html('');
        }
    });
    $('body').delegate('.select_list', 'change', function(e){
        let t = $(this);
        t.parents('tr').find('.toquv_acs_code').html(t.find('option:selected').attr('data-code'));
    });
    $("body").delegate(".add_var",'click',function(e){
        let t = $(this);
        let parent = t.parents('.rmParent');
        let modal = parent.find('.var-modal');
        $(modal).modal('show');
        if(t.attr('data-status')==='no_load'){
            let model_list_id = t.attr('data-list-id');
            let num = parent.attr('data-row-index');
            let modelVarModal = $('#var-modal_'+num);
            let variation = modelVarModal.find('.modal-body');
            variation.load('$listVariationUrl?id='+model_list_id,{'var_id':t.attr('data-id')}, function() {
                t.attr('data-status','load')
            });
        }
    });
    jQuery('body').delegate('.orderItemForm select','change', function(e) { 
        let t = $(this);
        let val = t.val();
        t.find('option').removeAttr('selected');
        if(val!=0&&val!=''){
            t.find('option[value='+val+']').attr('selected',true);
        }
    });
    jQuery('body').delegate('.rm_size','change', function(e) { 
        let t = $(this);
        let indeks = t.attr('indeks');
        let val_id = t.val();
        let model_id = t.attr('model_id');
        let parent = t.parents('.sizeParent');
        $.ajax({
            url: '{$url_size}?id=' + indeks + '&num=' + val_id + '&model_id=' +model_id,
            success: function(response) {
                let div = '';
                if(response.status == 1){
                    let sizeList = response.size;
                    sizeList.map(function(index,key) {
                        div += '<div style="width: 49px;padding-right: 3px;float: left;">'+
                                    '<div class="form-group field-model_orders_size_'+indeks+'">'+
                                        '<label class="control-label text-center" style="width: 200%" for="model_orders_size_'+index.id+'_'+indeks+'"> '+index.name+' </label>'+
                                        '<input type="text" id="model_orders_size_'+index.id+'_'+indeks+'" class="form-control number numberFormat" name="ModelOrdersItems['+indeks+'][size]['+index.id+']" indeks="'+indeks+'" style="padding-left: 2px;">'+
                                        '<div class="help-block"></div>'+
                                    '</div>'+
                                '</div>';
                    });
                }
                parent.next().html(div);
            }
        });
    });
    $('body').delegate('.stone_count,.print_count,.input_count', 'focus', function(e){
        $(this).blur();
    });
    $(".rmButton").on('click',function(e){
        e.preventDefault();
        let parent = $(this).parents(".model-orders-form").find(".rmParentDiv");
        let lastNum = parent.find('.orderItemForm').last().attr('data-row-index');
        let num = (typeof lastNum !== 'undefined') ? 1*lastNum+1 : 0;
        let new_content = '<form id="order-form-'+num+'" data-row-index="'+num+'" class="orderItemForm" action="{$urlSaveItem}" method="post">' +
         '<input type="hidden" name="_csrf" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
    '<div class="document-items row parentRow">' +
    '  <div class="pull-right removeButtonParent"><button type="button" class="btn btn-success saveItem">{$saqlash}</button>&nbsp;<button type="button" class="btn btn-primary copyButton"><span class="fa fa-copy"></span></button>&nbsp;<button type="button" class="btn btn-danger removeButton"><span class="fa fa-trash"></span></button></div>' +
    '  <div class="rmParent" data-row-index="'+num+'">' +
    '    <div class="col-md-3 rmOrderId">' +
    '        <div class="form-group field-model_orders_item-model-list_'+num+' required">' +
    '            <label class="control-label" for="model_orders_item-model-list_'+num+'">{$model_name}</label>' +
    '            <select id="model_orders_item-model-list_'+num+'" class="rm_order form-control" name="ModelOrdersItems['+num+'][models_list_id]" indeks="'+num+'">' +
    '                <option value="">{$modelTanlang}</option>' +
    '            </select>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '        <div class="rmSpan"></div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12">' +
    '        <div class="form-group field-modelordersitems-model_var_id">' +
    '            <label>{$variant}</label>' +
    '            <div class="input-group">' +
    '                <span type="text" class="form-control var_name" id="var_'+num+'" aria-describedby="var-addon_'+num+'" disabled=""></span>' +
    '                <div class="form-group field-model-var-'+num+' required">' +
    '                    <input type="hidden" id="model-var-'+num+'" class="model_var_id" name="ModelOrdersItems['+num+'][model_var_id]">' +
    '                    <div class="help-block"></div>' +
    '                </div> <span class="input-group-addon btn btn-success add_var" id="var-addon_'+num+'" style="padding: 3px 6px;" data-status="no_load" data-list-id="" data-id=""><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="var-modal_'+num+'" class="fade modal var-modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <p class="modal-top" style="padding-right: 10px;padding-top: 10px;text-align: right;">' +
    '                        <button type="button" class="btn btn-success btn-lg form-variation" data-url="{$create_variation_url}" style="padding: 3px 6px;font-size: 14px;">' +
    '                            <i class="fa fa-plus"></i>' +
    '                        </button> &nbsp;&nbsp;' +
    '                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>' +
    '                    </p>' +
    '                    <div class="modal-header">' +
    '                    </div>' +
    '                    <div class="search_div">' +
    '                        <input type="text" class="form-control search_var" placeholder="{$qidirish_uchun}">' +
    '                    </div>' +
    '                    <div class="modal-body"></div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar acs" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_acs_id">' +
    '            <label>{$aksessuarlar}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control acs_count input_count" id="acs_'+num+'" aria-describedby="basic-addon_'+num+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#acs-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="acs-modal_'+num+'" class="fade modal acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$aksessuarlar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_acs_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul">{$artikul_kodi}</th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__turi">' +
    '                                    {$turi} </th>' +
    '                                <th class="list-cell__qty">' +
    '                                    {$miqdori} </th>' +
    '                                <th class="list-cell__unit_id">' +
    "                                    {$ulchov_birligi} </th>" +
    '                                <th class="list-cell__barcod">' +
    '                                    {$barkod} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__acs_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_acs btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar toquv_acs" style="width: 90px">' +
    '      <div class="form-group field-modelordersitems-model_toquv_acs_id">' +
    '           <label>{$toquv_aksessuar}</label>' +
    '           <div class="input-group">' +
    '               <input type="text" class="form-control toquv_acs_count input_count" id="toquv_acs_'+num+'" aria-describedby="basic-addon_'+num+'" value="0">' +
    '               <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#toquv_acs-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '           </div>' +
    '       </div>' +
    '       <div id="toquv_acs-modal_'+num+'" class="fade modal toquv_acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '           <div class="modal-dialog modal-lg">' +
    '               <div class="modal-content">' +
    '                   <div class="modal-header">' +
    '                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                       <h3>{$toquv_aksessuarlar}</h3>' +
    '                   </div>' +
    '                   <div class="modal-body">' +
    '                       <table id="table_toquv_acs_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                          <thead>' +
    '                           <tr>' +
    '                              <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul">{$artikul_kodi}</th>' +
    '                              <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$aksessuar}</th>' +
    '                              <th class="list-cell__turi">{$turi}</th>' +
        '                              <th class="list-cell__miqdor">{$miqdori}</th>' +
    '                           </tr>' +
    '                          </thead>' +
    '                          <tbody>' +
    '                          </tbody>' +
    '                      </table>' +
    '                  </div>' +
    '              </div>' +
    '          </div>' +
    '      </div>' +
    '    </div>'+
    '    <div class="col-md-1 col-w-12" style="width: 85px;">' +
    '        <div class="form-group field-model_orders_item-load_date_'+num+'">' +
    '            <label class="control-label" for="model_orders_item-load_date_'+num+'">{$yuklama_sanasi}</label>' +
    '            <div id="model_orders_item-load_date_'+num+'-kvdate" class="input-group  date"><span class="input-group-addon kv-date-picker"><i class="glyphicon glyphicon-calendar kv-dp-icon"></i></span>' +
    '                <input type="text" id="model_orders_item-load_date_'+num+'" class="customRequired form-control load_date" name="ModelOrdersItems['+num+'][load_date]">' +
    '            </div>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12" style="width: 70px;">' +
    '        <div class="form-group field-model_brend_id_'+num+'">' +
    '            <label class="control-label" for="model_brend_id_'+num+'">{$brend_name}</label>' +
    '            <select id="model_brend_id_'+num+'" class="form-control brend_id select_list customRequired" name="ModelOrdersItems['+num+'][brend_id]" indeks="'+num+'">' +
                    $("#brend_id_list").html() +
    '            </select>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 priority_div" style="width: 70px;">' +
    '        <div class="form-group field-model_priority_'+num+'">' +
    '            <label class="control-label" for="model_priority_'+num+'">{$muhimliligi}</label>' +
    '            <select id="model_priority_'+num+'" class="form-control priority" name="ModelOrdersItems['+num+'][priority]" indeks="'+num+'">' +
    '                <option value="1" style="background:#ccc;color:white;padding:2px;font-weight:bold">Muhim emas</option>' +
    '                <option value="2" style="background:green;color:white;padding:2px;font-weight:bold">Normal</option>' +
    '                <option value="3" style="background:#CC7722;color:white;padding:2px;font-weight:bold">Muhim</option>' +
    '                <option value="4" style="background:red;color:white;padding:2px;font-weight:bold">O\'ta muhim</option>' +
    '            </select>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12" style="width: 80px;">' +
    '        <div class="form-group field-model_season_'+num+'">' +
    '            <label class="control-label" for="model_season_'+num+'">{$mavsum}</label>' +
    '            <input type="text" id="model_season_'+num+'" class="form-control model_season" name="ModelOrdersItems['+num+'][season]" indeks="'+num+'">' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12" style="width: 80px;">' +
    '        <div class="form-group field-model_add_info_'+num+'">' +
    '            <label class="control-label" for="model_add_info_'+num+'">{$izoh}</label>' +
    '            <input type="text" id="model_add_info_'+num+'" class="form-control add_info" name="ModelOrdersItems['+num+'][add_info]" indeks="'+num+'">' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 percentage_div" style="width: 30px;">' +
    '        <div class="form-group field-model_percentage_'+num+'">' +
    '            <label class="control-label" for="model_percentage_'+num+'">%</label>' +
    '            <input type="text" id="model_percentage_'+num+'" class="number form-control percentage" name="ModelOrdersItems['+num+'][percentage]" indeks="'+num+'">' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar baski hidden" data-hidden="hidden" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_baski_id">' +
    '            <label>{$baski_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control baski_count input_count" id="baski_'+num+'" aria-describedby="basic-addon_'+num+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#baski-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="baski-modal_'+num+'" class="fade modal baski_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$baskilar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_baski_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__baski_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_baski btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar rotatsion hidden" data-hidden="hidden" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_rotatsion_id">' +
    '            <label>{$rotatsion_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control rotatsion_count input_count" id="rotatsion_'+num+'" aria-describedby="basic-addon_'+num+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#rotatsion-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="rotatsion-modal_'+num+'" class="fade modal rotatsion_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$rotatsionlar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_rotatsion_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__rotatsion_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_rotatsion btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar print hidden" data-hidden="hidden" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_print_id">' +
    '            <label>{$print_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control print_count input_count" id="print_'+num+'" aria-describedby="basic-addon_'+num+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#print-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="print-modal_'+num+'" class="fade modal print_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$printlar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__prints_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_prints btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar stone hidden" data-hidden="hidden" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_stone_id">' +
    '            <label>{$stone_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control stone_count input_count" id="stone_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+num+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#stone-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="stone-modal_'+num+'" class="fade modal stone_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$stone_name}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_stone_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__stones_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_stones btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 price" style="width: 70px">' +
    '        <div class="form-group field-modelordersitems-model_price">' +
    '            <div class="form-group field-model_price_'+num+'">' +
    '                <label class="control-label" for="model_price_'+num+'">{$narx}</label>' +
    '                <input type="text" id="model_price_'+num+'" class="number form-control model_price customRequired" name="ModelOrdersItems['+num+'][price]" indeks="'+num+'">' +
    '                <div class="help-block"></div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 pb_id" style="width: 65px">' +
    '        <div class="form-group field-modelordersitems-model_pb_id">' +
    '            <div class="form-group field-model_pb_id_'+num+'">' +
    '                <label class="control-label" for="model_pb_id_'+num+'">{$pul_birligi}</label>' +
    '                <select id="model_pb_id_'+num+'" class="number form-control model_pb_id" name="ModelOrdersItems['+num+'][pb_id]" indeks="'+num+'">' +
                        $("#pb_id_list").html() +
    '                </select>' +
    '                <div class="help-block"></div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 sizeParent" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_size_collections_id">' +
    "            <label>{$ulcham}</label>" +
    '            <select id="model_orders_item-model-size_'+num+'" class="rm_size form-control" name="ModelOrdersItems['+num+'][size_collections_id]" indeks="'+num+'">' +
                     $('#size_collection_list').html() +
    '            </select>' +
    '        </div>' +
    '    </div>' +
    '    <div class="sizeDiv" style="padding-right: 15px;padding-left: 15px;float: left;">' +
    '    </div>' +
    '  </div>' +
    '</div>' +
    '<input type="hidden" class="orderItemId" name="ModelOrdersItems[id]">' +
    '</form>';
            parent.append(new_content);
            if (jQuery('#model_orders_item-model-list_'+num).data('select2')) {
                jQuery('#model_orders_item-model-list_'+num).select2('destroy');
            }
            jQuery.when(jQuery('#model_orders_item-model-list_'+num).select2(model_list_id_new_select2)).done(initS2Loading('model_orders_item-model-list_'+num, 'model_list_id_select2'));
            
            jQuery('#model_orders_item-model-list_'+num).on('select2:select', 
                function(e){
                    if(e.params.data){
                    let t = $(this);
                    let parent = t.parents('.rmParent');
                    if(e.params.data.baski==0){
                        parent.find('.baski').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.baski_count').removeClass('customRequired');
                    }else{
                        parent.find('.baski').removeClass('hidden').attr('data-hidden','');
                        parent.find('.baski_count').addClass('customRequired');
                    }
                    if(e.params.data.rotatsion==0){
                        parent.find('.rotatsion').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.rotatsion_count').removeClass('customRequired');
                    }else{
                        parent.find('.rotatsion').removeClass('hidden').attr('data-hidden','');
                        parent.find('.rotatsion_count').addClass('customRequired');
                    }
                    if(e.params.data.prints==0){
                        parent.find('.print').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.print_count').removeClass('customRequired');
                    }else{
                        parent.find('.print').removeClass('hidden').attr('data-hidden','');
                        parent.find('.print_count').addClass('customRequired');
                    }
                    if(e.params.data.stone==0){
                        parent.find('.stone').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.stone_count').removeClass('customRequired');
                    }else{
                        parent.find('.stone').removeClass('hidden').attr('data-hidden','');
                        parent.find('.stone_count').addClass('customRequired');
                    }
                    if(e.params.data.brend_id){
                        parent.find('.brend_id').val(e.params.data.brend_id).trigger('change');
                    }
                    if(e.params.data.acs){
                        let indeks = parent.attr('data-row-index');
                        let table = $('#table_acs_'+indeks);
                        let acs = $('#acs_'+indeks);
                        let dataList = e.params.data.acs;
                        table.find('tbody').html('');
                        let count_acs = 0;
                        Object.keys(dataList).map(function(index,key){
                            let pr_id = index;
                            let pr_name = dataList[index].name;
                            let pr_artikul = dataList[index].artikul;
                            let pr_turi = dataList[index].turi;
                            let pr_qty = dataList[index].qty ?? 0;
                            let pr_unit = dataList[index].unit;
                            let pr_unit_id = dataList[index].unit_id;
                            let pr_barcod = dataList[index].barcod;
                            let pr_add_info = dataList[index].add_info;
                            let pr_image = dataList[index].image;
                            let check_row = table.find('.row_'+pr_id);
                            let tbody = table.find('tbody');
                            let last_tr = tbody.find('tr').last();
                            let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
                            let image = (pr_image!=null)?'<img class="imgPreview pr_image" src="'+pr_image+'">':'';
                            if(check_row.length==0){
                                table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                                    '                                <td class="list-cell__artikul">' +
                                    '                                    <span type="text" class="form-control" disabled="">' +
                                                                            pr_artikul +
                                                                         '</span>'+
                                    '                                </td>' +
                                    '                                <td class="list-cell__name">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_name+'</span>' +
                                    '                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][id]" value="'+pr_id+'"><input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][unit_id]" value="'+pr_unit_id+'">' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__turi">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_turi+'</span>' +
                                    '    <td class="list-cell__qty">' +
                                    '        <input type="text" class="acs_input form-control number" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][qty]" value="'+pr_qty+'">' +
                                    '    </td>' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__unit_id">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_unit+'</span>' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__barcod">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_barcod+'</span>' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__add_info">' +
'                                   <input type="text" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][add_info]" value="'+pr_add_info+'">' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__acs_image">' +
                                                                         image+
                                    '                                </td>' +
                                    '                                <td class="list-cell__button">' +
                                    '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                                    '                                        <i class="glyphicon glyphicon-remove"></i>' +
                                    '                                    </div>' +
                                    '                                </td>' +
                                    '                            </tr>');
                            }
                            count_acs++;
                        });
                        acs.val(count_acs);
                    }
                    if(e.params.data.toquv_acs){
                        let indeks = parent.attr('data-row-index');
                        let table = $('#table_toquv_acs_'+indeks);
                        let acs = $('#toquv_acs_'+indeks);
                        let dataList = e.params.data.toquv_acs;
                        table.find('tbody').html('');
                        let count_toquv_acs = 0;
                        Object.keys(dataList).map(function(index,key){
                            let pr_id = index;
                            let pr_name = dataList[index].name;
                            let pr_artikul = dataList[index].artikul;
                            let pr_turi = dataList[index].turi;
                            let pr_qty = dataList[index].qty ?? 0;
                            let check_row = table.find('.row_'+pr_id);
                            let tbody = table.find('tbody');
                            let last_tr = tbody.find('tr').last();
                            let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
                            if(check_row.length==0){
                                table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                                    '                                <td class="list-cell__artikul">' +
                                    '                                    <span type="text" class="form-control" disabled="">' +
                                                                            pr_artikul +
                                                                         '</span>'+
                                    '                                </td>' +
                                    '                                <td class="list-cell__name">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_name+'</span>' +
                                    '                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][id]" value="'+pr_id+'">' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__turi">' +
                                    '                                    <span type="text" class="toquv_acs_input form-control" disabled="">'+pr_turi+'</span>' +
                                    '    <td class="list-cell__qty">' +
                                    '        <input type="text" class="acs_input form-control number" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][qty]" value="'+pr_qty+'">' +
                                    '    </td>' +
                                    '                                </td>' +
                                    '                            </tr>');
                            }
                            count_toquv_acs++;
                        });
                        acs.val(count_toquv_acs);
                    }
                }
            });
            /*if (jQuery('#modelVar'+num).data('select2')) {
                jQuery('#modelVar'+num).select2('destroy');
            }
            jQuery.when(jQuery('#modelVar'+num).select2(model_var_id_select2)).done(initS2Loading('modelVar'+num, 'model_list_id_select2'));*/
            
            jQuery.fn.kvDatepicker.dates = {};
            if (jQuery('#model_orders_item-load_date_'+num).data('kvDatepicker')) {
                jQuery('#model_orders_item-load_date_'+num).kvDatepicker('destroy');
            }
            jQuery('#model_orders_item-load_date_'+num+'-kvdate').kvDatepicker(model_load_date_kvdatepicker);
            
            initDPAddon('model_orders_item-load_date_'+num);
            if (jQuery('#model_orders_item-model-size_'+num).data('select2')) {
                jQuery('#model_orders_item-model-size_'+num).select2('destroy');
            }
            jQuery.when(jQuery('#model_orders_item-model-size_'+num).select2(model_size_collections_id_select2)).done(initS2Loading('model_orders_item-model-size_'+num, 'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-baski_'+num).data('select2')) {jQuery('#model_orders_item-model-baski_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-baski_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-baski_'+num,'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-rotatsion_'+num).data('select2')) {jQuery('#model_orders_item-model-rotatsion_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-rotatsion_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-rotatsion_'+num,'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-prints_'+num).data('select2')) {jQuery('#model_orders_item-model-prints_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-prints_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-prints_'+num,'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-stone_'+num).data('select2')) {jQuery('#model_orders_item-model-stone_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-stone_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-stone_'+num,'model_list_id_select2'));
    });
    jQuery('body').delegate('.removeButton','click', function(e) { 
        e.preventDefault();
        if(confirm("{$confirm_delete}")){
            let t = $(this);
            let id = t.attr('data-id');
            let form = t.parents('.orderItemForm');
            if(id){
               $.ajax({
                    url: '{$urlDeleteItem}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    type: "POST",
                    success: function (response) {
                        if(response.status == 1){
                            call_pnotify('success',response.message);
                            form.remove();
                        }else{
                            call_pnotify('fail',response.message);
                        }
                    }
               }); 
            }else{
                form.remove();
            }
        }
    });
    $("body").delegate(".model_var_id","change",function(){
        if($(this).val()!=0){
            $(this).parents('.input-group').css("border-color","#d2d6de");
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
            try{
                $(this).parent().tooltip('destroy');
            }catch(e){
                console.log(e);
            }
        }
    });
    $("body").delegate(".numberFormat","change",function(){
        if(/^([0-9]+)?\s*$/.test($(this).val())){
            $(this).css("border-color","#d2d6de");
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
            try{
                $(this).parent().tooltip('destroy');
            }catch(e){
                console.log(e);
            }
        }
    });
    $("body").delegate(".customRequired","change",function(){
        if($(this).val()!=0){
            $(this).parents('.input-group').css("border-color","#d2d6de");
            $(this).css("border-color","#d2d6de");
            $(this).parents('.form-group').removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
            try{
                $(this).parent().tooltip('destroy');
            }catch(e){
                console.log(e);
            }
        }
    });
    $("#saveButtonFooter").on('click',function(e){
        $("#saveButton").click();
    });
    $("#saveButton").on('click',function(e){
        let modelVar = $(".model_var_id");
        $(modelVar).each(function (index, value){
            if($(this).val()==0||$(this).val()==null){
                e.preventDefault();
                $(this).parents('.input-group').css("border","1px solid red");
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
                try{
                    $(value).parent().tooltip({
                        delay: { show: 500, hide: 100 }
                    }).tooltip("show");
                }catch(e){
                    console.log(e);
                }
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
        let select = $(".rm_order");
        $(select).each(function (index, value){
            if($(this).val()==0||$(this).val()==null){
                e.preventDefault();
                $(this).next().find(".select2-selection").css("border-color","red");
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).next().attr('title',"{$required}").attr("data-toggle","tooltip");
                try{
                    $(value).next().tooltip('show');
                }catch(e){
                    console.log(e);
                }
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
        let required = $(".customRequired");
        $(required).each(function (index, value){
            if($(this).val()==0||$(this).val()==null){
                e.preventDefault();
                $(value).parents('.form-group').addClass('has-error').removeClass('has-success');
                $(value).attr('title',"{$required}").attr("data-toggle","tooltip");
                try{
                    $(value).tooltip('show');
                }catch(e){
                    console.log(e);
                }
                check = false;
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
        let numberFormat = $(".numberFormat");
        $(numberFormat).each(function (index, value){
            if(!/^([0-9]+)?\s*$/.test($(this).val())){
                e.preventDefault();
                $(this).css("border-color","red");
                $(this).focus();
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
                try{
                    $(value).parent().tooltip('show');
                }catch(e){
                    console.log(e);
                }
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
    });
    $('body').delegate('.sizeDiv input', 'change', function(e){
        $(this).attr('value',$(this).val());
    });
    $(document).on('shown.bs.tooltip', function (e) {
      setTimeout(function () {
        $(e.target).tooltip('hide');
      }, 3000);
    });
    let input = $('form.orderItemForm').find(':input');
    $('body').delegate(':input', 'change', function(e){
        $(this).addClass('changed');
        $(this).parents('.document-items').addClass('changedItem').removeClass('savedItem');
        $(this).parents('.orderItemForm').addClass('changedItem').removeClass('savedItem');
    });
    $("body").delegate(".saveItem",'click',function(e){
        let form = $(this).parents('form.orderItemForm');
        let modelVar = form.find(".model_var_id");
        var data = form.serialize();
        var url = form.attr("action");
        var check = true;
        $(modelVar).each(function (index, value){
            if($(this).val()==0||$(this).val()==null){
                e.preventDefault();
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
                $(value).parent().tooltip({
                    delay: { show: 500, hide: 100 }
                }).tooltip("show");
                check = false;
            }
        });
        let select = form.find(".rm_order");
        $(select).each(function (index, value){
            if($(this).val()==0||$(this).val()==null){
                e.preventDefault();
                $(this).next().find(".select2-selection").css("border-color","red");
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).next().attr('title',"{$required}").attr("data-toggle","tooltip");
                $(value).next().tooltip('show');
                check = false;
            }
        });
        let required = form.find(".customRequired");
        $(required).each(function (index, value){
            if($(value).val()==0||$(value).val()==null){
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).attr('title',"{$required}").attr("data-toggle","tooltip");
                $(value).tooltip('show');
                check = false;
            }
        });
        let numberFormat = form.find(".numberFormat");
        $(numberFormat).each(function (index, value){
            if(!/^([0-9]+)?\s*$/.test($(this).val())){
                e.preventDefault();
                $(this).css("border-color","red");
                $(this).focus();
                $(value).parent().addClass('has-error').removeClass('has-success');
                $(value).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
                $(value).parent().tooltip('show');
                check = false;
            }
        });
        if(check){
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if(response.status == 1){
                        call_pnotify('success',response.message);
                        form.find('.orderItemId').val(response.model.id);
                        form.find('.removeButton').attr('data-id',response.model.id);
                        form.find('.copyButton').attr('data-id',response.model.id);
                        form.find('.changed').removeClass('changed');
                        form.addClass('savedItem').removeClass('changedItem');
                        form.find('.document-items').addClass('savedItem').removeClass('changedItem');
                    }else{
                        call_pnotify('fail',response.message);
                        let error = response.errors;
                        Object.keys(error).map(function(key) {
                            let error_list = error[key][0];
                            if(error_list){
                                Object.keys(error_list).map(function(n) {
                                    call_pnotify('fail',error_list[n][0]);
                                });
                            }
                        });
                    }  
                }
            });
        }
    });
    $('body').delegate('.copyButton', 'click', function(e){
        let form = $(this).parents('form.orderItemForm');
        let data_row_index = form.attr("data-row-index");
        e.preventDefault();
        let parent = $(this).parents(".model-orders-form").find(".rmParentDiv");
        let lastNum = 0;
        parent.find('.orderItemForm').each(function() {
          let value = parseInt($(this).attr('data-row-index'));
          lastNum = (value > lastNum) ? value : lastNum;
        });
        let num = lastNum+1;
        let rm_order = form.find('.rm_order');
        let var_name = form.find('.var_name');
        let model_var_id = form.find('.model_var_id');
        let add_var = form.find('.add_var');
        let var_modal = form.find('.var-modal');
        
        let acs_count  = form.find('.acs_count');
        let acs_modal  = form.find('.acs_modal');
        let acs_tbody_old = acs_modal.find('tbody').html();
        let acs_tbody_old_massiv = acs_tbody_old.split('ModelOrdersItems['+data_row_index+']');
        let acs_tbody = acs_tbody_old_massiv.join('ModelOrdersItems['+num+']');
        
        let toquv_acs_count  = form.find('.toquv_acs_count');
        let toquv_acs_modal  = form.find('.toquv_acs_modal');
        let toquv_acs_tbody_old = toquv_acs_modal.find('tbody').html();
        let toquv_acs_tbody_old_massiv = toquv_acs_tbody_old.split('ModelOrdersItems['+data_row_index+']');
        let toquv_acs_tbody = toquv_acs_tbody_old_massiv.join('ModelOrdersItems['+num+']');
        
        let load_date  = form.find('.load_date');
        let brend_id  = form.find('.brend_id');
        let priority  = form.find('.priority');
        let model_season  = form.find('.model_season');
        let add_info  = form.find('.add_info');
        let percentage  = form.find('.percentage');
        
        let baski_class  = form.find('.baski').attr('data-hidden');
        let baskiRequired = (baski_class=='hidden')?'':'customRequired';
        let baski_count  = form.find('.baski_count');
        let baski_modal  = form.find('.baski_modal');
        let baski_tbody_old = baski_modal.find('tbody').html();
        let baski_tbody_old_massiv = baski_tbody_old.split('ModelOrdersItems['+data_row_index+']');
        let baski_tbody = baski_tbody_old_massiv.join('ModelOrdersItems['+num+']');
        
        let rotatsion_class  = form.find('.rotatsion').attr('data-hidden');
        let rotatsionRequired = (rotatsion_class=='hidden')?'':'customRequired';
        let rotatsion_count  = form.find('.rotatsion_count');
        let rotatsion_modal  = form.find('.rotatsion_modal');
        let rotatsion_tbody_old = rotatsion_modal.find('tbody').html();
        let rotatsion_tbody_old_massiv = rotatsion_tbody_old.split('ModelOrdersItems['+data_row_index+']');
        let rotatsion_tbody = rotatsion_tbody_old_massiv.join('ModelOrdersItems['+num+']');
        
        let print_class  = form.find('.print').attr('data-hidden');
        let printRequired = (print_class=='hidden')?'':'customRequired';
        let print_count  = form.find('.print_count');
        let print_modal  = form.find('.print_modal');
        let print_tbody_old = print_modal.find('tbody').html();
        let print_tbody_old_massiv = print_tbody_old.split('ModelOrdersItems['+data_row_index+']');
        let print_tbody = print_tbody_old_massiv.join('ModelOrdersItems['+num+']');
        
        let stone_class  = form.find('.stone').attr('data-hidden');
        let stoneRequired = (stone_class=='hidden')?'':'customRequired';
        let stone_count  = form.find('.stone_count');
        let stone_modal  = form.find('.stone_modal');
        let stone_tbody_old = stone_modal.find('tbody').html();
        let stone_tbody_old_massiv = stone_tbody_old.split('ModelOrdersItems['+data_row_index+']');
        let stone_tbody = stone_tbody_old_massiv.join('ModelOrdersItems['+num+']');
        
        let model_price  = form.find('.model_price');
        let model_pb_id  = form.find('.model_pb_id');
        let rm_size  = form.find('.rm_size');
        
        let sizeDiv_old = form.find('.sizeDiv').html();
        let sizeDiv_old_massiv = sizeDiv_old.split('ModelOrdersItems['+data_row_index+']');
        let sizeDiv = sizeDiv_old_massiv.join('ModelOrdersItems['+num+']');
        let new_content = '<form id="order-form-'+num+'" data-row-index="'+num+'" class="orderItemForm" action="{$urlSaveItem}" method="post">' +
         '<input type="hidden" name="_csrf" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
    '<div class="document-items row parentRow">' +
    '  <div class="pull-right removeButtonParent"><button type="button" class="btn btn-success saveItem">{$saqlash}</button>&nbsp;<button type="button" class="btn btn-primary copyButton"><span class="fa fa-copy"></span></button>&nbsp;<button type="button" class="btn btn-danger removeButton"><span class="fa fa-trash"></span></button></div>' +
    '  <div class="rmParent" data-row-index="'+num+'">' +
    '    <div class="col-md-3 rmOrderId">' +
    '        <div class="form-group field-model_orders_item-model-list_'+num+' required">' +
    '            <label class="control-label" for="model_orders_item-model-list_'+num+'">{$model_name}</label>' +
    '            <select id="model_orders_item-model-list_'+num+'" class="rm_order form-control" name="ModelOrdersItems['+num+'][models_list_id]" indeks="'+num+'" value="'+rm_order.val()+'">' +
                    rm_order.html() +
    '            </select>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '        <div class="rmSpan"></div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12">' +
    '        <div class="form-group field-modelordersitems-model_var_id">' +
    '            <label>{$variant}</label>' +
    '            <div class="input-group">' +
    '                <span type="text" class="form-control var_name" id="var_'+num+'" aria-describedby="var-addon_'+num+'" disabled="">'+var_name.html()+'</span>' +
    '                <div class="form-group field-model-var-'+num+' required">' +
    '                    <input type="hidden" id="model-var-'+num+'" class="model_var_id" name="ModelOrdersItems['+num+'][model_var_id]" value="'+model_var_id.val()+'">' +
    '                    <div class="help-block"></div>' +
    '                </div> <span class="input-group-addon btn btn-success add_var" id="var-addon_'+num+'" style="padding: 3px 6px;" data-status="'+add_var.attr('data-status')+'" data-list-id="'+add_var.attr('data-list-id')+'" data-id="'+add_var.attr('data-id')+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="var-modal_'+num+'" class="fade modal var-modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <p class="modal-top" style="padding-right: 10px;padding-top: 10px;text-align: right;">' +
    '                        <button type="button" class="btn btn-success btn-lg form-variation" data-url="{$create_variation_url}" style="padding: 3px 6px;font-size: 14px;">' +
    '                            <i class="fa fa-plus"></i>' +
    '                        </button> &nbsp;&nbsp;' +
    '                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>' +
    '                    </p>' +
    '                    <div class="modal-header">' +
    '                    </div>' +
    '                    <div class="search_div">' +
    '                        <input type="text" class="form-control search_var" placeholder="{$qidirish_uchun}">' +
    '                    </div>' +
    '                    <div class="modal-body">'+var_modal.find(".modal-body").html()+'</div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar acs" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_acs_id">' +
    '            <label>{$aksessuarlar}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control acs_count input_count" id="acs_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+acs_count.val()+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#acs-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="acs-modal_'+num+'" class="fade modal acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$aksessuarlar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_acs_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul">Artikul / {$kodi}</th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__turi">' +
    '                                    {$turi} </th>' +
    '                                <th class="list-cell__qty">' +
    '                                    {$miqdori} </th>' +
    '                                <th class="list-cell__unit_id">' +
    "                                    {$ulchov_birligi} </th>" +
    '                                <th class="list-cell__barcod">' +
    '                                    {$barkod} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__acs_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_acs btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' + acs_tbody+
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar toquv_acs" style="width: 90px">' +
    '      <div class="form-group field-modelordersitems-model_toquv_acs_id">' +
    '           <label>{$toquv_aksessuar}</label>' +
    '           <div class="input-group">' +
    '               <input type="text" class="form-control toquv_acs_count input_count" id="toquv_acs_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+toquv_acs_count.val()+'">' +
    '               <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#toquv_acs-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '           </div>' +
    '       </div>' +
    '       <div id="toquv_acs-modal_'+num+'" class="fade modal toquv_acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '           <div class="modal-dialog modal-lg">' +
    '               <div class="modal-content">' +
    '                   <div class="modal-header">' +
    '                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                       <h3>{$toquv_aksessuarlar}</h3>' +
    '                   </div>' +
    '                   <div class="modal-body">' +
    '                       <table id="table_toquv_acs_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                          <thead>' +
    '                           <tr>' +
    '                              <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul">{$artikul_kodi}</th>' +
    '                              <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$aksessuar}</th>' +
    '                              <th class="list-cell__turi">{$turi}</th>' +
    '                              <th class="list-cell__miqdor">{$miqdori}</th>' +
    '                           </tr>' +
    '                          </thead>' +
    '                          <tbody>' + toquv_acs_tbody +
    '                          </tbody>' +
    '                      </table>' +
    '                  </div>' +
    '              </div>' +
    '          </div>' +
    '      </div>' +
    '    </div>'+
    '    <div class="col-md-1 col-w-12" style="width: 85px;">' +
    '        <div class="form-group field-model_orders_item-load_date_'+num+'">' +
    '            <label class="control-label" for="model_orders_item-load_date_'+num+'">{$yuklama_sanasi}</label>' +
    '            <div id="model_orders_item-load_date_'+num+'-kvdate" class="input-group  date"><span class="input-group-addon kv-date-picker"><i class="glyphicon glyphicon-calendar kv-dp-icon"></i></span>' +
    '                <input type="text" id="model_orders_item-load_date_'+num+'" class="customRequired form-control load_date" name="ModelOrdersItems['+num+'][load_date]" value="'+load_date.val()+'">' +
    '            </div>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12" style="width: 70px;">' +
    '        <div class="form-group field-model_brend_id_'+num+'">' +
    '            <label class="control-label" for="model_brend_id_'+num+'">{$brend_name}</label>' +
    '            <select id="model_brend_id_'+num+'" class="form-control select_list brend_id customRequired" name="ModelOrdersItems['+num+'][brend_id]" indeks="'+num+'" value="'+brend_id.val()+'">' +
                    brend_id.html() +
    '            </select>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 priority_div" style="width: 70px;">' +
    '        <div class="form-group field-model_priority_'+num+'">' +
    '            <label class="control-label" for="model_priority_'+num+'">{$muhimliligi}</label>' +
    '            <select id="model_priority_'+num+'" class="form-control priority" name="ModelOrdersItems['+num+'][priority]" indeks="'+num+'" value="'+priority.val()+'">' +
                        priority.html() +
    '            </select>' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12" style="width: 80px;">' +
    '        <div class="form-group field-model_season_'+num+'">' +
    '            <label class="control-label" for="model_season_'+num+'">{$mavsum}</label>' +
    '            <input type="text" id="model_season_'+num+'" class="form-control model_season" name="ModelOrdersItems['+num+'][season]" indeks="'+num+'" value="'+model_season.val()+'">' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12" style="width: 80px;">' +
    '        <div class="form-group field-model_add_info_'+num+'">' +
    '            <label class="control-label" for="model_add_info_'+num+'">{$izoh}</label>' +
    '            <input type="text" id="model_add_info_'+num+'" class="form-control add_info" name="ModelOrdersItems['+num+'][add_info]" indeks="'+num+'" value="'+add_info.val()+'">' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 percentage_div" style="width: 30px;">' +
    '        <div class="form-group field-model_percentage_'+num+'">' +
    '            <label class="control-label" for="model_percentage_'+num+'">%</label>' +
    '            <input type="text" id="model_percentage_'+num+'" class="number form-control percentage" name="ModelOrdersItems['+num+'][percentage]" indeks="'+num+'" value="'+percentage.val()+'">' +
    '            <div class="help-block"></div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar baski '+baski_class+'" data-hidden="'+baski_class+'" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_baski_id">' +
    '            <label>{$baski_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control baski_count input_count '+baskiRequired+'" id="baski_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+baski_count.val()+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#baski-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="baski-modal_'+num+'" class="fade modal baski_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$baskilar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_baski_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__baski_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_baski btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
                                    baski_tbody +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar rotatsion '+rotatsion_class+'" data-hidden="'+rotatsion_class+'" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_rotatsion_id">' +
    '            <label>{$rotatsion_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control rotatsion_count input_count '+rotatsionRequired+'" id="rotatsion_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+rotatsion_count.val()+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#rotatsion-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="rotatsion-modal_'+num+'" class="fade modal rotatsion_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$rotatsionlar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_rotatsion_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__rotatsion_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_rotatsion btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
                                    rotatsion_tbody +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar print '+print_class+'" data-hidden="'+print_class+'" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_print_id">' +
    '            <label>{$print_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control print_count input_count '+printRequired+'" id="print_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+print_count.val()+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#print-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="print-modal_'+num+'" class="fade modal print_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$printlar}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__prints_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_prints btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
                                    print_tbody +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 aksessuar stone '+stone_class+'" data-hidden="'+stone_class+'" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_stone_id">' +
    '            <label>{$stone_name}</label>' +
    '            <div class="input-group">' +
    '                <input type="text" class="form-control stone_count input_count '+stoneRequired+'" id="stone_'+num+'" aria-describedby="basic-addon_'+num+'" value="'+stone_count.val()+'">' +
    '                <span class="input-group-addon btn btn-success" id="basic-addon_'+num+'" style="padding: 3px 6px;" data-toggle="modal" data-target="#stone-modal_'+num+'"><i class="fa fa-plus"></i></span>' +
    '            </div>' +
    '        </div>' +
    '        <div id="stone-modal_'+num+'" class="fade modal stone_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">' +
    '            <div class="modal-dialog modal-lg">' +
    '                <div class="modal-content">' +
    '                    <div class="modal-header">' +
    '                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
    '                        <h3>{$stone_name}</h3>' +
    '                    </div>' +
    '                    <div class="modal-body">' +
    '                        <table id="table_stone_'+num+'" class="multiple-input-list table table-condensed table-renderer">' +
    '                            <thead>' +
    '                            <tr>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name">{$nomi}</th>' +
    '                                <th class="list-cell__desen_no">' +
    '                                    {$desen_no} </th>' +
    '                                <th class="list-cell__code">' +
    '                                    {$kodi} </th>' +
    '                                <th class="list-cell__brend">' +
    '                                    {$brend_name} </th>' +
    '                                <th class="list-cell__width">' +
    '                                    {$width} </th>' +
    '                                <th class="list-cell__height">' +
    "                                    {$height} </th>" +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">' +
    '                                    {$izoh} </th>' +
    '                                <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__stones_attachments">' +
    '                                    {$rasmlar} </th>' +
    '                                <th class="list-cell__button">' +
    '                                    <div class="add_stones btn btn-success" data-row-index="'+num+'"><i class="glyphicon glyphicon-plus"></i></div>' +
    '                                </th>' +
    '                            </tr>' +
    '                            </thead>' +
    '                            <tbody>' +
                                    stone_tbody +
    '                            </tbody>' +
    '                        </table>' +
    '                    </div>' +
    '                </div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 price" style="width: 70px">' +
    '        <div class="form-group field-modelordersitems-model_price">' +
    '            <div class="form-group field-model_price_'+num+'">' +
    '                <label class="control-label" for="model_price_'+num+'">{$narx}</label>' +
    '                <input type="text" id="model_price_'+num+'" class="number form-control model_price customRequired" name="ModelOrdersItems['+num+'][price]" indeks="'+num+'" value="'+model_price.val()+'">' +
    '                <div class="help-block"></div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 pb_id" style="width: 65px">' +
    '        <div class="form-group field-modelordersitems-model_pb_id">' +
    '            <div class="form-group field-model_pb_id_'+num+'">' +
    '                <label class="control-label" for="model_pb_id_'+num+'">{$pul_birligi}</label>' +
    '                <select id="model_pb_id_'+num+'" class="number form-control model_pb_id" name="ModelOrdersItems['+num+'][pb_id]" indeks="'+num+'" value="'+model_pb_id.val()+'">' + 
                            model_pb_id.html() +
    '                </select>' +
    '                <div class="help-block"></div>' +
    '            </div>' +
    '        </div>' +
    '    </div>' +
    '    <div class="col-md-1 col-w-12 sizeParent" style="width: 90px">' +
    '        <div class="form-group field-modelordersitems-model_size_collections_id">' +
    "            <label>{$ulcham}</label>" +
    '            <select id="model_orders_item-model-size_'+num+'" class="rm_size form-control" name="ModelOrdersItems['+num+'][size_collections_id]" indeks="'+num+'" value="'+rm_size.val()+'">' +
                    rm_size.html() +
    '            </select>' +
    '        </div>' +
    '    </div>' +
    '    <div class="sizeDiv" style="padding-right: 15px;padding-left: 15px;float: left;">' +
            sizeDiv +
    '    </div>' +
    '  </div>' +
    '</div>' +
    '<input type="hidden" class="orderItemId" name="ModelOrdersItems[id]">' +
    '</form>';
            //parent.append(new_content);
            $(new_content).insertAfter(form);
            $('#model_orders_item-model-size_'+num).find('option').removeAttr('data-select2-id');
            if (jQuery('#model_orders_item-model-list_'+num).data('select2')) {
                jQuery('#model_orders_item-model-list_'+num).select2('destroy');
            }
            jQuery.when(jQuery('#model_orders_item-model-list_'+num).select2(model_list_id_new_select2)).done(initS2Loading('model_orders_item-model-list_'+num, 'model_list_id_select2'));
            
            jQuery('#model_orders_item-model-list_'+num).on('select2:select',
                function(e){
                    if(e.params.data){
                    let t = $(this);
                    let parent = t.parents('.rmParent');
                    if(e.params.data.baski==0){
                        parent.find('.baski').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.baski_count').removeClass('customRequired');
                    }else{
                        parent.find('.baski').removeClass('hidden').attr('data-hidden','');
                        parent.find('.baski_count').addClass('customRequired');
                    }
                    if(e.params.data.rotatsion==0){
                        parent.find('.rotatsion').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.rotatsion_count').removeClass('customRequired');
                    }else{
                        parent.find('.rotatsion').removeClass('hidden').attr('data-hidden','');
                        parent.find('.rotatsion_count').addClass('customRequired');
                    }
                    if(e.params.data.prints==0){
                        parent.find('.print').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.print_count').removeClass('customRequired');
                    }else{
                        parent.find('.print').removeClass('hidden').attr('data-hidden','');
                        parent.find('.print_count').addClass('customRequired');
                    }
                    if(e.params.data.stone==0){
                        parent.find('.stone').addClass('hidden').attr('data-hidden','hidden');
                        parent.find('.stone_count').removeClass('customRequired');
                    }else{
                        parent.find('.stone').removeClass('hidden').attr('data-hidden','');
                        parent.find('.stone_count').addClass('customRequired');
                    }
                    if(e.params.data.brend_id){
                        parent.find('.brend_id').val(e.params.data.brend_id).trigger('change');
                    }
                    if(e.params.data.acs){
                        let indeks = parent.attr('data-row-index');
                        let table = $('#table_acs_'+indeks);
                        let acs = $('#acs_'+indeks);
                        let dataList = e.params.data.acs;
                        table.find('tbody').html('');
                        let count_acs = 0;
                        Object.keys(dataList).map(function(index,key){
                            let pr_id = index;
                            let pr_name = dataList[index].name;
                            let pr_artikul = dataList[index].artikul;
                            let pr_turi = dataList[index].turi;
                            let pr_qty = dataList[index].qty ?? 0;
                            let pr_unit = dataList[index].unit;
                            let pr_unit_id = dataList[index].unit_id;
                            let pr_barcod = dataList[index].barcod;
                            let pr_add_info = dataList[index].add_info;
                            let pr_image = dataList[index].image;
                            let check_row = table.find('.row_'+pr_id);
                            let tbody = table.find('tbody');
                            let last_tr = tbody.find('tr').last();
                            let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
                            let image = (pr_image!=null)?'<img class="imgPreview pr_image" src="'+pr_image+'">':'';
                            if(check_row.length==0){
                                table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                                    '                                <td class="list-cell__artikul">' +
                                    '                                    <span type="text" class="form-control" disabled="">' +
                                                                            pr_artikul +
                                                                         '</span>'+
                                    '                                </td>' +
                                    '                                <td class="list-cell__name">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_name+'</span>' +
                                    '                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][id]" value="'+pr_id+'"><input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][unit_id]" value="'+pr_unit_id+'">' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__turi">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_turi+'</span>' +
                                    '    <td class="list-cell__qty">' +
                                    '        <input type="text" class="acs_input form-control number" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][qty]" value="'+pr_qty+'">' +
                                    '    </td>' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__unit_id">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_unit+'</span>' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__barcod">' +
                                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_barcod+'</span>' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__add_info">' +
'                                   <input type="text" class="acs_input form-control" name="ModelOrdersItems['+indeks+'][acs]['+row_index+'][add_info]" value="'+pr_add_info+'">' +
                                    '                                </td>' +
                                    '                                <td class="list-cell__acs_image">' +
                                                                         image+
                                    '                                </td>' +
                                    '                                <td class="list-cell__button">' +
                                    '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                                    '                                        <i class="glyphicon glyphicon-remove"></i>' +
                                    '                                    </div>' +
                                    '                                </td>' +
                                    '                            </tr>');
                            }
                            count_acs++;
                        });
                        acs.val(count_acs);
                    }
                }
            });
            /*if (jQuery('#modelVar'+num).data('select2')) {
                jQuery('#modelVar'+num).select2('destroy');
            }
            jQuery.when(jQuery('#modelVar'+num).select2(model_var_id_select2)).done(initS2Loading('modelVar'+num, 'model_list_id_select2'));*/
            
            jQuery.fn.kvDatepicker.dates = {};
            if (jQuery('#model_orders_item-load_date_'+num).data('kvDatepicker')) {
                jQuery('#model_orders_item-load_date_'+num).kvDatepicker('destroy');
            }
            jQuery('#model_orders_item-load_date_'+num+'-kvdate').kvDatepicker(model_load_date_kvdatepicker);
            
            initDPAddon('model_orders_item-load_date_'+num);
            if (jQuery('#model_orders_item-model-size_'+num).data('select2')) {
                jQuery('#model_orders_item-model-size_'+num).select2('destroy');
            }
            jQuery.when(jQuery('#model_orders_item-model-size_'+num).select2(model_size_collections_id_select2)).done(initS2Loading('model_orders_item-model-size_'+num, 'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-baski_'+num).data('select2')) {jQuery('#model_orders_item-model-baski_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-baski_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-baski_'+num,'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-rotatsion_'+num).data('select2')) {jQuery('#model_orders_item-model-rotatsion_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-rotatsion_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-rotatsion_'+num,'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-prints_'+num).data('select2')) {jQuery('#model_orders_item-model-prints_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-prints_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-prints_'+num,'model_list_id_select2'));
            if (jQuery('#model_orders_item-model-stone_'+num).data('select2')) {jQuery('#model_orders_item-model-stone_'+num).select2('destroy'); }
            jQuery.when(jQuery('#model_orders_item-model-stone_'+num).select2(aksessuar)).done(initS2Loading('model_orders_item-model-stone_'+num,'model_list_id_select2'));
    });
JS;
$this->registerJs($customScript, \yii\web\View::POS_READY);
$css = <<< Css
.search_div{
    width: 300px;
    margin: 0 auto;
    padding-bottom: 5px;
}
.search_div .search_var{
    height: 24px;
    border: 1px solid green;
}
.var-modal .modal-body{
    padding: 0;
}
body{
    font-size: 10px;
}
div.form-group .select2-container--krajee .select2-selection--single {
    height: 18px;
    line-height: 1.7;
    padding: 3px 24px 3px 12px;
    border-radius: 0;
}
.select2-container--krajee .select2-selection {
    color: #555555;
    font-size: 10px;
}
div.form-group .select2-container--krajee .select2-selection__clear {
    top: 0;
    font-size: 10px;
}
div.form-group .select2-container--krajee span.selection .select2-selection--single span.select2-selection__arrow {
    height: 16px;
}
.form-control {
    height: 18px;
    font-size: 10px;
    padding-right: 0;
}
.date .input-group-addon {
    padding: 2px 2px;
    font-size: 10px;
}
.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
    border: 1px solid #d2d6de;
    border-radius: 0;
    padding: 3px 6px;
    height: 18px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 18px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 18px;
    right: 3px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-top: -4px;
}
.control-label, label {
    font-size: 10px;
    margin-bottom: 0;
}
.btn{
    padding: 2px 6px;
    font-size: 10px;
}
.document-items{
    margin: 0;
    min-height: 12px;
    padding-bottom: 10px;
    padding-top: 10px;
    padding-left: 10px;
    margin-left: -5px;
    position:relative;
}
.rmParentDiv .orderItemForm:nth-child(2n+1),.rmParentDiv .orderItemForm:nth-child(2n+1) .document-items{
    background: #f2f2f2;
}
.rmParentDiv > .document-items:first-child{
    margin-top: 16px;
}
.document-items .col-w-18{
    width: 18%;
    padding: 0;
    padding-left: 3px;
}
.document-items .col-w-12{
    width: 12%;
    padding: 0;
    padding-left: 3px;
}
.rmParentDiv{
    margin: 0;
}
.rmParent{
    padding: 0;
    margin-left: -7px;
    margin-right: -25px;
}
.document-items > .rmParent > .rmOrderId{
    width: 17%;
    padding: 0;
    padding-left: 3px;
}
.removeButtonParent{
    padding-right: 0;
    z-index: 999;
    margin-right: -5px;
    position: absolute;
    right: 2px;
    top: -12px;
}
.removeButtonParent:hover{
    z-index: 999999999;
}
.viewIp{
    height: 14px;
}
.select2-container--krajee .select2-selection--multiple .select2-selection__choice__remove{
    font-size: 11px;
}
.select2-container--krajee .select2-selection--multiple .select2-selection__clear {
    right: 5px;
}
.select2-container--krajee .select2-selection--multiple .select2-search--inline .select2-search__field {
    height: 18px !important;
}
.select2-selection__rendered img{
    display: none;
}
.rmButton{
    padding: 3px 8px;
    margin-top: -30px;
    font-size: 14px;
}
.rmContentSize .form-group .help-block {
    margin-bottom: 0 !important;
}
/*
.models-variations-form .cansel{
    display: none;
}*/
.flex-container-variations{
    background: ivory;
}
Css;
$this->registerCss($css);

$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .acs_div,.print_div,.stone_div,.baski_div,.rotatsion_div{
        width: 130px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        position: relative;
        margin-bottom: 20px;
    }
    .list_acs,.list_prints,.list_stone,.list_baski, .list_rotatsion{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
    .check_button{
        position: absolute;
        bottom: -18px;
        left: 30%;
    }
CSS;
$this->registerCss($css);
$url_search = Url::to('search-prints');
$js = <<< JS
    var table;
    function addslashes( str ) {
        return str.replace('/(["\'\])/g', "\\$1").replace('/\0/g', "\\0");
    } 
    $("body").delegate(".add_prints","click",function(){
        $('#prints-modal').modal('show');
        $('#prints-modal').attr('data-row-index',$(this).attr('data-row-index'));
    });
    $("body").delegate(".check_print","click",function(){
        let index = $("#prints-modal").attr('data-row-index');
        let table = $('#table_'+index);
        let t = $(this);
        let parent = t.parents('.print_div');
        let pr_id = t.attr('data-id');
        let pr_name = parent.find('.pr_name').html();
        let pr_desen = parent.find('.pr_desen').html();
        let pr_code = parent.find('.pr_code').html();
        let pr_brend = parent.find('.pr_brend').html();
        let pr_musteri = parent.find('.pr_musteri').html();
        let pr_width = parent.find('.pr_width').html();
        let pr_height = parent.find('.pr_height').html();
        let pr_add_info = parent.find('.pr_add_info').html();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = table.find('.row_'+pr_id);
        let tbody = table.find('tbody');
        let last_tr = tbody.find('tr').last();
        let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
        if(check_row.length==0){
            table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__name">' +
                '                                    <span type="text" class="print_input form-control" disabled="">'+pr_name+'</span>' +
                '                                    <input type="hidden" class="print_input form-control" name="ModelOrdersItems['+index+'][print]['+row_index+'][id]" value="'+pr_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__desen_no">' +
                '                                    <span type="text" class="print_input form-control" disabled="">'+pr_desen+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__code">' +
                '                                    <span type="text" class="print_input form-control" disabled="">'+pr_code+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__brend">' +
                '                                    <span type="text" class="print_input form-control" disabled="">'+pr_brend+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__width">' +
                '                                    <span type="text" class="print_input form-control" disabled="">'+pr_width+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__height">' +
                '                                    <span type="text" class="print_input form-control" disabled="">'+pr_height+'</span>' +
                '                                </td>'+
                '                                <td class="list-cell__add_info">' +
                '                                    <span>'+pr_add_info+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__prints_image">' +
                '                                    <img class="imgPreview pr_image" src="'+pr_image+'">' +
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            call_pnotify('success','Muvaffaqqiyatli saqlandi');
        }else{
            call_pnotify('fail',"Siz buni tanlab bo'lgansiz")
        }
        let count = tbody.find('tr').length;
        $('#print_'+index).val(count).trigger('change');
    });
    $('body').delegate('.removeTr', 'click', function(e){
        let tbody = $(this).parents('tbody');
        let printCount = $(this).parents('.aksessuar');
        $(this).parents('tr').remove();
        let count = tbody.find('tr').length;
        printCount.find('.input_count').val(count);
    });
    $("body").delegate(".create-print","click",function(e){
        e.preventDefault(); 
        $('#model-var-prints-modal').modal('show').find('.modal-body').load($(this).attr('href'));
    });
    $("body").delegate(".customAjaxForm","submit", function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr("action");
        var self = $(this);
        var required = self.find(".customRequired");
        var check = true;
        if(check){
            $(this).find("button[type=submit]").hide();
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if(response.status == 0){
                        call_pnotify('success',response.message);
                        $('#model-var-prints-modal').modal('hide');
                        let model = response.model;
                        let list = '<div class="print_div" id="print_div_'+model.id+'" data-id="'+model.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+model.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '             <small class="pr_width">'+(model.width ? model.width : "")+'</small>'+     
                                    '              <small>x</small>'+
                                    '              <small class="pr_height">'+(model.height ? model.height : "")+'</small>'+ 
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+model.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+model.desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+model.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+model.brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+model.musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(model.width ? model.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(model.height ? model.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+model.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_print" data-id="'+model.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                        $('.list_prints').append(list);
                        self.find("button[type=submit]").show();
                    }else{
                        let error = response.errors;
                        Object.keys(error).map(function(key) {
                            let error_list = error[key][0];
                            call_pnotify('fail',error_list);
                        });
                        self.find("button[type=submit]").show();
                        call_pnotify('fail',response.message);
                    }
                }
            });
        }
    });
    var list = [];
    $('body').delegate("#search_prints","keyup",function(){
        let _this = this;
        list = [];
        $.each($(".print_div"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list.push($(this).data('id'));
        });
    });
    $('body').delegate('#search_button', 'click', function(e){
        let search = $("#search_prints");
        if(search.val()==""){
            call_pnotify('fail', 'Qidirish uchun biror narsa yozing');
        }else{
            $.ajax({
                url: '{$url_search}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: search.val(),
                    list: list
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.model;
                    dataList.map(function(key) {
                        let desen_no = (key.desen_no!=null)?key.desen_no:'';
                        let brend_id = (key.brend_id!=null)?key.brend_id:'';
                        let musteri_id = (key.musteri_id!=null)?key.musteri_id:'';
                        li += '<div class="print_div" id="print_div_'+key.id+'" data-id="'+key.id+'">' +
                                '    <div class="media">' +
                                '        <div class="media-left">' +
                                '            <img class="imgPreview" src="/web/'+key.image+'" ' +
                                '                style="width: 40px;min-height: 5vh;">' +
                                '        </div>' +
                                '        <div class="media-body">' +
                                '            <h4 class="media-heading pr_name">'+key.name+'</h4>' +
                                '            <h5 class="pr_desen"><small>'+desen_no+'</small></h5>' +
                                '            <h5 class="pr_code"><small>'+key.code+'</small></h5>' +
                                '            <h5 class="pr_brend"><small>'+brend_id+'</small></h5>' +
                                '            <h5 class="pr_musteri"><small>'+musteri_id+'</small></h5>' +
                                '            <h5 class="hidden pr_width"><small>'+(key.width ? key.width : "")+'</small></h5>' +
                                '            <h5 class="hidden pr_height"><small>'+(key.height ? key.height : "")+'</small></h5>' +
                                '            <h5 class="hidden pr_add_info"><small>'+key.add_info+'</small></h5>' +
                                '        </div>' +
                                '    </div>' +
                                '    <div class="text-center check_button">' +
                                '        <span class="btn btn-success' +
                                '            btn-xs check_print" data-id="'+key.id+'">$tanlash</span>' +
                                '    </div>' +
                                '</div>';
                        list.push(key.id);
                    });
                    $('.list_prints').append(li);
                }else{
                    call_pnotify('fail',response.message);
                }
            });
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$url_search_stone = Url::to('search-stones');
$js = <<< JS
    var table;
    $("body").delegate(".add_stones","click",function(){
        $('#stone-modal').modal('show');
        $('#stone-modal').attr('data-row-index',$(this).attr('data-row-index'));
    });
    $("body").delegate(".check_stone","click",function(){
        let index = $("#stone-modal").attr('data-row-index');
        let table = $('#table_stone_'+index);
        let t = $(this);
        let parent = t.parents('.stone_div');
        let pr_id = t.attr('data-id');
        let pr_name = parent.find('.pr_name').text();
        let pr_desen = (parent.find('.pr_desen').text()!=""&&parent.find('.pr_desen').text()!="null")?parent.find('.pr_desen').text():'';
        let pr_code = parent.find('.pr_code').text();
        let pr_brend = parent.find('.pr_brend').text();
        let pr_musteri = parent.find('.pr_musteri').text();
        let pr_width = parent.find('.pr_width').text();
        let pr_height = parent.find('.pr_height').text();
        let pr_add_info = parent.find('.pr_add_info').text();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = table.find('.row_'+pr_id);
        let tbody = table.find('tbody');
        let last_tr = tbody.find('tr').last();
        let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
        if(check_row.length==0){
            table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__name">' +
                '                                    <span type="text" class="stone_input form-control" disabled="">'+pr_name+'</span>' +
                '                                    <input type="hidden" class="stone_input form-control" name="ModelOrdersItems['+index+'][stone]['+row_index+'][id]" value="'+pr_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__desen_no">' +
                '                                    <span type="text" class="stone_input form-control" disabled="" >'+pr_desen+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__code">' +
                '                                    <span type="text" class="stone_input form-control" disabled="">'+pr_code+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__brend">' +
                '                                    <span type="text" class="stone_input form-control" disabled="">'+pr_brend+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__width">' +
                '                                    <span type="text" class="stone_input form-control" disabled="">'+pr_width+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__height">' +
                '                                    <span type="text" class="stone_input form-control" disabled="">'+pr_height+'</span>' +
                '                                </td>'+
                '                                <td class="list-cell__add_info">' +
                '                                    <span class="stone_input form-control" disabled="">'+pr_add_info+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__stones_image">' +
                '                                    <img class="imgPreview pr_image" src="'+pr_image+'">' +
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            call_pnotify('success','Muvaffaqqiyatli saqlandi');
        }else{
            call_pnotify('fail',"Siz buni tanlab bo'lgansiz")
        }
        let count = tbody.find('tr').length;
        $('#stone_'+index).val(count).trigger('change');
    });
    $('body').delegate('.removeTrStone', 'click', function(e){
        let tbody = $(this).parents('tbody');
        let stoneCount = $(this).parents('.stone');
        $(this).parents('tr').remove();
        let count = tbody.find('tr').length;
        stoneCount.find('.stone_count').val(count);
    });
    $("body").delegate(".create-stone","click",function(e){
        e.preventDefault(); 
        $('#model-var-stones-modal').modal('show').find('.modal-body').load($(this).attr('href'));
    });
    $("body").delegate(".customAjaxFormStone","submit", function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr("action");
        var self = $(this);
        var required = self.find(".customRequired");
        var check = true;
        if(check){
            $(this).find("button[type=submit]").hide();
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if(response.status == 0){
                        call_pnotify('success',response.message);
                        $('#model-var-stones-modal').modal('hide');
                        let model = response.model;
                        let list = '<div class="stone_div" id="stone_div_'+model.id+'" data-id="'+model.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+model.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '             <small class="pr_width">'+(model.width ? model.width : "")+'</small>'+     
                                    '              <small>x</small>'+
                                    '              <small class="pr_height">'+(model.height ? model.height : "")+'</small>'+ 
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+model.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+model.desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+model.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+model.brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+model.musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(model.width ? model.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(model.height ? model.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+model.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_stone" data-id="'+model.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                        $('.list_stone').append(list);
                        self.find("button[type=submit]").show();
                    }else{
                        self.find("button[type=submit]").show();
                        call_pnotify('fail',response.message);
                    }
                }
            });
        }
    });
    var list_stone = [];
    $('body').delegate("#search_stone","keyup",function(){
        let _this = this;
        list_stone = [];
        $.each($(".stone_div"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list_stone.push($(this).data('id'));
        });
    });
    $('body').delegate('#search_button_stone', 'click', function(e){
        let search = $("#search_stone");
        if(search.val()==""){
            call_pnotify('fail', 'Qidirish uchun biror narsa yozing');
        }else{
            $.ajax({
                url: '{$url_search_stone}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: search.val(),
                    list: list_stone
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.model;
                    dataList.map(function(key) {
                        let desen_no = (key.desen_no!=null)?key.desen_no:'';
                        let brend_id = (key.brend_id!=null)?key.brend_id:'';
                        let musteri_id = (key.musteri_id!=null)?key.musteri_id:'';
                          li += '<div class="stone_div" id="stone_div_'+key.id+'" data-id="'+key.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+key.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+key.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+key.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(key.width ? key.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(key.height ? key.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+key.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_stone" data-id="'+key.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                          list_stone.push(key.id);
                    });
                    $('.list_stone').append(li);
                }else{
                    call_pnotify('fail',response.message);
                }
            });
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$url_search_baski = Url::to('search-baski');
$url_search_rotatsion = Url::to('search-rotatsion');
$js = <<< JS
    var table;
    $("body").delegate(".add_baski","click",function(){
        $('#baski-modal').modal('show');
        $('#baski-modal').attr('data-row-index',$(this).attr('data-row-index'));
    });
    $("body").delegate(".check_baski","click",function(){
        let index = $("#baski-modal").attr('data-row-index');
        let table = $('#table_baski_'+index);
        let t = $(this);
        let parent = t.parents('.baski_div');
        let pr_id = t.attr('data-id');
        let pr_name = parent.find('.pr_name').text();
        let pr_desen = (parent.find('.pr_desen').text()!=""&&parent.find('.pr_desen').text()!="null")?parent.find('.pr_desen').text():'';
        let pr_code = parent.find('.pr_code').text();
        let pr_brend = parent.find('.pr_brend').text();
        let pr_musteri = parent.find('.pr_musteri').text();
        let pr_width = parent.find('.pr_width').text();
        let pr_height = parent.find('.pr_height').text();
        let pr_add_info = parent.find('.pr_add_info').text();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = table.find('.row_'+pr_id);
        let tbody = table.find('tbody');
        let last_tr = tbody.find('tr').last();
        let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
        if(check_row.length==0){
            table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__name">' +
                '                                    <span type="text" class="baski_input form-control" disabled="">'+pr_name+'</span>' +
                '                                    <input type="hidden" class="baski_input form-control" name="ModelOrdersItems['+index+'][baski]['+row_index+'][id]" value="'+pr_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__desen_no">' +
                '                                    <span type="text" class="baski_input form-control" disabled="" >'+pr_desen+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__code">' +
                '                                    <span type="text" class="baski_input form-control" disabled="">'+pr_code+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__brend">' +
                '                                    <span type="text" class="baski_input form-control" disabled="">'+pr_brend+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__width">' +
                '                                    <span type="text" class="baski_input form-control" disabled="">'+pr_width+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__height">' +
                '                                    <span type="text" class="baski_input form-control" disabled="">'+pr_height+'</span>' +
                '                                </td>'+
                '                                <td class="list-cell__add_info">' +
                '                                    <span class="baski_input form-control" disabled="">'+pr_add_info+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__baski_image">' +
                '                                    <img class="imgPreview pr_image" src="'+pr_image+'">' +
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            call_pnotify('success','Muvaffaqqiyatli saqlandi');
        }else{
            call_pnotify('fail',"Siz buni tanlab bo'lgansiz")
        }
        let count = tbody.find('tr').length;
        $('#baski_'+index).val(count).trigger('change');
    });
    $('body').delegate('.removeTrBaski', 'click', function(e){
        let tbody = $(this).parents('tbody');
        let baskiCount = $(this).parents('.baski');
        $(this).parents('tr').remove();
        let count = tbody.find('tr').length;
        baskiCount.find('.baski_count').val(count);
    });
    $("body").delegate(".create-baski","click",function(e){
        e.preventDefault(); 
        $('#model-var-baski-modal').modal('show').find('.modal-body').load($(this).attr('href'));
    });
    $("body").delegate(".customAjaxFormBaski","submit", function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr("action");
        var self = $(this);
        var required = self.find(".customRequired");
        var check = true;
        if(check){
            $(this).find("button[type=submit]").hide();
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if(response.status == 0){
                        call_pnotify('success',response.message);
                        $('#model-var-baski-modal').modal('hide');
                        let model = response.model;
                        let list = '<div class="baski_div" id="baski_div_'+model.id+'" data-id="'+model.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+model.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '             <small class="pr_width">'+(model.width ? model.width : "")+'</small>'+     
                                    '              <small>x</small>'+
                                    '              <small class="pr_height">'+(model.height ? model.height : "")+'</small>'+ 
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+model.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+model.desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+model.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+model.brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+model.musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(model.width ? model.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(model.height ? model.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+model.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_baski" data-id="'+model.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                        $('.list_baski').append(list);
                        self.find("button[type=submit]").show();
                    }else{
                        self.find("button[type=submit]").show();
                        let error = response.errors;
                        Object.keys(error).map(function(key) {
                            let input = $("input[name='ModelVarBaski["+key+"]']");
                            input.css("border-color","red");
                            input.parent().find(".help-block").css("color","red").html(error[key][0]);
                        });
                        call_pnotify('fail',response.message);
                    }
                }
            });
        }
    });
    var list_baski = [];
    $('body').delegate("#search_baski","keyup",function(){
        let _this = this;
        list_baski = [];
        $.each($(".baski_div"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list_baski.push($(this).data('id'));
        });
    });
    $('body').delegate('#search_button_baski', 'click', function(e){
        let search = $("#search_baski");
        if(search.val()==""){
            call_pnotify('fail', 'Qidirish uchun biror narsa yozing');
        }else{
            $.ajax({
                url: '{$url_search_baski}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: search.val(),
                    list: list_baski
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.model;
                    dataList.map(function(key) {
                        let desen_no = (key.desen_no!=null)?key.desen_no:'';
                        let brend_id = (key.brend_id!=null)?key.brend_id:'';
                        let musteri_id = (key.musteri_id!=null)?key.musteri_id:'';
                          li += '<div class="baski_div" id="baski_div_'+key.id+'" data-id="'+key.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+key.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+key.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+key.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(key.width ? key.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(key.height ? key.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+key.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_baski" data-id="'+key.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                          list_baski.push(key.id);
                    });
                    $('.list_baski').append(li);
                }else{
                    call_pnotify('fail',response.message);
                }
            });
        }
    });
    $("body").delegate(".add_rotatsion","click",function(){
        $('#rotatsion-modal').modal('show');
        $('#rotatsion-modal').attr('data-row-index',$(this).attr('data-row-index'));
    });
    $("body").delegate(".check_rotatsion","click",function(){
        let index = $("#rotatsion-modal").attr('data-row-index');
        let table = $('#table_rotatsion_'+index);
        let t = $(this);
        let parent = t.parents('.rotatsion_div');
        let pr_id = t.attr('data-id');
        let pr_name = parent.find('.pr_name').text();
        let pr_desen = (parent.find('.pr_desen').text()!=""&&parent.find('.pr_desen').text()!="null")?parent.find('.pr_desen').text():'';
        let pr_code = parent.find('.pr_code').text();
        let pr_brend = parent.find('.pr_brend').text();
        let pr_musteri = parent.find('.pr_musteri').text();
        let pr_width = parent.find('.pr_width').text();
        let pr_height = parent.find('.pr_height').text();
        let pr_add_info = parent.find('.pr_add_info').text();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = table.find('.row_'+pr_id);
        let tbody = table.find('tbody');
        let last_tr = tbody.find('tr').last();
        let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
        if(check_row.length==0){
            table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__name">' +
                '                                    <span type="text" class="rotatsion_input form-control" disabled="">'+pr_name+'</span>' +
                '                                    <input type="hidden" class="rotatsion_input form-control" name="ModelOrdersItems['+index+'][rotatsion]['+row_index+'][id]" value="'+pr_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__desen_no">' +
                '                                    <span type="text" class="rotatsion_input form-control" disabled="" >'+pr_desen+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__code">' +
                '                                    <span type="text" class="rotatsion_input form-control" disabled="">'+pr_code+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__brend">' +
                '                                    <span type="text" class="rotatsion_input form-control" disabled="">'+pr_brend+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__width">' +
                '                                    <span type="text" class="rotatsion_input form-control" disabled="">'+pr_width+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__height">' +
                '                                    <span type="text" class="rotatsion_input form-control" disabled="">'+pr_height+'</span>' +
                '                                </td>'+
                '                                <td class="list-cell__add_info">' +
                '                                    <span class="rotatsion_input form-control" disabled="">'+pr_add_info+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__rotatsion_image">' +
                '                                    <img class="imgPreview pr_image" src="'+pr_image+'">' +
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            call_pnotify('success','Muvaffaqqiyatli saqlandi');
        }else{
            call_pnotify('fail',"Siz buni tanlab bo'lgansiz")
        }
        let count = tbody.find('tr').length;
        $('#rotatsion_'+index).val(count).trigger('change');
    });
    $('body').delegate('.removeTrRotatsion', 'click', function(e){
        let tbody = $(this).parents('tbody');
        let rotatsionCount = $(this).parents('.rotatsion');
        $(this).parents('tr').remove();
        let count = tbody.find('tr').length;
        rotatsionCount.find('.rotatsion_count').val(count);
    });
    $("body").delegate(".create-rotatsion","click",function(e){
        e.preventDefault(); 
        $('#model-var-rotatsion-modal').modal('show').find('.modal-body').load($(this).attr('href'));
    });
    $("body").delegate(".customAjaxFormRotatsion","submit", function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr("action");
        var self = $(this);
        var required = self.find(".customRequired");
        var check = true;
        if(check){
            $(this).find("button[type=submit]").hide();
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if(response.status == 0){
                        call_pnotify('success',response.message);
                        $('#model-var-rotatsion-modal').modal('hide');
                        let model = response.model;
                        let list = '<div class="rotatsion_div" id="rotatsion_div_'+model.id+'" data-id="'+model.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+model.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '             <small class="pr_width">'+(model.width ? model.width : "")+'</small>'+     
                                    '              <small>x</small>'+
                                    '              <small class="pr_height">'+(model.height ? model.height : "")+'</small>'+ 
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+model.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+model.desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+model.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+model.brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+model.musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(model.width ? model.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(model.height ? model.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+model.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_rotatsion" data-id="'+model.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                        $('.list_rotatsion').append(list);
                        self.find("button[type=submit]").show();
                    }else{
                        self.find("button[type=submit]").show();
                        let error = response.errors;
                        Object.keys(error).map(function(key) {
                            let input = $("input[name='ModelVarBaski["+key+"]']");
                            input.css("border-color","red");
                            input.parent().find(".help-block").css("color","red").html(error[key][0]);
                        });
                        call_pnotify('fail',response.message);
                    }
                }
            });
        }
    });
    var list_rotatsion = [];
    $('body').delegate("#search_rotatsion","keyup",function(){
        let _this = this;
        list_rotatsion = [];
        $.each($(".rotatsion_div"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list_rotatsion.push($(this).data('id'));
        });
    });
    $('body').delegate('#search_button_rotatsion', 'click', function(e){
        let search = $("#search_rotatsion");
        if(search.val()==""){
            call_pnotify('fail', 'Qidirish uchun biror narsa yozing');
        }else{
            $.ajax({
                url: '{$url_search_rotatsion}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: search.val(),
                    list: list_rotatsion
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.model;
                    dataList.map(function(key) {
                        let desen_no = (key.desen_no!=null)?key.desen_no:'';
                        let brend_id = (key.brend_id!=null)?key.brend_id:'';
                        let musteri_id = (key.musteri_id!=null)?key.musteri_id:'';
                          li += '<div class="rotatsion_div" id="rotatsion_div_'+key.id+'" data-id="'+key.id+'">' +
                                    '    <div class="media">' +
                                    '        <div class="media-left">' +
                                    '            <img class="imgPreview" src="/web/'+key.image+'" ' +
                                    '                style="width: 40px;min-height: 5vh;">' +
                                    '        </div>' +
                                    '        <div class="media-body">' +
                                    '            <h4 class="media-heading pr_name">'+key.name+'</h4>' +
                                    '            <h5 class="pr_desen"><small>'+desen_no+'</small></h5>' +
                                    '            <h5 class="pr_code"><small>'+key.code+'</small></h5>' +
                                    '            <h5 class="pr_brend"><small>'+brend_id+'</small></h5>' +
                                    '            <h5 class="pr_musteri"><small>'+musteri_id+'</small></h5>' +
                                    '            <h5 class="hidden pr_width"><small>'+(key.width ? key.width : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_height"><small>'+(key.height ? key.height : "")+'</small></h5>' +
                                    '            <h5 class="hidden pr_add_info"><small>'+key.add_info+'</small></h5>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '    <div class="text-center check_button">' +
                                    '        <span class="btn btn-success' +
                                    '            btn-xs check_rotatsion" data-id="'+key.id+'">$tanlash</span>' +
                                    '    </div>' +
                                    '</div>';
                          list_rotatsion.push(key.id);
                    });
                    $('.list_rotatsion').append(li);
                }else{
                    call_pnotify('fail',response.message);
                }
            });
        }
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$imageUrl = Yii::$app->urlManager->createUrl(['base/models-variations/attachment-upload']);
$js = <<< JS
    function call_pnotify(status,text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 3000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
$("body").delegate(".addAttach","click",function(){
    let t = $(this);
    let num = 1*t.attr("num");
    t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hidden"></span></label>');
    t.attr("num",num+1);
});
$("body").delegate("input.uploadImage", "change", function(){
    let a = $(this).parent();
    let b = a.parent();
    if (this.files[0]) {
        let fr = new FileReader();
        let fd = new FormData;
        let input = $(this);
        let fon = "";
        fd.append('img', input.prop('files')[0]);
        fr.addEventListener("load", function () {
           fon = fr.result;
        }, false);
        fr.readAsDataURL(this.files[0]);
        a.css("background-image","url(/img/loading_my.gif)");
        $.ajax({
            url: '{$imageUrl}',
            data: fd,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                if(data.status == 1){
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hidden");
                    s.html("<input type='hidden' name='attachments[]' value='"+data.id+"'>");
                    call_pnotify('success',data.message);
                }else{
                    call_pnotify('fail',data.message);
                }
            },
            error: function(error){
                call_pnotify('fail',error.responseText);
            }
        });
        //b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
    }
});
$(document).on('click', ".udalit", function(e){
    e.preventDefault();
    //$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
    $(this).parent().remove();
});
JS;
$this->registerJs($js,View::POS_READY);
$js = <<< JS
    $("body").delegate(".form-variation","click",function(e){
        e.preventDefault();
        let url = $(this).attr("data-url");
        let list = $(this).parents('.rmParent').find('.rm_order').val();
        let variation = $(this).parents('.modal-content').find('.modal-header');
        variation.html('<button class="btn cansel pull-right"><i class="fa fa-close"></i></button>').show();
        // $("#loading").show();
        variation.load(url+'?list='+list,{'id':'orders'}, function() {
            $("#loading").hide();
        });
        /*$.ajax({
            url: url,
            data: {list:list},
            type: "GET",
            success: function (response) {
                if(response.status == 0){
                    
                }else{
                    call_pnotify('fail');
                }
            }
        });*/
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$required = Yii::t("app","Ushbu maydon to'ldirilishi majburiy");
$saved = Yii::t('app','Saved Successfully');
$infoError = Yii::t('app',"To`ldirish majburiy bo`lgan maydonlarni hammasi  to`ldirilmagan");
$amount = Yii::t('app',"ta qoldi");
$imageUrl = Url::to(['models-variations/file-upload']);
$js = <<< JS
    $("body").delegate(".newAttachments","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let prev = t.prev();
        prev.append('<label class="upload upload-attachments-label"><input type="file" class="upload-attachments"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o bigger-200"></i></span><span class="hiddenAttachments"></span></label>');
        t.attr("num",1*num+1);
    });
    $("body").delegate("input.upload-attachments", "change", function(){
	    var a = $(this).parent();
	    var b = a.parent();
		if (this.files[0]) {
		    var fr = new FileReader();
		    let fd = new FormData;
		    let input = $(this);
		    let fon = "";
            fd.append('img', input.prop('files')[0]);
            fr.addEventListener("load", function () {
               fon = fr.result;
            }, false);
            fr.readAsDataURL(this.files[0]);
            a.css("background-image","url(/img/loading_my.gif)");
            $.ajax({
                url: '{$imageUrl}',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hiddenAttachments");
                    s.html("<input type='hidden' name='ModelVarRelAttach[]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalitAttachments", function(e){
		e.preventDefault();
		$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		$(this).parent().remove();
	});
    function errorInfo(n){
        return "{$infoError} ("+n+" {$amount})";
    }
    $('body').delegate('.send-variation', 'click', function(e){
        try{
            let parent = $(this).parents('.rmParent');
            let content = $(this).parents('.modal-content');
            let indeks = parent.attr('data-row-index');
            let num = 1*content.find('.variations_div').last().find('.num_var').html();
            let number = (num>0)?(1+num):1;
            e.preventDefault();
            let required = $(this).parents('form.formVariation').find(".shart");
            let n = 0;
            $(required).each(function (index, value){
                if($(this).val()==""){
                    $(this).css("border-color","red");
                    if($(this).parent().find(".help-block").length>0){
                        $(this).parent().find(".help-block").css("color","red").html("{$required}");
                    }else{
                        $(this).parent().append("<div class='help-block'></div>");
                        $(this).parent().find(".help-block").css("color","red").html("{$required}");
                    }
                    n++;
                }
            });
            if(n>0){
                let infoError = $(this).parents('form.formVariation').find(".infoErrorForm");
                if(infoError.length==0){
                    $(this).after("<div class='infoErrorForm' style='color:red'>{$infoError}</div>");
                }else{
                    infoError.html(errorInfo(n));
                }
            }else{
                var data = $('.formVariation').serializeArray();
                var url = $('.formVariation').attr('saveUrl');
                $("#loading").show();
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data
                })
                .done(function(response) {
                    if (response.status == 1) {
                        try{
                            let model = response.model;
                            $("#modelList").show();
                            $('#var_'+indeks).html(model.name+'<i><small>('+model.code+')</small></i>');
                            $('#model-var-'+indeks).val(model.id).trigger('change');
                            call_pnotify('success',response.message);
                            // $('#var-modal_'+indeks).modal('hide');
                            content.find('.modal-header').html('');
                            content.find('.check_btn_var').show();
                            content.find('.checked_btn_var').addClass('hidden');
                            content.find('.variations_div').css('background','none');
                            let image = (response.image != '/web/')?'<img src="'+response.image+'" class="thumbnail imageVariationMain imgPreview">':'';
                            let res = '<div class="thumbnail variations_div" style="background:lime">' +
                                    '     <span class="num_var">'+(number)+'</span>' +
                                    '     <div class="caption">' +
                                    '         <div class="row">' +
                                    '             <div class="col-md-12 parent_var">' +
                                    '                 <p>' + image +
                                    '                 </p>' +
                                    '                 <h3 class="item_var_name">'+model.name+' <i><small>('+model.code+')</small></i></h3>' +
                                    '                 <p></p>' +
                                    '                 <p>' +
                                    '                     <button type="button" class="btn btn-success btn-xs check_btn_var" data-id="'+model.id+'" style="display:none">$tanlash</button>' +
                                    '                     <button type="button" class="checked_btn_var btn-success btn btn-xs"><i class="fa fa-check"></i></button>' +
                                    '                     <button type="button" class="btn btn-default btn-xs view_var" data-id="'+model.id+'" data-status="no_load"><i class="fa fa-eye"></i></button>' +
                                    '                 </p>' +
                                    '             </div>' +
                                    '             <div class="col-md-9 parentViewVariation hidden"></div>'+  
                                    '         </div>' +
                                    '     </div>' +
                                    ' </div>';
                            console.log(content);
                            content.find('.flex-container-variations').append(res);
                        }catch (e) {
                            console.log(e);
                            console.log(e.toLocaleString());
                            call_pnotify('fail','Hatolik yuz berdi!');
                        }
                    }else{
                        call_pnotify('fail',response.message);
                        let error = response.messages;
                        if(error){
                            Object.keys(error).map(function(key) {
                                let input = $("[name='ModelsVariations["+key+"]']");
                                input.css("border-color","red");
                                input.parent().find(".help-block").css("color","red").html(error[key][0]);
                            });
                        }
                    }
                })
                .fail(function(error) {
                    call_pnotify('fail',error.responseText)
                });
            }
        }catch(e){
            console.log(e);
        }
    });
    $("body").delegate(".models-variations-form .cansel","click",function(event) {
        event.preventDefault(); // stopping submitting
        $(this).parents('.models-variations-form').hide();
    });
    $("body").delegate(".shart","blur",function(){
        if($(this).val()!=""){
            $(this).css("border-color","green");
            $(this).parent().find(".help-block").html('');
        }else{
            $(this).css("border-color","red");
            if($(this).parent().find(".help-block").length>0){
                $(this).parent().find(".help-block").css("color","red").html("{$required}");
            }else{
                $(this).parent().append("<div class='help-block'></div>");
                $(this).parent().find(".help-block").css("color","red").html("{$required}");
            }    
        }
        let required = $(this).parents('form.formVariation').find(".shart");
        let n = 0;
        $(required).each(function (index, value){
            if($(this).val()==""){
                n++;
                $("#infoErrorForm").html(errorInfo(n));
            }
        });
        if(n==0){
            $("#infoErrorForm").remove();
        }
    });
    $('body').delegate(".search_var","keyup",function(){
        let _this = this;
        let content = $(this).parents('.modal-content');
        let var_div = content.find('.variations_div');
        $.each($(var_div), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
        });
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$infoErrorRaw = Yii::t('app', 'Asosiy mato tanlanishi lozim');
$infoErrorColor = Yii::t('app', 'Asosiy panton rang kodi tanlanishi lozim');
$infoConfirm = Yii::t('app', 'Siz rostdan ham barcha andoza detallarini asosiy mato va asosiy rangalarga o\'zgartirmoqchimisiz?');
\app\widgets\helpers\Script::begin();
?>
    <script>
        $('body').delegate('.makeAllMain', 'change', function (e) {
            let checkbox = $(this).is(':checked');
            let form = $(this).parents('.formVariation');
            let raw = form.find('.toquvRawMaterialId').val();
            let color = form.find('.colorPantoneId').val();
            let boyoq = form.find('.boyoqhonaColorId').val();

            let colorTxt = form.find('.colorPantoneId option:selected').text();
            let rawTxt = form.find('.toquvRawMaterialId option:selected').text();
            let boyoqTxt = form.find('.boyoqhonaColorId option:selected').text();

            if (!raw && checkbox) {
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: "<?= $infoErrorRaw; ?>", type: 'error'});
                $(this).prop("checked", false);
                return false;
            }

            if (checkbox) {
                let confirm = window.confirm("<?=$infoConfirm?>");
                if (confirm) {
                    let objCVB = form.find('.colorVariationBox');
                    let vcp = objCVB.find('.variation-color-pantone');
                    let vrm = objCVB.find('.variation-raw-material');
                    let bcp = objCVB.find('.variation-color-pantone-boyoqhona');

                    if (vcp) {
                        vcp.each(function (key, val) {
                            let newOption = new Option(colorTxt, color, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(color).trigger('change');
                        });
                    }
                    if (vrm) {
                        vrm.each(function (key, val) {
                            let newOption = new Option(rawTxt, raw, true, true);
                            let check = $(val).find('option[value='+raw+']');
                            if(!check) {
                                $(val).append(newOption).trigger('change');
                            }
                            $(val).val(raw).trigger('change');
                        });
                    }
                    if (bcp) {
                        bcp.each(function (key, val) {
                            let newOption = new Option(boyoqTxt, boyoq, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(boyoq).trigger('change');
                        });
                    }
                }
            }
        });
    </script>
<?php \app\widgets\helpers\Script::end();
$viewVarUrl = Yii::$app->urlManager->createUrl('base/models-variations/colors');
$js = <<< JS
    $('body').delegate('.view_var', 'click', function(e){
        let t = $(this);
        let parent = t.parents('.variations_div');
        if(t.attr('data-status')=='no_load'){
            parent.find('.parentViewVariation').load('$viewVarUrl?id='+t.attr('data-id'));
        }
        if(t.attr('data-status')!='open'){
            t.parents('.parent_var').removeClass('col-md-12').addClass('col-md-3');
            parent.addClass('open-var');
            parent.find('.parentViewVariation').removeClass('hidden');
            t.attr('data-status','open');
            t.find('i').removeClass('fa-eye').addClass('fa-close');
            t.removeClass('btn-default').addClass('btn-danger');
        }else{
            t.parents('.parent_var').removeClass('col-md-3').addClass('col-md-12');
            parent.removeClass('open-var');
            parent.find('.parentViewVariation').addClass('hidden');
            t.attr('data-status','hidden');
            t.find('i').removeClass('fa-close').addClass('fa-eye');
            t.removeClass('btn-danger').addClass('btn-default');
        }
    });
    $('body').delegate('.check_btn_var', 'click', function(e){
            let t = $(this);
            console.log(t);
            let rm_parent = t.parents('.rmParent');
            console.log(rm_parent);
            rm_parent.find('.check_btn_var').show();
            console.log('show');
            rm_parent.find('.checked_btn_var').addClass('hidden');
            console.log('show2');
            rm_parent.find('.variations_div').css('background','none');
            console.log('none');
            rm_parent.find('.model_var_id').val(t.attr('data-id')).trigger('change');
            console.log('change');
            let modal = $(this).parents('.modal');
            console.log(modal);
            let name = t.parents('.parent_var').find('.item_var_name').html();
            rm_parent.find('.var_name').html(name);
            t.parents('.variations_div').css('background','lime');
            t.hide();
            t.next().removeClass('hidden');
            $(modal).modal('hide');
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
$css = <<< CSS
    .flex-container-variations{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .variations_div{
        width: 7vw;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        text-align: center;
        position: relative;
    }
    .variations_div *{
        font-size: 1em;
    }
    .variations_div .imgPreview{
        width: 100%;
    }
    .open-var{
        width: 90vw;
    }
    .num_var{
        position: absolute;
        top: 0;
        left: 20px;
    }
    .parent_var{
        min-height: 65px;
    }
    .button-var{
        padding-top: 5px;
    }
CSS;
$this->registerCss($css);
$url_search_acs = Url::to('search-acs');
$js = <<< JS
    var table;
    $("body").delegate(".add_acs","click",function(){
        $('#acs-modal').modal('show');
        $('#acs-modal').attr('data-row-index',$(this).attr('data-row-index'));
    });
    $("body").delegate(".check_acs","click",function(){
        let index = $("#acs-modal").attr('data-row-index');
        let table = $('#table_acs_'+index);
        let t = $(this);
        let parent = t.parents('.acs_div');
        let pr_id = t.attr('data-id');
        let pr_unit_id = t.attr('data-unit_id');
        let pr_name = parent.find('.pr_name').html();
        let pr_artikul = parent.find('.pr_artikul').html();
        let pr_turi = parent.find('.pr_turi').html();
        let pr_unit = parent.find('.pr_unit').html();
        let pr_barcod = parent.find('.pr_barcod').html();
        let pr_add_info = parent.find('.pr_add_info').html();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = table.find('.row_'+pr_id);
        let tbody = table.find('tbody');
        let last_tr = tbody.find('tr').last();
        let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
        let image = (pr_image!=null)?'<img class="imgPreview pr_image" src="'+pr_image+'">':'';
        if(check_row.length==0){
            table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+'" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__artikul">' +
                '                                    <span type="text" class="form-control" disabled="">' +
                                                        pr_artikul +
                                                     '</span>'+
                '                                </td>' +
                '                                <td class="list-cell__name">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_name+'</span>' +
                '                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][id]" value="'+pr_id+'"><input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][unit_id]" value="'+pr_unit_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__turi">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_turi+'</span>' +
                '                               <td class="list-cell__qty">' +
                '                                   <input type="text" class="acs_input form-control number" name="ModelOrdersItems['+index+'][acs]['+row_index+'][qty]">' +
                '                               </td>' +
                '                                </td>' +
                '                                <td class="list-cell__unit_id">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_unit+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__barcod">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_barcod+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__add_info">' +
                '                                   <input type="text" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][add_info]" value="'+pr_add_info+'">' +
                '                                </td>' +
                '                                <td class="list-cell__acs_image">' +
                                                     image+
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            call_pnotify('success','Muvaffaqqiyatli saqlandi');
        }else{
            call_pnotify('fail',"Siz buni tanlab bo'lgansiz")
        }
        let count = tbody.find('tr').length;
        $('#acs_'+index).val(count).trigger('change');
    });
    $('body').delegate('.removeTr', 'click', function(e){
        let tbody = $(this).parents('tbody');
        let acsCount = $(this).parents('.acs');
        $(this).parents('tr').remove();
        let count = tbody.find('tr').length;
        acsCount.find('.acs_count').val(count);
    });
    var list = [];
    $('body').delegate("#search_acs","keyup",function(){
        let _this = this;
        list = [];
        $.each($(".acs_div"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list.push($(this).data('id'));
        });
    });
    $('body').delegate('#search_button_acs', 'click', function(e){
        let search = $("#search_acs");
        if(search.val()==""){
            call_pnotify('fail', 'Qidirish uchun biror narsa yozing');
        }else{
            $.ajax({
                url: '{$url_search_acs}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: search.val(),
                    list: list
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.model;
                    dataList.map(function(key) {
                        let artikul = (key.artikul!=null)?key.artikul:'';
                        let image = (key.image!=null)?'<img class="imgPreview" src="'+key.image+'" style="width: 40px;min-height: 5vh;">':'';
                        li += '<div class="acs_div" id="acs_div_'+key.id+'" data-id="'+key.id+'">' +
                                '    <div class="media">' +
                                '        <div class="media-left">' + image +
                                '        </div>' +
                                '        <div class="media-body">' +
                                '            <h5 class="pr_artikul"><small>'+artikul+'</small></h5>' +
                                '            <h4 class="media-heading pr_name">'+key.name+'</h4>' +
                                '            <h5 class="pr_turi"><small>'+key.turi+'</small></h5>' +
                                '            <h5 class="pr_unit"><small>'+key.unit+'</small></h5>' +
                                '            <h5 class="pr_barcod"><small>'+key.barcod+'</small></h5>' +
                                '            <h5 class="hidden pr_add_info">'+key.add_info+'</h5>' +
                                '        </div>' +
                                '    </div>' +
                                '    <div class="text-center check_button">' +
                                '        <span class="btn btn-success' +
                                '            btn-xs check_acs" data-id="'+key.id+'" data-unit_id="'+key.unit_id+'">$tanlash</span>' +
                                '    </div>' +
                                '</div>';
                        list.push(key.id);
                    });
                    $('.list_acs').append(li);
                }else{
                    call_pnotify('fail',response.message);
                }
            });
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
    .percentage_div,.priority_div{
        display: none;
    }
    .changedItem{
        background: gainsboro!important;
    }
    .savedItem{
        background: #aff29a!important;
    }
CSS;
$this->registerCss($css);
$this->registerJsFile('/select2/custom_select2.js', ['depends' => \app\assets\AppAsset::className()]);
$this->registerCssFile('/select2/custom_select2.css');