<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 04.01.20 14:58
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\RemainSearchMato|\yii\db\ActiveRecord */
/* @var $data array|false|mixed|string */
/* @var $entity_type integer|false */
/* @var $type integer|false */

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax; ?>
<div class="toquv-item-balance-search">
    <?php
    $this->registerJs(
        '$("document").ready(function(){
            $("#reportSearchFormMoving").on("pjax:end", function() {
            $.pjax.reload({container:"#reportResultMoving"}).done(function(){
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
    );
    ?>
<!--    --><?php //Pjax::begin(['id' => 'reportSearchFormMoving'])?>
    <?php $url = $entity_type?'report-outcoming-aksessuar':'report-outcoming-mato';
    $form = ActiveForm::begin([
        'action' => Url::to([$url]),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-3">
                    <?= $form->field($model, 'department_id')->dropDownList($model->getDepartments(),[
                        'prompt' => Yii::t('app', "Barchasi")
                    ]);?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'to_musteri')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvMusteri::getMyMusteri(),
                        'size' => Select2::SIZE_SMALL,
                        'options' => ['placeholder' => Yii::t('app', 'Select')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-md-6">
                    <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
                    <?= DatePicker::widget([
                        'model' => $model,
                        'language' => 'ru',
                        'separator' => '<i class="fa fa-arrows-h"></i>',
                        'attribute' => 'from_date',
                        'attribute2' => 'to_date',
                        'options' => ['placeholder' => Yii::t('app', 'Start date'), 'value' => $data['from_date']],
                        'options2' => ['placeholder' => Yii::t('app', 'End Date'), 'value' => $data['to_date']],
                        'type' => DatePicker::TYPE_RANGE,
                        'form' => $form,
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'autoclose' => true,
                        ]
                    ]);

                    ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2">
                    <?php echo $form->field($model, 'musteri_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvMusteri::getMyMusteri(),
                        'size' => Select2::SIZE_SMALL,
                        'options' => ['placeholder' => Yii::t('app', 'Select')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
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
                <div class="col-md-5">
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
                    <div class="col-md-6">
                        <?= $form->field($model, 'thread_length')->textInput() ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'finish_en')->textInput() ?>
                    </div>
                </div>
                <div class="col-md-5">
                    <?php echo $form->field($model, 'sort_id')->widget(Select2::className(),[
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
                    ])
                    ?>
                    <div class="col-md-6">
                        <?= $form->field($model, 'finish_gramaj')->textInput() ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Filterni bekor qilish', Url::to(['report-outcoming-mato']), ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => ($type)??\app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_MATO])->label(false) ?>
    <?= $form->field($model, 'document_type')->hiddenInput(['value' => \app\modules\toquv\models\ToquvDocuments::DOC_TYPE_OUTCOMING])->label(false) ?>
    <?php ActiveForm::end(); ?>
<!--    --><?php //Pjax::end()?>
</div>