<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\bichuv\models\BichuvMatoOrders;
use app\widgets\helpers\Script;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $mato_orders app\modules\bichuv\models\BichuvMatoOrders */
/* @var $trm_list array */
/* @var $form yii\widgets\ActiveForm */
$list = \app\modules\bichuv\models\BichuvMatoSearch::getRmInfo();
?>
<?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxForm']]); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t('app','Sana')],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'add_info')->textarea(['rows' => 1]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_department')->widget(Select2::className(),[
            'data' => $model->getDepartmentsBelongTo()
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->widget(Select2::className(),[
            'data' => $model->getDepartmentByToken(['BICHUV_DEP'], true),
            'options' => [
                'placeholder' => Yii::t('app', 'Select'),
                'allowClear' => true,
            ],
            'pluginOptions' => ['allowClear' => true]
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->widget(Select2::className(),[
            'data' => $model->getEmployees()
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_employee')->widget(Select2::className(),[
            'data' => $model->getEmployees()
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        $labelPartyNo = Yii::t('app','Partiya No');
        $labelMusteriPartyNo = Yii::t('app','Musteri Partiya No');
        $defaultMustei = \app\models\Constants::$NillGranitID;
        if(empty($model->musteri_id)){
            $model->musteri_id = $defaultMustei;
        }
        echo $form->field($model, 'musteri_id')->widget(Select2::className(),[
            'data' => $model->getMusteries(),
            'pluginEvents' => [
                "change" => new JsExpression("function(e){
                        let id = $(this).val();
                        if(id == '$defaultMustei'){
                            $('.field-bichuvdoc-barcode label').html('$labelPartyNo');
                        }else{
                            $('.field-bichuvdoc-barcode label').html('$labelMusteriPartyNo');
                        }
                    }")
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
    </div>
</div>
    <br>
    <h4><?php echo Yii::t('app','Model')?> : <?=($mato_orders->moi)?$mato_orders->moi->info:''?></h4>
<div class="document-items">
    <?php
    $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);
    $fromDepId = Html::getInputId($model, 'from_department');
    $this->registerJsVar('dep_fail_msg', Yii::t('app','Bo\'limni tanlang'));
    ?>
    <?= (!empty($models))?CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $models,
        'theme' => 'bs',
        'showFooter' => true,
        'attributes' => [
            [
                'id' => 'footer_name',
                'value' => Yii::t('app', 'Jami')
            ],
            [
                'id' => 'footer_document_qty',
                'value' => 0
            ],
            [
                'id' => 'footer_entity_id',
                'value' => null
            ],
            [
                'id' => 'footer_en_gramaj',
                'value' => null
            ],
            [
                'id' => 'footer_party_no',
                'value' => null
            ],
            [
                'id' => 'footer_roll_count_remain',
                'value' => 0
            ],
            [
                'id' => 'footer_roll_count',
                'value' => 0
            ],
            [
                'id' => 'footer_roll_weight',
                'value' => ''
            ],
            [
                'id' => 'footer_quantity',
                'value' => 0
            ],
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 100,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success hidden',
        ],
        'cloneButton' => true,
        'columns' => [
            [
                'type' => 'hiddenInput',
                'name' => 'bichuv_mato_order_items_id',
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'entity_type',
                'defaultValue' => 2
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'is_accessory',
                'defaultValue' => 1,
                'options' => [
                    'class' => 'is-accessory',
                ],
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'bss_id',
                'defaultValue' => 1,
                'options' => [
                    'class' => 'bss-id',
                ],
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'entity_id',
                'options' => [
                    'class' => 'entity-id',
                ],
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'party_no',
                'options' => [
                    'class' => 'party-no',
                ],
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'musteri_party_no',
                'options' => [
                    'class' => 'musteri-party-no',
                ],
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'model_id',
                'options' => [
                    'class' => 'model-id',
                ],
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'musteri_id',
                'options' => [
                    'class' => 'item_musteri_id',
                ],
            ],
            [
                'name' => 'name',
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'type' => Select2::className(),
                'options' => [
                    'data' => $trm_list['{multiple_index_documentitems_id}'],
                    'options' => [
                        'placeholder' => Yii::t('app','Placeholder Select'),
                        'class' => 'name',
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
                ],
                'value' => function($model){
                    return $model->bichuvMatoOrderItems->trmName;
                },
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ]
            ],
            [
                'name' => 'document_qty',
                'title' => Yii::t('app', 'Berilishi kerak'),
                'options' => [
                    'class' => 'document_qty',
                    'readonly' => true
                ],
                'value' => function($model){
                    return $model->bichuvMatoOrderItems->quantity;
                },
                'headerOptions' => [
                    'style' => 'width: 80px;',
                ]
            ],
            [
                'name' => 'given_qty',
                'title' => Yii::t('app', 'Berilgan miqdor'),
                'options' => [
                    'class' => 'given_qty',
                    'disabled' => true
                ],
                'value' => function($model){
                    return (!$model->isNewRecord)?$model->given:$model->given_qty;
                },
                'headerOptions' => [
                    'style' => 'width: 80px;',
                ]
            ],
            [
                'name' => 'brib_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' => $list['list'],
                    'options' => [
                        'placeholder' => Yii::t('app','Placeholder Select'),
                        'class' => 'mato_select',
                        'options' => $list['options']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
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
                ],
                'headerOptions' => [
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'party',
                'title' => Yii::t('app','Part./Mijoz No'),
                'options' => [
                    'class' => 'roll-party',
                    'disabled' => true,
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ],
                'value' => function($model){
                    return $model->party_no.'/'.$model->musteri_party_no;
                }
            ],
            [
                'name' => 'en_gramaj',
                'title' => Yii::t('app', "En/gramaj"),
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell-mato en-gramaj',
                ],
                'value' => function($model){
                    $sub = $model->getSubItem();
                    if($model->is_accessory == 2){
                        return Yii::t('app','Aksessuar');
                    }
                    return number_format($sub['en'],0)." sm / ".number_format($sub['gramaj'],0)." gr/m2";
                },
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ]
            ],
            [
                'name' => 'roll_count_remain',
                'title' => Yii::t('app','Rulon soni(qol.)'),
                'options' => [
                    'class' => 'roll-count-remain',
                    'disabled' => true,
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ],
                'value' => function($model){
                    return $model->getRemainFromMusteriItemBalance('roll');
                }
            ],
            [
                'name' => 'roll_count',
                'title' => Yii::t('app','Rulon soni'),
                'options' => [
                    'class' => 'roll-count number'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ],
            ],
            [
                'name' => 'roll_weight',
                'title' => Yii::t('app', "Mavjud qol.(kg)"),
                'defaultValue' => 0,
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell-mato roll-weight',
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'remain-item-cell outgoing-multiple-input-cell'
                ],
                'value' => function($model){
                    return $model->getRemainFromMusteriItemBalance('quantity');
                }
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Mato miq.(kg)'),
                'options' => [
                    'class' => 'tabular-cell-mato roll-fact number',
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ]
            ],
        ]
    ]):"";
    ?>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$formId = $form->getId();
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$musId = Html::getInputId($model,'musteri_id');
$toEmp = Html::getInputId($model, 'to_employee');
$urlDep = Url::to(['doc/get-department-user', 'slug' => $this->context->slug]);
$urlGetMato = Url::to(['doc/get-rm-info', 'slug' => $this->context->slug]);
$accessoryText = Yii::t('app','Aksessuar');
Script::begin();
?>
<script>
    $('#<?= $formId; ?>').keypress(function (e) {
        if (e.which == 13) {
            return false;
        }
    });
    function calculateSum(id, className){
        let rmParty = $('#documentitems_id table tbody tr').find(className);
        if(rmParty){
            let totalRMParty = 0;
            rmParty.each(function (key, item) {
                if($(item).val()){
                    totalRMParty += parseFloat($(item).val());
                }
            });
            $(id).html(totalRMParty.toFixed(2));
        }
    }
    $('#documentitems_id').on('afterInit', function (e, index) {
        calculateSum('#footer_roll_weight','.roll-weight');
        calculateSum('#footer_quantity','.roll-fact');
        calculateSum('#footer_roll_count_remain','.roll-count-remain');
        calculateSum('#footer_roll_count','.roll-count');
    });
    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
        calculateSum('#footer_roll_weight','.roll-weight');
        calculateSum('#footer_quantity','.roll-fact');
        calculateSum('#footer_roll_count_remain','.roll-count-remain');
        calculateSum('#footer_roll_count','.roll-count');
    });
    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
        calculateSum('#footer_roll_weight','.roll-weight');
        calculateSum('#footer_quantity','.roll-fact');
        calculateSum('#footer_roll_count_remain','.roll-count-remain');
        calculateSum('#footer_roll_count','.roll-count');
    });
    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
        calculateSum('#footer_roll_weight','.roll-weight');
        calculateSum('#footer_quantity','.roll-fact');
        calculateSum('#footer_roll_count_remain','.roll-count-remain');
        calculateSum('#footer_roll_count','.roll-count');
    });
    $('body').delegate('.roll-fact', 'keyup', function (e) {
        let roll_weight = $(this).parents('tr').find('.roll-weight').val();
        if(1*$(this).val()>1*roll_weight){
            $(this).val(roll_weight);
        }
    });
    $('body').delegate('.roll-count', 'keyup', function(e){
        let t = $(this);
        let remain = t.parents('tr').find('.roll-count-remain').val();
        if(1*t.val()>1*remain){
            t.val(remain);
        }
    });
    $('body').delegate('.mato_select','change', function (e) {
        let t = $(this);
        let parent = t.parents('tr');
        let item = JSON.parse(t.find('option:selected').attr('data-list'));
        let enGramaj = (item.mato_en * 1).toFixed(0) + ' sm / ' + (item.gramaj * 1).toFixed(0) + ' gr/m2';
        let name = item.mato + '-' + item['ne'] + '-' + item['ip'] + '|' + item['pus_fine'] + ' (' + item['mname'] + ') (' + item['model'] + ')';
        if (item.is_accessory == 2) {
            name = item.mato + '-' + item['ip'] + ' (' + item['mname'] + ') (' + item['model'] + ')';
            enGramaj = "<?= $accessoryText; ?>"
        }
        parent.find('.roll-weight').val(item.rulon_kg);
        let rulon_count = 1*item.rulon_count;
        if(rulon_count&&rulon_count!==''){
            parent.find('.roll-count').val(rulon_count.toFixed(0));
            parent.find('.roll-count-remain').val(rulon_count.toFixed(0));
        }
        parent.find('.roll-party').val(item.party_no+'/'+item.musteri_party_no);
        parent.find('.party-no').val(item.party_no);
        parent.find('.musteri-party-no').val(item.musteri_party_no);
        parent.find('.model-id').val(item.model_id);
        parent.find('.roll-fact').val(item.rulon_kg);
        parent.find('.en-gramaj').val(enGramaj);
        parent.find('.is-accessory').val(item.is_accessory);
        parent.find('.bss-id').val(item.bss_id);
        parent.find('.entity-id').val(item.entity_id);
        parent.find('.item_musteri_id').val(item.from_musteri);
        calculateSum('#footer_roll_weight','.roll-weight');
        calculateSum('#footer_quantity','.roll-fact');
        calculateSum('#footer_roll_count_remain','.roll-count-remain');
        calculateSum('#footer_roll_count','.roll-count');
    });
    $('body').delegate('.roll-weight', 'change', function(e){
        calculateSum('#footer_roll_weight','.roll-weight');
    });
    $('body').delegate('.roll-fact', 'change', function(e){
        calculateSum('#footer_roll_fact','.roll-fact');
    });
    $('body').delegate('.roll-count-remain', 'change', function(e){
        calculateSum('#footer_roll_count_remain','.roll-count-remain');
    });
    $('body').delegate('.roll-count', 'change', function(e){
        calculateSum('#footer_roll_count','.roll-count');
    });
    $('#<?= $toDepId; ?>').on('change', function(e){
        let id = $(this).find('option:selected').val();
        $.ajax({
            url: '<?= $urlDep; ?>?id='+id,
            success: function(response){
                if(response.status == 1){
                    var option = new Option(response.name, response.id);
                    $('#<?= $toEmp; ?>').find('option').remove().end().append(option).val(response.id);
                }
            }
        });
    });
</script>
<?php Script::end();?>
<?php
$css = <<< CSS
    .list-cell__brib_id,.list-cell__brib_id > div,.list-cell__name,.list-cell__name > div{
        width: 275px;
        min-width: 200px;
    }
    /*.list-cell__name:hover,.list-cell__name:hover > div{
        width: 900px;
        position:absolute;
        z-index: 999999999999999;
    }
    .list-cell__brib_id:hover,.list-cell__brib_id:hover > div{
        width: auto;
        position:absolute;
        z-index: 999999999999999;
    }*/
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 3px 1px;
    }
    /*.table tbody tr td:hover,.table tbody tr td:hover .list-cell__name,.table tbody tr td:hover .list-cell__name > div{
        position:absolute;
        width: auto;
    }*/
    /*.list-cell__button{
        display: none;
    }*/
CSS;
$this->registerCss($css);