<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 30.01.20 17:19
 */

/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 29.01.20 17:40
 */

/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\MatoSearch */
/* @var $params mixed */
/* @var $kalite array|false */

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
                    <div class="row">
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
                    </div>
                </div>
                <div class="col-md-5 row">
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
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Filterni bekor qilish', Url::to(['save-and-finish','id'=>$kalite['tir_id']]), ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
