<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\NewModelBarcodeForm */
?>
<div class="barcode-form">
    <?php $url = \yii\helpers\Url::to(['barcode/get-model-vars-via-ajax'])?>
    <?php $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['barcode/add-new-model-barcode'])]); ?>
    <?= $form->field($model,'color')->hiddenInput(['id' => 'newBarcodeColorId'])->label(false);?>
    <?= $form->field($model,'article')->hiddenInput(['id' => 'newBarcodeArticle'])->label(false);?>
    <?= $form->field($model,'name')->hiddenInput(['id' => 'newBarcodeName'])->label(false);?>
    <?= $form->field($model,'code')->hiddenInput(['id' => 'newBarcodeCode'])->label(false);?>
    <?= $form->field($model, 'model')->widget(Select2::className(), [
            'data' => $model->getModelList(),
            'options' => [
              'placeholder' => Yii::t('app','Select'),
              'id' => 'barcodeNewModel'
            ],

            'pluginOptions' => [
                  'allowClear' => true
            ],
            'pluginEvents' => [
                    'change' => new JsExpression("function(e){
                        let id = e.target.value;
                        $.ajax({
                            url:'$url?id='+id,
                            success: function(response){
                                if(response.status){
                                    $('#newBarcodeModelVar').html('');
                                    response.items.map(function(item, key){
                                        let name  = item.code;
                                        if(item.name){
                                            name += ' '+ item.name; 
                                        }
                                        document.getElementById('newBarcodeArticle').value = item.article;
                                        document.getElementById('newBarcodeName').value = item.modelName;
                                        let option = new Option(name, item.id);
                                        option.setAttribute('data-code',item.code);
                                        option.setAttribute('data-color-id',item.colorId);
                                        $('#newBarcodeModelVar').append(option);
                                        
                                    });
                                    $('#newBarcodeModelVar').trigger('change');
                                }
                            }
                        })
                    }"),
                'select2:clear' => new JsExpression("function(e){
                    $('#newBarcodeModelVar').html('');
                }")
            ]
    ]); ?>

    <?= $form->field($model, 'model_var')->widget(Select2::className(),[
            'data' => [],
            'options' => [
                'id' => 'newBarcodeModelVar'
            ],
            'pluginEvents' => [
                    'change' => new JsExpression("function(e){
                            let code = $('option:selected', this).attr('data-code');
                            let colorId = $('option:selected', this).attr('data-color-id');
                            document.getElementById('newBarcodeCode').value = code;
                            document.getElementById('newBarcodeColorId').value = colorId;
                    }")
            ]
    ]) ?>

    <?= $form->field($model, 'size')->widget(Select2::className(), [
            'options' => [
              'placeholder' => Yii::t('app','Select'),
            ],
            'data' => $model->getSizeCollection(),

    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>