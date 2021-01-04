<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 04.03.20 9:07
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocumentBalanceSearch|\yii\db\ActiveRecord */
/* @var $from_model null */
/* @var $entity_type null */
/* @var $search_mato null */
/* @var $url null */

use app\modules\base\models\BaseModel;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm; ?>
<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => $url ?? Url::to(['model-report']),
        'method' => 'get',
        'id' => 'ip-search-model-report',
        'options' => [
            'data-pjax' => true,
            'autofocus' => true,
        ]
    ]); ?>

        <div class="form-group row">
            <div class="col-md-4">
                <?= $form->field($model, 'doc_number')->textInput()->label(Yii::t('app', "Doc Number"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'model_no')->textInput()->label(Yii::t('app', "Model No"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'hom_kg')->textInput()->label(Yii::t('app', "Hom kg"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'rm_name')->textInput()->label(Yii::t('app', "Mato Nomi"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'rm_id')->widget(Select2::classname(), [
                    'data' => \app\modules\toquv\models\ToquvRawMaterials::getMaterialList()['list'],
                    'options' => ['placeholder' => Yii::t('app', 'Select')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'escapeMarkup' => new JsExpression("function (markup) { 
                                    return markup;
                                }"),
//                        'templateResult' => new JsExpression("function(data) {
//                                       return data.text;
//                                 }"),
//                        'templateSelection' => new JsExpression("
//                                        function (data) { return data.text; }
//                                 "),
                    ],
                ])->label(Yii::t('app', "Mato Nomi"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' => \app\modules\base\models\ModelOrders::getStatusList(),
                    'options' => ['placeholder' => Yii::t('app', 'Select')],
                    'pluginOptions' => [
                        'multiple' => true,
                        'allowClear' => true,
                        'escapeMarkup' => new JsExpression("function (markup) { 
                                    return markup;
                                }"),
                    ],
                ])->label(Yii::t('app', "Holati"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'rang1')->textInput()->label(Yii::t('app', "Color Pantone"))?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'rang2')->textInput(['value' => $searchModel->rang2])->label(Yii::t('app', "Bo'yoqhona ranglari"))?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?php $url = Url::to(['model-report'])?>
                <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>

