<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelOrders;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use app\modules\bichuv\models\BichuvMatoOrders as BMO;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-mato-orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app', 'Sana')],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
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
        <div class="col-md-4">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(), [
                'data' => \app\modules\base\models\ModelOrders::getOrdersMusteriList(\app\modules\base\models\ModelOrders::STATUS_SAVED,'>'),
                'options' => [
                    'id' => 'musteri_id',
                    'prompt' => Yii::t('app', 'Tanlang')
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'model_orders_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => (!$model->isNewRecord)?BMO::getOrdersList($model->musteri_id,true,ModelOrders::STATUS_SAVED,'>'):'',
                'options'=>['id'=>'model_orders_id'],
                'pluginOptions'=>[
                    'depends'=>['musteri_id'],
                    'placeholder'=>Yii::t('app', 'Tanlang'),
                    'url'=>\yii\helpers\Url::to('orders-list')
                ]
            ]); ?>
        </div>
        <div class="col-md-4">
            <?php $url = \yii\helpers\Url::to('ajax-request') ?>
            <?= $form->field($model, 'model_orders_items_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => (!$model->isNewRecord)?BMO::getOrderItemsList($model->model_orders_id,true):'',
                'options'=>['id'=>'model_orders_items_id'],
                'pluginOptions'=>[
                    'depends'=>['model_orders_id'],
                    'placeholder'=>Yii::t('app', 'Tanlang'),
                    'url'=>\yii\helpers\Url::to('orders-items-list')
                ],
                'pluginEvents' => [
                    'change' => new JsExpression(
                        "function(e){
                                    var _this = $(this);
                                    var id = _this.val();
                                    if(id){
                                        console.log(id);
                                        $.ajax({
                                            url: '{$url}',
                                            type: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                                            },
                                            data: {
                                                id: id,
                                            },
                                        })
                                        .done(function(response) {
                                            console.log(response);
                                            $('#documentitems_id').find('tbody').html('');
                                            if(response.status==1){
                                                if(response.results.mato){
                                                    response.results.mato.map(function(index,key){
                                                        $('#documentitems_id').multipleInput('add');
                                                        let tr = $('#documentitems_id table tbody tr:last');
                                                        tr.find('.mop_id').val($(index).attr('mop_id')).trigger('change');
                                                        tr.find('.entity_id').val($(index).attr('id')).trigger('change');
                                                        tr.find('.entity_type').val($(index).attr('type')).trigger('change');
                                                        tr.find('.name').val($(index).attr('name')).trigger('change');
                                                        tr.find('.quantity').val($(index).attr('quantity')).trigger('change');
                                                        tr.find('.count_summa').val($(index).attr('count')).trigger('change');
                                                    });
                                                }
                                                /*if(response.results.toquv_acs){
                                                    response.results.toquv_acs.map(function(index,key){
                                                        $('#documentitems_id').multipleInput('add');
                                                        let tr = $('#documentitems_id table tbody tr:last');
                                                        tr.find('.mop_id').val($(index).attr('mop_id')).trigger('change');
                                                        tr.find('.entity_id').val($(index).attr('id')).trigger('change');
                                                        tr.find('.entity_type').val($(index).attr('type')).trigger('change');
                                                        tr.find('.name').val($(index).attr('name')).trigger('change');
                                                        tr.find('.quantity').val($(index).attr('quantity')).trigger('change');
                                                        tr.find('.count_summa').val($(index).attr('count')).trigger('change');
                                                    });
                                                }*/
                                                if(response.results.acs){
                                                    response.results.acs.map(function(index,key){
                                                        $('#documentitems_id').multipleInput('add');
                                                        let tr = $('#documentitems_id table tbody tr:last');
                                                        tr.find('.entity_id').val($(index).attr('id')).trigger('change');
                                                        tr.find('.entity_type').val($(index).attr('type')).trigger('change');
                                                        tr.find('.name').val($(index).attr('name')).trigger('change');
                                                        tr.find('.quantity').val($(index).attr('quantity')).trigger('change');
                                                        tr.find('.count_summa').val($(index).attr('count')).trigger('change');
                                                    });
                                                }
                                            }
                                        })
                                        .fail(function(response) {
                                        });
                                    }
                                }"
                    ),
                ]
            ]); ?>
        </div>
    </div>
    <div class="document-items">
        <?= CustomTabularInput::widget([
            'id' => 'documentitems_id',
            'form' => $form,
            'models' => $models,
            'theme' => 'bs',
            'showFooter' => true,
            'attributes' => [
                [
                    'id' => 'footer_mato',
                    'value' => null
                ],
                [
                    'id' => 'footer_quantity',
                    'value' => 0
                ],
                [
                    'id' => 'footer_roll_count',
                    'value' => 0
                ],
                [
                    'id' => 'footer_count',
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
                'class' => 'hidden'
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'name' => 'entity_type',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'entity_type'
                    ]
                ],
                [
                    'name' => 'entity_id',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'entity_id'
                    ]
                ],
                [
                    'name' => 'mop_id',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'mop_id'
                    ]
                ],
                [
                    'name' => 'name',
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    'options' => [
                        'class' => 'name',
                        'readOnly' => true
                    ],
                    'headerOptions' => [
                        'style' => 'width: 40%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Miqdori(kg)'),
                    'options' => [
                        'class' => 'tabular-cell-summa quantity number',
                        'data-footer' => 'footer_quantity',
                        'data-summa' => 'quantity'
                    ],
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'roll_count',
                    'title' => Yii::t('app', 'Rulon soni'),
                    'options' => [
                        'class' => 'tabular-cell-summa roll-count number',
                        'data-footer' => 'footer_roll_count',
                        'data-summa' => 'roll-count'
                    ],
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'count',
                    'title' => Yii::t('app', 'Soni(dona)'),
                    'options' => [
                        'class' => 'tabular-cell-summa count_summa number',
                        'data-footer' => 'footer_count',
                        'data-summa' => 'count_summa'
                    ],
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],

            ]
        ]);
        ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<< JS
    $('body').delegate('.tabular-cell-summa', 'change', function(e){
        let footer = $('#'+$(this).attr('data-footer'));
        footer.html(0);
        let summa = $(this).parents('tbody').find('.'+$(this).attr('data-summa'));
        let sum = 0;
        summa.each(function(index,value) {
            let num = 1*$(this).val();
            let check = sum + num;
            if(!Number.isNaN(check)){
                sum += num;
            }
        });
        footer.html(sum);
    });
    $('.tabular-cell-summa').each(function(index,value){
        let footer = $('#'+$(this).attr('data-footer'));
        let summa = $(this).parents('tbody').find('.'+$(this).attr('data-summa'));
        let sum = 0;
        summa.each(function(index,value) {
            let num = 1*$(this).val();
            let check = sum + num;
            if(!Number.isNaN(check)){
                sum += num;
            }
        });
        footer.html(sum);
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);