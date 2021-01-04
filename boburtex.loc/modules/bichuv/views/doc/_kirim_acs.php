<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\hr\models\HrDepartments;
use kartik\tree\TreeViewInput;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelOrders \app\modules\base\models\ModelOrders */

?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INCOMING])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1])->label('Asos'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                    'data' => $model->getMusteries()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_hr_department')->widget(TreeViewInput::class, [
                'id' => 'tree-from_hr_department',
                'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::OWN_DEPARTMENT_TYPE),
                'headingOptions' => ['label' => Yii::t('app', "From department")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => ['disabled' => false],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...'),
                    ]
                ]
            ]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'musteri_responsible')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'model_orders_id')->widget(Select2::class, [
                'data' => BichuvDoc::getModelOrdersMapList(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Model Orders Select'),
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_hr_employee')->widget(Select2::className(),[
                    'data' => $model->getHrEmployees()
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= Collapse::widget([
                'items' => [
                    [
                        'label' => Yii::t('app','Harajatlar'),
                        'content' => $this->render('_document_expenses', ['form' => $form, 'modelTDE' => $modelTDE]),
                        'contentOptions' => []
                    ]
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <p class="text-yellow">
                <i class="fa fa-info-circle"></i>&nbsp;
                <i><b>F9</b> - <small><?= Yii::t('app','Yangi qator qo\'shish')?></small></i>&nbsp;&nbsp;&nbsp;
                <i><b>F8</b> - <small><?= Yii::t('app','So\'nggi qatorni o\'chirish')?></small></i>
            </p>
        </div>
        <div class="col-md-6">
                <?= Html::textInput('barcode', null, ['id'=> 'barcodeInput', 'autofocus'=>true, 'class'=>'pull-right col-md-6 customCard']) ?>
                <?= Html::label(Yii::t('app', 'Barcode'), 'barcodeInput', ['class'=>'pull-right mr2 text-primary']) ?>
        </div>
    </div>

    <div class="document-items">
        <?php $accessoriesList = $model->getAccessories(null,true);?>
       <?= CustomTabularInput::widget([
           'id' => 'documentitems_id',
           'form' => $form,
           'models' => $models,
           'theme' => 'bs',
           'showFooter' => true,
           'attributes' => [
               [
                   'id' => 'footer_entity_id',
                   'value' => Yii::t('app', 'Jami')
               ],
               [
                   'id' => 'footer_price_sum',
                   'value' => 0
               ],
               [
                   'id' => 'footer_price_usd',
                   'value' => 0
               ],
               [
                   'id' => 'footer_quantity',
                   'value' => 0
               ],
               [
                   'id' => 'footer_summa',
                   'value' => 0
               ],
               [
                   'id' => 'footer_summa_usd',
                   'value' => 0
               ]
           ],
           'rowOptions' => [
               'id' => 'row{multiple_index_documentitems_id}',
               'data-row-index' => '{multiple_index_documentitems_id}'
           ],
           'max' => 100,
           'min' => 0,
           'addButtonPosition' => CustomMultipleInput::POS_HEADER,
           'addButtonOptions' => [
               'class' => 'hide',
           ],
            'removeButtonOptions' => [
                'class' => 'hide',
            ],
           'cloneButton' => false,
           'columns' => [
               [
                   'type' => 'hiddenInput',
                   'name' => 'document_quantity' ,
                   'defaultValue' => 0
               ],
               [
                   'name' => 'entity_id',
                   'type' => Select2::className(),
                   'title' => Yii::t('app', 'Maxsulot nomi'),
                   //'defaultValue' => 1,
                   'options' => [
                       'data' => $accessoriesList['data'],
                       'options' => [
                           'placeholder' => Yii::t('app','Placeholder Select'),
                           'multiple' => false,
                           'class' => 'entity_id',
                           'options' => $accessoriesList['barcodeAttr']
                       ]
                   ],
                   'headerOptions' => [
                       'style' => 'width: 30%;',
                       'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                   ]
               ],
               [
                   'name' => 'price_sum',
                   'title' => Yii::t('app', "Narxi(So'm)"),
                   'options' => [
                       'step' => '0.001',
                       'type' => 'number',
                       'min' => 0.000,
                       'class' => 'tabular-cell',
                       'field' => 'price_sum'
                   ],
                   'headerOptions' => [
                       'style' => 'width: 100px;',
                       'class' => 'price_sum-item-cell incoming-multiple-input-cell',
                       'data-field-name' => 'price_sum'
                   ]
               ],
               [
                   'name' => 'price_usd',
                   'title' => Yii::t('app', 'Narxi($)'),
                   'options' => [
                       'step' => '0.001',
                       'type' => 'number',
                       'min' => 0.000,
                       'class' => 'tabular-cell',
                       'field' => 'price_usd'
                   ],
                   'headerOptions' => [
                       'style' => 'width: 100px;',
                       'class' => 'price_usd-item-cell incoming-multiple-input-cell'
                   ]
               ],
               [
                   'name' => 'quantity',
                   'title' => Yii::t('app', 'Soni'),
                   'defaultValue' => 1,
                   'options' => [
                       'step' => '0.001',
                       'type' => 'number',
                       'min' => 0,
                       'class' => 'qty tabular-cell',
                       'field' => 'quantity',
                   ],
                   'headerOptions' => [
                       'style' => 'width: 100px;',
                       'class' => 'quantity-item-cell incoming-multiple-input-cell'
                   ]
               ],
               [
                   'name' => 'summa',
                   'title' => Yii::t('app', 'Summa (UZS)'),
                   'value' => function ($model) {
                       return $model->getSum();
                   },
                   'options' => [
                       'disabled' => true,
                       'class' => 'tabular-cell',
                       'field' => 'summa'
                   ],
                   'headerOptions' => [
                       'style' => 'width: 100px;',
                       'class' => 'summa-item-cell incoming-multiple-input-cell'
                   ]
               ],
               [
                   'name' => 'summa_usd',
                   'title' => Yii::t('app', 'Summa ($)'),
                   'value' => function ($model) {
                       return $model->getSum(2);
                   },
                   'options' => [
                       'disabled' => true,
                       'class' => 'tabular-cell',
                       'field' => 'summa_usd'
                   ],
                   'headerOptions' => [
                       'style' => 'width: 100px;',
                       'class' => 'summa-item-cell incoming-multiple-input-cell'
                   ]
               ]

           ]
       ]);
       ?>
    </div>
    <br>

    <div class="row">
        <div class="col-md-1 col-md-offset-11">
            <button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#payment">
                <i class="fa fa-money"></i> <?= Yii::t('app','To\'lov')?>
            </button>
        </div>
    </div>

    <div class="row collapse <?= $model->paid_amount > 0 ? 'in' : '' ?>" id="payment">
        <div class="col-md-3"></div>
        <div class="col-md-2">
            <?= $form->field($model, 'payment_method')->widget(Select2::className(),[
                'data' => \app\models\PaymentMethod::getData()
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'paid_amount')->input('number', ['step'=>'any']) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'pb_id')->widget(Select2::className(),[
                'data' => $model->getAllPulBirligi(),
            ]) ?>
        </div>
    </div>
    <br>

<?php
    $formId = $form->getId();
    $this->registerJsVar('barcode_fail_msg', Yii::t('app','Bunday shtrixkoddagi tovar topilmadi'));
    $this->registerJs("$('#{$formId}').keypress(function(e) {
        if( e.which == 13 ) {
            return false;
        }
    });
    
    $('#barcodeInput').keypress(function(e){
        var barcode = $(this).val();
        var flag = true;
        if (e.which == 13) {
        
            if(!barcode) return false;
            $(this).val('').focus();
            
            var selectObj = $('#documentitems_id table tbody tr:last').find('select');
            var selectVal = selectObj.find('option[data-barcode=\"'+barcode+'\"]').val();
            
            if (!selectVal) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:barcode_fail_msg,type:'error'});
                return false;
            }
            
            if ( $('#documentitems_id table tbody tr').length ) {
                $('#documentitems_id table tbody tr').each(function(i, elem) {
                    if(selectVal == $(elem).find('select').val()) {
                        flag = false;
                        let qtyInput = $(elem).find('input[id$=\"quantity\"]');
                        qtyInput.val(+qtyInput.val()+1);
                        return false;
                    }
                });
            }

            if(flag) {
                if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                
                $('#documentitems_id table tbody tr:last').find('select').val(selectVal).trigger('change');
            }
            
        }
    });
    $('body').on('submit', '.customAjaxForm', function (e) {
        $(this).find('button[type=submit]').hide();
        // .attr('disabled', false); Bunda knopka 2 marta bosilsa 2 marta zapros ketyapti
    });
    ");
?>
<?php
$url = Url::to(["doc/kirim_acs/get-model-orders-acs"]);
$js = <<<JS
    $('#bichuvdoc-model_orders_id').change(function (e){
        let modelOrdersId = $(this).val();
        let summa = 0;
        let trFirst = $('#documentitems_id table tbody tr.multiple-input-list__item:first').find('select.entity_id');
        if(modelOrdersId){
            $.ajax({
                type: 'GET',
                url: "$url",
                data: {id: modelOrdersId},
                success: function (response){
                    if(response.status){
                        $('#documentitems_id table tbody tr.multiple-input-list__item:first').nextAll().remove();
                        $('#documentitems_id table tbody tr.multiple-input-list__item:first').find('select.entity_id').val('').trigger('change');
                        $('#documentitems_id table tbody tr.multiple-input-list__item:first').find('input.qty').val('').trigger('change');
                        let data = response.data;
                        for(let i in data){
                            if(trFirst.val())
                                $('#documentitems_id').multipleInput('add');
                            let items = data[i];
                             
                            let n = items.name==null?'':items.name;
                            let qty = items.qty==null?'':parseInt(items.qty);
                            summa = summa + qty;
                            let value = '';
                            for (let i in items.value){
                                value = value + ' ' +items.value[i];
                            }
                            let allName = n + value;
                            let acsOption = new Option(allName, items.id, true, true);
                            let trLast = $('#documentitems_id table tbody tr.multiple-input-list__item:last');
                            trLast.find('select.entity_id').append(acsOption).trigger('change');
                            trLast.find('select.entity_id').attr('readonly', true);
                            trLast.find('input.qty').val(qty);
                        }
                        $('#footer_quantity').html(summa);
                    }
                }
            });
        }
        else{
            $('#documentitems_id table tbody tr.multiple-input-list__item:first').nextAll().remove();
            $('#documentitems_id table tbody tr.multiple-input-list__item:first').find('select.entity_id').val('').trigger('change');
            $('#documentitems_id table tbody tr.multiple-input-list__item:first').find('select.entity_id').attr('readonly', false);
            $('#documentitems_id table tbody tr.multiple-input-list__item:first').find('input').val('').trigger('change');
        }
    });
JS;
$this->registerJs($js);
