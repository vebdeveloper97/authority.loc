<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.04.20 15:38
 */

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelsList;
use app\modules\base\models\Musteri;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;


/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrdersSearch */
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to('index'),
    'method' => 'get',
    'id' => 'ip-search-form',
    'options' => ['data-pjax' => true]
]); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4">
                <?= $form->field($model, 'artikul')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'pantone')->widget(Select2::className(),[
                    'data' => $model->pantoneCodeList(),
                    'options' => [
                        'prompt'=>'',
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
                ])?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'doc_number')->textInput() ?>
            </div>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(), [
                'data' => Musteri::getList(),
                'language' => 'ru',
                'options' => [
                    'prompt' => '',
                    'id' => 'musteri_list'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'status')->dropDownList(ModelOrders::getStatusList(),['prompt'=>'']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'created_by')->widget(\kartik\select2\Select2::className(), [
                'data' => ModelOrders::getAuthorList(),
                'language' => 'ru',
                'options' => [
                    'prompt' => '',
                    'id' => 'personal_list'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'updated_by')->widget(\kartik\select2\Select2::className(), [
                'data' => ModelOrders::getAuthorList(),
                'language' => 'ru',
                'options' => [
                    'prompt' => '',
                    'id' => 'updated_list'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'add_info')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'brend')->dropDownList(ModelsList::getAllBrend(),['prompt'=>'']) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'per_page')->textInput() ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6" style="margin-top: 25px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
            <?php $url = Url::to(['index'])?>
            <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
        </div>
        <!--<div class="col-md-3">

    </div>-->
        <div class="col-md-3">

        </div>
    </div>
<?php ActiveForm::end(); ?>