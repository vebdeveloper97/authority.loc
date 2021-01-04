<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 29.01.20 17:40
 */

/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\MatoSearch */
/* @var $params mixed */

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm; ?>

<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => '',
        'method' => 'get',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-2">
                    <?php echo $form->field($model, 'musteri_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvKalite::getMusteriList(),
                        'size' => Select2::SIZE_SMALL,
                        'options' => [
                            'multiple' => true,
                            'placeholder' => Yii::t('app', 'Select'),
                            'id' => 'musteri_search'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                    <?= $form->field($model, 'pus_fine_id')->widget(Select2::className(),[
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
                    <?php echo $form->field($model, 'sort_name_id')->widget(Select2::className(),[
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
                </div>
                <div class="col-md-5">
                    <?php $label = Yii::t('app', 'Matolarni tanlash');?>
                    <?= $form->field($model, 'toquv_raw_materials_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvKalite::getMatoList(1),
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
                    <div class="row">
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
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo $form->field($model, 'toquv_instructions_id')->widget(Select2::className(),[
                                'data' => \app\modules\toquv\models\ToquvKalite::getToquvOrdersList(),
                                'toggleAllSettings' => [
                                    'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                                    'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                                ],
                                'options' => [
                                    'multiple' => true,
                                    'prompt' =>Yii::t('app','Barchasi'),
                                    'id' => 'toquv_order_search'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ])->label(Yii::t('app', 'Doc Number'))
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo $form->field($model, 'toquv_makine_id')->widget(Select2::className(),[
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
                                ],
                            ])
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo $form->field($model, 'user_kalite_id')->widget(Select2::className(),[
                                'data' => \app\modules\toquv\models\ToquvMakine::getUserList(null,null,'TOQUV_KALITE'),
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
                        </div>
                        <div class="col-md-6">
                            <?php echo $form->field($model, 'status')->dropDownList(\app\modules\toquv\models\ToquvInstructions::getStatusActive(),['prompt'=>''])->label(Yii::t('app', "Ko'rsatma holati"))
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo $form->field($model, 'user_id')->widget(Select2::className(),[
                                'data' => \app\modules\toquv\models\ToquvMakine::getUserList(),
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
                        </div>
                        <div class="col-md-6">
                            <?php echo $form->field($model, 'is_closed')->dropDownList([1=>Yii::t('app', 'Bajarilmaganlar'),2=>Yii::t('app', 'Bajarilganlar')],['prompt'=>''])
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?=$form->field($model, 'created_at')->widget(\kartik\daterange\DateRangePicker::className(),[
                                'model' => $model,
                                'attribute'=>'created_at',
                                'convertFormat'=>true,
                                'startAttribute' => 'date_from',
                                'endAttribute' => 'date_to',
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
                            ])?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'order_type')->dropDownList(\app\modules\toquv\models\ToquvOrders::getOrderTypeList(),['prompt'=>Yii::t('app', 'Buyurtma turi')]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Filterni bekor qilish', Url::to(['report-moving-mato']), ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
