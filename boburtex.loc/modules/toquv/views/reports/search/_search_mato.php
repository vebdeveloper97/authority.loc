<?php

use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $from_model boolean|integer */
/* @var $entity_type boolean|integer */
?>
<?php
$url_musteri = Url::to('musteri');
$url_order = Url::to('order');
$url_order_items = Url::to('order-items');
$js = <<<JS
function formatDate(date,join) {
    let d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [day, month, year].join(join);
}
JS;
$this->registerJs($js,\yii\web\View::POS_HEAD);
/*$this->registerJs(
    '$("document").ready(function(){
            $("#reportSearchFormIncoming").on("pjax:end", function() {
                $.pjax.reload({container:"#reportResultIncoming"}).done(function(){
                    $("table").tableExport({
                        headers: true,
                        footers: true,
                        formats: ["xlsx", "csv", "xls"],
                        filename: "id",
                        bootstrap: true,
                        exportButtons: true,
                        position: "top",
                        ignoreRows: null,
                        ignoreCols: null,
                        trimWhitespace: true,
                        RTL: false,
                        sheetname: "id",
                    });
                });
            });
    });'
);*/
?>
<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => $url ?? Url::to(['report-mato-sklad']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
                    <?= $form->field($model, 'created_at')->widget(\kartik\daterange\DateRangePicker::className(),[
                        'model' => $model,
                        'attribute'=>'created_at',
                        'convertFormat'=>true,
                        'startAttribute' => 'from_date',
                        'endAttribute' => 'to_date',
                        'pluginOptions'=>[
                            'showDropdowns'=>true,
                            'allowClear' => true,
                            'timePicker'=>true,
                            'timePickerIncrement'=>1,
                            'timePicker24Hour' => true,
                            'language' => 'uz-latn',
                            'locale'=>[
                                'format'=>'Y-m-d H:i:s',
                                "applyLabel" => "Tanlash",
                                "cancelLabel" => "Bekor",
                                "fromLabel" => "Dan",
                                "toLabel" => "Gacha",
                                "customRangeLabel" => "Tanlangan",
                                "daysOfWeek" => [
                                    "Ya",
                                    "Du",
                                    "Se",
                                    "Ch",
                                    "Pa",
                                    "Ju",
                                    "Sh"
                                ],
                                "monthNames" => [
                                    "Yanvar",
                                    "Fevral",
                                    "Mart",
                                    "Aprel",
                                    "May",
                                    "Iyun",
                                    "Iyul",
                                    "Avgust",
                                    "Sentabr",
                                    "Oktabr",
                                    "Noyabr",
                                    "Dekabr"
                                ],
                                "firstDay" => 1
                            ],
                            'ranges'=>[
                                Yii::t('app', "Bugun") => ["moment().startOf('day')", "moment()"],
                                Yii::t('app', "Kecha") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                                Yii::t('app', "Ohirgi {n} kun", ['n' => 7]) => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                                Yii::t('app', "Ohirgi {n} kun", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                                Yii::t('app', "Shu oy") => ["moment().startOf('month')", "moment().endOf('month')"],
                                Yii::t('app', "O'tgan oy") => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                            ],
                        ],
                    ])->label(false)?>
                </div>
                <div class="col-md-6">
                    <?php echo $form->field($model, 'musteri_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvMusteri::getMyMusteri(),
                        'size' => Select2::SIZE_SMALL,
                        'options' => ['placeholder' => Yii::t('app', 'Select')],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => true,
                        ],
                        'pluginEvents' => [
                            "change" => new JsExpression("function(e) { 
                                var id = $(this).val();
                                var musteri = $('#orders_id');
                                $.ajax({
                                    url:'{$url_musteri}?id='+id,
                                    success: function(response){
                                        if(response.status){
                                            var dataTypeId = response.data;
                                            musteri.html('');
                                            dataTypeId.map(function(val, k){
                                                var newOption = new Option(val.doc_number +' ('+ val.musteri +') ('+ formatDate(val.reg_date,'.') +')', val.id, false, false);
                                                musteri.append(newOption);
                                            });
                                            musteri.trigger('change');
                                        }else{
                                           musteri.html('');
                                        }
                                    }
                                }); 
                            }"),
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <?php $label = ($entity_type)?Yii::t('app', 'Aksessuarlar'):Yii::t('app', 'Matolarni tanlash');?>
                    <?= $form->field($model, 'entity_ids')->widget(Select2::className(),[
                        'data' => \app\modules\base\models\ModelsRawMaterials::getMaterialList($entity_type),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { return markup; }"
                            ),
                            'templateResult' => new JsExpression(
                                "function(data) { return data.text; }"
                            ),
                            'templateSelection' => new JsExpression(
                                "function (data) { return data.text; }"
                            ),
                        ],
                    ])->label($label) ?>
                </div>
                <div class="col-md-6">
                    <?=(!$search_mato)?$form->field($model,'department_id')->dropDownList($model->getBelongToDepartments(),['prompt' => Yii::t('app', "Bo'lim tanlang")])->label(Yii::t('app', "Bo'lim")):"<br>".$form->field($model, 'group_by_type', ['template' => '<label class="checkbox-transform">{input}
                        <span class="checkbox__label">'.Yii::t("app","Mato turi bo'yicha guruhlash").'</span>
                    </label>',])->checkbox(['class' => 'checkbox__input'], false)?>
                    <?/*=($from_model)?$form->field($model,'model_id')->widget(Select2::className(),[
                        'data' => \app\modules\base\models\ModelOrders::getModelList(),
                        'options' => [
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { return markup; }"
                            ),
                            'templateResult' => new JsExpression(
                                "function(data) { return data.text; }"
                            ),
                            'templateSelection' => new JsExpression(
                                "function (data) { return data.text; }"
                            ),
                        ],
                    ]):'' */?>
                </div>
            </div>
            <?php if($search_mato){?>
            <div class="form-group row">
                <div class="col-md-6">
                    <?= $form->field($model, 'user_id')->widget(Select2::className(),[
                        'data' => \app\models\Users::getUserList(null,'TOQUV_TOQUVCHI'),
                        'options' => [
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { return markup; }"
                            ),
                            'templateResult' => new JsExpression(
                                "function(data) { return data.text; }"
                            ),
                            'templateSelection' => new JsExpression(
                                "function (data) { return data.text; }"
                            ),
                        ],
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <br>
                    <?= $form->field($model, 'group_by_user', ['template' => '<label class="checkbox-transform">{input}
                        <span class="checkbox__label">'.Yii::t("app","To'quvchi bo'yicha guruhlash").'</span>
                    </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                </div>
            </div>
            <?php }?>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?php $url = (!$from_model)?((!$search_mato)?Url::to(['report-mato-sklad']):Url::to(['report-kalite'])):Url::to(['report-model-mato-sklad'])?>
                    <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
                    <?= ($search_mato)?$form->field($model, 'makine_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvKalite::getMakineList(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { return markup; }"
                            ),
                            'templateResult' => new JsExpression(
                                "function(data) { return data.text; }"
                            ),
                            'templateSelection' => new JsExpression(
                                "function (data) { return data.text; }"
                            ),
                        ],
                    ]):$form->field($model, 'model_musteri_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvMusteri::getMyMusteri(),
                        'size' => Select2::SIZE_SMALL,
                        'options' => ['placeholder' => Yii::t('app', 'Select')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'pluginEvents' => [
                            "change" => new JsExpression("function(e) { 
                                var id = $(this).val();
                                var musteri = $('#orders_id');
                                $.ajax({
                                    url:'{$url_musteri}?id='+id,
                                    success: function(response){
                                        if(response.status){
                                            var dataTypeId = response.data;
                                            musteri.html('');
                                            dataTypeId.map(function(val, k){
                                                var newOption = new Option(val.doc_number +' ('+ val.musteri +') ('+ formatDate(val.reg_date,'.') +')', val.id, false, false);
                                                musteri.append(newOption);
                                            });
                                            musteri.trigger('change');
                                        }else{
                                           musteri.html('');
                                        }
                                    }
                                }); 
                            }"),
                        ]
                    ]) ?>
                </div>
                <div class="row col-md-8">
                    <div class="col-md-4">
                        <?= $form->field($model, 'thread_length')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'finish_en')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'finish_gramaj')->textInput() ?>
                    </div>
                </div>
            </div>
            <?/*=($from_model)?$form->field($model, 'orders_id')->widget(Select2::classname(), [
                'data' => ($id = $_GET['ToquvDocumentBalanceSearch']['musteri_id'])?\app\modules\base\models\ModelOrders::getOrdersList($id):[],
                'size' => Select2::SIZE_SMALL,
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                    'id' => 'orders_id'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "change" => new JsExpression("function(e) { 
                        var id = $(this).val();
                        var order = $('#order_items');
                        $.ajax({
                            url:'{$url_order}?id='+id,
                            success: function(response){
                                if(response.status){
                                    var dataTypeId = response.data;
                                    order.html('');
                                    dataTypeId.map(function(val, k){
                                        var newOption = new Option(val.doc_number +' ('+ val.model +' - '+ val.code +')' +' ('+ val.size_type +')' +' ('+ val.summa +')' +' ('+ formatDate(val.load_date,'.') +')', val.id, false, false);
                                        order.append(newOption);
                                    });
                                    order.trigger('change');
                                }else{
                                   order.html('');
                                }
                            }
                        }); 
                    }"),
                ]
            ]):'' */?><!--
            --><?/*=($from_model)?$form->field($model,'moi_id')->widget(Select2::className(),[
                'data' => ($orders_id = $_GET['ToquvDocumentBalanceSearch']['orders_id'])?\app\modules\base\models\ModelOrders::getOrderItemsList($orders_id):[],
                'options' => [
                    'prompt' =>Yii::t('app','Barchasi'),
                    'id' => 'order_items'
                ],
                'size' => Select2::SIZE_SMALL,
                'pluginOptions' => [
                    'allowClear' => true,
                    'escapeMarkup' => new JsExpression(
                        "function (markup) { return markup; }"
                    ),
                    'templateResult' => new JsExpression(
                        "function(data) { return data.text; }"
                    ),
                    'templateSelection' => new JsExpression(
                        "function (data) { return data.text; }"
                    ),
                ],
            ]):'' */?>
            <div class="row">
                <div class="col-md-4">
                    <?= (!$entity_type)?$form->field($model, 'sort_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvMakine::getSortNameList(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]):""
                    ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'pus_fine')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvPusFine::getList(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'placeholder' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <br>
                    <?=$form->field($model,'group_by_ip', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">   &nbsp;&nbsp;&nbsp;'.Yii::t("app","Buyurtma bo'yicha guruhlash").'</span>
            </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                </div>
            </div>
            <?php if($search_mato){?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'user_kalite_id')->widget(Select2::className(),[
                            'data' => \app\models\Users::getUserList(null,'TOQUV_KALITE'),
                            'options' => [
                                'prompt' =>Yii::t('app','Barchasi')
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'escapeMarkup' => new JsExpression(
                                    "function (markup) { return markup; }"
                                ),
                                'templateResult' => new JsExpression(
                                    "function(data) { return data.text; }"
                                ),
                                'templateSelection' => new JsExpression(
                                    "function (data) { return data.text; }"
                                ),
                            ],
                        ]) ?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <?=($from_model)?$form->field($model, 'from_model')->hiddenInput(['value' => 1])->label(false):'' ?>
    <?php ActiveForm::end(); ?>
</div>
