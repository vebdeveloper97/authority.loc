<?php

use app\components\TabularInput\CustomTabularInput;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $newModel app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($newModel, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($newModel, 'document_type')->hiddenInput(['value' => $newModel::DOC_TYPE_RETURN])->label(false) ?>
        <?= $form->field($newModel, 'parent_id')->hiddenInput(['value' => $model->id])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($newModel, 'reg_date')->widget(DatePicker::classname(), [
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
        <?php $newModel->musteri_id = $model->musteri_id; ?>
        <?= $form->field($newModel, 'musteri_id')->widget(Select2::className(), [
            'data' => $model->getMusteries(),
            'options' => [
                'disabled' => true,
            ]
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php $newModel->from_department = $model->to_department; ?>
        <?= $form->field($newModel, 'from_department')->widget(Select2::className(), [
            'data' => $model->getDepartmentsBelongTo(),
            'options' => [
                'disabled' => true,
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($newModel, 'musteri_responsible')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($newModel, 'from_employee')->widget(Select2::className(),[
            'data' => $model->getEmployees()
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($newModel, 'add_info')->textarea(['rows' => 1]); ?>
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

<div class="document-items">
    <?php
    $accessoriesList = $model->getAccessories();

    $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);
    $fromDepId = Html::getInputId($model, 'from_department');
    $this->registerJsVar('dep_fail_msg', Yii::t('app','Bo\'limni tanlang'));
    ?>
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
                'id' => 'footer_quantity',
                'value' => ''
            ],
            [
                'id' => 'qty',
                'value' => 0
            ],
            [
                'id' => 'add_info',
                'value' => ''
            ],
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 100,
        'min' => 0,
        'addButtonOptions' => [
            'class' => 'hidden',
        ],
        'removeButtonOptions' => [
            'class' => 'hidden',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'name' => 'entity_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' => $accessoriesList['data'],
                    'disabled' => true,
                    'options' => [
                        'placeholder' => Yii::t('app','Placeholder Select'),
                        'multiple' => false,
                        'options' => $accessoriesList['barcodeAttr']
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 50%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'document_quantity',
//                'type' => 'text',
                'options' => [
                    'field' => 'document_quantity',
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ]
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Soni'),
                'options' => [
                    'field' => 'quantity',
                    'disabled' => true,
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ]
            ],
            [
                'name' => 'qty',
                'title' => Yii::t('app', 'Qaytarish'),
                'defaultValue' => 0,
                'options' => [
                    'step' => '0.001',
                    'type' => 'number',
                    'min' => 0,
                    'class' => 'tabular-cell',
                    'field' => 'qty'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'add_info',
                'title' => Yii::t('app', 'Add Info'),
                'headerOptions' => [
                    'style' => 'width: 20%;',
                    'class' => 'add_info-item-cell'
                ]
            ],


        ]
    ]);
    ?>
</div>
<br>

<?php
$formId = $form->getId();

$this->registerJsVar('barcode_fail_msg', Yii::t('app','Bunday shtrixkoddagi tovar topilmadi'));
$this->registerJsVar('remain_fail_msg', Yii::t('app','Balansda bundan ortiq tovar yoq'));
$this->registerJs("$('#{$formId}').keypress(function(e) {
        if( e.which == 13 ) {
            return false;
        }
    });
    
    $('#documentitems_id table tbody tr').each(function(i, elem) {
        $(elem).find('input[id$=\"qty\"]').on('blur change paste keyup', function(e) {
            $.fn.calcRemain($(elem).find('input[id$=\"document_quantity\"]'));
        });
    });
    
    
    $.fn.calcRemain = function(remainInput) {
         var returnInput = remainInput.parents('tr').find('input[id$=\"qty\"]');
         var qtyInput = remainInput.parents('tr').find('input[id$=\"quantity\"]');
         if( parseFloat(remainInput.val()) < parseFloat(returnInput.val()) ) {
            returnInput.val(remainInput.val()).change();
            return false;
         } else if( parseFloat(qtyInput.val()) < parseFloat(returnInput.val()) ) {
            returnInput.val(qtyInput.val()).change();
            return false;
         }
         
         return false;
     }
    ", View::POS_READY);
?>
