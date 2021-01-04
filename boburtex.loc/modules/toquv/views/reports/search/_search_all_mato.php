<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 30.01.20 20:11
 */

use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\RemainSearchMato|\yii\db\ActiveRecord */
/* @var $data array|false|mixed|string */

$url_musteri = Url::to('musteri');
$url_order = Url::to('order');
$url_order_items = Url::to('order-items');?>
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
    <?php Pjax::begin(['id' => 'reportSearchFormMoving'])?>
    <?php $url = 'report-all-mato'; $form = ActiveForm::begin([
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
                    ])->label(Yii::t('app', "Department"));?>
                </div>
                <div class="col-md-6">
                    <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
                    <?= $form->field($model, 'date')->widget(\kartik\daterange\DateRangePicker::className(),[
                        'model' => $model,
                        'attribute'=>'created_at',
                        'convertFormat'=>true,
                        'startAttribute' => 'from_date',
                        'endAttribute' => 'to_date',
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
                    ])->label(false)?>
                </div>
            </div>
            <div class="form-group row">
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Filterni bekor qilish', Url::to(['report-incoming-mato']), ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end()?>
</div>
