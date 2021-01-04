<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\modules\toquv\models\ToquvDepartments;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvInstructions */
/* @var $models app\modules\toquv\models\ToquvInstructionItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-instructions-form">
    <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_IP_SKLAD'])->asArray()->one(); ?>
    <?php $form = ActiveForm::begin(); ?>
    <?php $url = Url::to(['get-order-info'])?>
    <div class="row">
        <div class="col-md-3">
            <?php $model->reg_date = date('d.m.Y');?>
            <?= $form->field($model, 'reg_date')->widget(DatePicker::className(),[
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'toquv_order_id')->widget(Select2::className(), [
                    'data' => $model->getOrderList(),
                    'options' => [
                        'placeholder' => Yii::t('app',"Select Order")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        "select2:select" => new JsExpression("function(e){
                            var orderId = $(this).val(); 
                            $.ajax({
                               url:'{$url}?id='+orderId,
                               success: function(response){
                                    if(response.status == 1 && response.data){
                                        $('#orderInfoBox .body-order-info').html(response.data);
                                    }    
                               }   
                            });
                        }"),
                        "select2:clear" => new JsExpression("function(e){
                            $('#orderInfoBox .body-order-info').html('');
                        }")
                    ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'from_department')->hiddenInput(['value' => $dept['id']])->label(false) ?>
            <?= $form->field($model, 'to_department')->widget(Select2::className(),[
                    'data' => $model->getDepartments(),
            ])
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'priority')->dropDownList($model->getPriorityList()) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'responsible_persons')->textarea(['rows' => 2]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>
        </div>
    </div>
    <div id="orderInfoBox">
        <h4><?= Yii::t('app',"Buyurtma haqida ma'lumotlar")?></h4>
        <table class="table table-bordered">
            <thead>
                <th>№</th>
                <th><?= Yii::t('app',"Buyurtmachi")?></th>
                <th><?= Yii::t('app','Mato Nomi')?></th>
                <th><?= Yii::t('app','Mato miqdori (kg)')?></th>
                <th><?= Yii::t('app','Ip nomi va miqdori (kg)')?></th>
            </thead>
            <tbody class="body-order-info">

            </tbody>
        </table>
    </div>
    <?php
    $urlRemain = Url::to(['ajax-request']);
    $toDepId = Html::getInputId($model, 'from_department');
    $dataEntities = [];
    $dataEntityAttrs  = [];
    if(!$model->isNewRecord){
        $params = [];
        $params['department_id'] = $dept['id'];
        $params['entity_type'] = 1;
        $res = $model->searchEntities($params);
        foreach ($res as $item){
            $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
            $dataEntities[$item['entity_id']] = $name;
            $dataEntityAttrs[$item['entity_id']] = ['data-sum' => $item['summa']];
        }
    }
    ?>
    <div class="instruction-items-box">
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
                    'id' => 'footer_remain',
                    'value' => 0
                ],
                [
                    'id' => 'footer_quantity',
                    'value' => 0
                ],
                [
                    'id' => 'footer_add_info',
                    'value' => ''
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
                'class' => 'btn btn-success',
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'type' => 'hiddenInput',
                    'name' => 'toquv_instruction_id'
                ],
                [
                    'name' => 'entity_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    'options' => [
                        'data' => $dataEntities,
                        'options' => [
                            'class' => 'tabularSelectEntity',
                            'placeholder' => Yii::t('app','Ipni tanlang'),
                            'multiple' => false,
                            'options' => $dataEntityAttrs
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return '...'; }"),
                            ],
//                            'ajax' => [
//                                'url' => $urlRemain,
//                                'dataType' => 'json',
//                                'data' => new JsExpression("function(params) {
//                                        let deptId = $('#{$toDepId}').val();
//                                        let currIndex = 1;
//                                        return {
//                                                q:params.term,
//                                                dept:deptId,
//                                                type:1,
//                                                index:currIndex
//                                            };
//                             }"),
//                                'cache' => true
//                            ],
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
                        'pluginEvents' => [
                            'select2:select' => new JsExpression(
                                "function(e){
                                            if(e.params.data && e.params.data.index){
                                                let  index = e.params.data.index;
                                                let tib_id = e.params.data.tib_id;
                                                let lot = e.params.data.lot;
                                                $(this).parents('td').find('#toquvinstructionitems-'+index+'-lot').val(lot);
                                                $(this).parents('td').find('#toquvinstructionitems-'+index+'-tib_id').val(tib_id);
                                            }
                                            
                                            if(e.params.data && e.params.data.summa){
                                                $(this).parents('tr').find('.list-cell__remain input').val(e.params.data.summa);
                                            }else{
                                                $(this).parents('tr').find('.list-cell__remain input').val(0);
                                            }
                                            $('.quantityMoving').on('keyup', function(e){
                                                let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                                                let currentValue = $(this).val();
                                                if(parseFloat(currentValue) > parseFloat(remainQty)){
                                                    $(this).val(parseFloat(remainQty));
                                                }
                                            });
                                    }"
                            ),
                            "select2:close" => "function(e) { 
                                 $(this).parents('tr').find('.list-cell__remain input').val(0);
                                 $(this).parents('tr').find('.list-cell__quantity input').val(0);
                         }",
                        ],
                    ],

                    'headerOptions' => [
                        'style' => 'width: 200px;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'remain',
                    'options' => [
                         'disabled' => true
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'remain-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Soni (Fakt)'),
                    'defaultValue' => 0,
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ],
                    'options' => [
                        'class' => 'tabular-cell quantityMoving',
                        'field' => 'quantity'
                    ]
                ],
                [
                    'name' =>  'add_info',
                    'title' => Yii::t('app', 'Add Info'),
                    'defaultValue' => '',
                    'headerOptions' => [
                        'style' => 'width: 200px;',
                        'class' => 'add_info-item-cell incoming-multiple-input-cell'
                    ],
                ],
            ]
        ]);
        ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $this->registerJs("
    $('.quantityMoving').on('keyup', function(e){
        let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
        let currentValue = $(this).val();
        if(parseFloat(currentValue) > parseFloat(remainQty)){
            $(this).val(parseFloat(remainQty));
        }
    });");
    ?>
    <?php
    if(!$model->isNewRecord){
        $this->registerJs("
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let select = $(val).find('.list-cell__entity_id select option:selected').attr('data-sum');
                        $(val).find('.list-cell__remain input').val(select);                    
                    });
               }
         });
    ");
    }
    ?>
</div>
