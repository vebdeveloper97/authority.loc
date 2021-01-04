<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 04.03.20 9:07
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocumentBalanceSearch|\yii\db\ActiveRecord */
/* @var $data array|false|mixed|string */
/* @var $from_model null */
/* @var $entity_type null */
/* @var $search_mato null */
/* @var $url null */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm; ?>
<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => $url ?? Url::to(['report-mato-ip']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <h2 class="text-center"><label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label></h2>
                <div class="col-md-6">
                    <?= $form->field($model, 'from_date')->widget(\kartik\widgets\DateTimePicker::className(),[
                        'type' => \kartik\widgets\DateTimePicker::TYPE_COMPONENT_PREPEND,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy hh:ii'
                        ]
                    ])->label(false)?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'to_date')->widget(\kartik\widgets\DateTimePicker::className(),[
                        'type' => \kartik\widgets\DateTimePicker::TYPE_COMPONENT_PREPEND,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy hh:ii'
                        ]
                    ])->label(false)?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <?php $label = Yii::t('app', 'Iplarni tanlash');?>
                    <?= $form->field($model, 'entity_ids')->widget(Select2::className(),[
                        'data' => $model->getEntities(1),
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
                    <?php $label = ($entity_type)?Yii::t('app', 'Aksessuarlar'):Yii::t('app', 'Matolarni tanlash');?>
                    <?= $form->field($model, 'mato_id')->widget(Select2::className(),[
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
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?php $url = Url::to(['report-mato-ip'])?>
                    <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
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
                        ]
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <?=$form->field($model, 'model_musteri_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvMusteri::getMyMusteri(),
                        'size' => Select2::SIZE_SMALL,
                        'options' => ['placeholder' => Yii::t('app', 'Select')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row">
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
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

