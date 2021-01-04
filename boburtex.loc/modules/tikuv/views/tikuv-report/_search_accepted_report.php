<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 20.08.20 23:32
 */


use app\modules\tikuv\models\TikuvReportSearch;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this View */
/* @var $model TikuvReportSearch|null */
?>
<?php $url = 'usluga-summa'; $form = ActiveForm::begin([
    'action' => Url::to(['accepted-report']),
    'method' => 'get',
    'id' => 'ip-search-form',
]); ?>
    <div>
        <div class="row">
            <div class="col-md-4">
                <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
                <?= $form->field($model, 'reg_date')->widget(DateRangePicker::className(),[
                    'model' => $model,
                    'attribute'=>'from_date',
                    'convertFormat'=>true,
                    'startAttribute' => 'from_date',
                    'endAttribute' => 'to_date',
                    'options' => [
                        'autocomplete' => 'off'
                    ],
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
        <div class="row">
            <div class="col-md-4" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Filterni bekor qilish', Url::to(['accepted-report']), ['class' => 'btn btn-danger']) ?>
            </div>
            <div class="col-md-6" style="padding-top: 20px;">
                <div class="row">
                    <label for="tableAndDiagram">
                        <input type="radio" name="fullScreen" id="tableAndDiagram" checked>
                        <?php echo Yii::t('app','Jadval va diagramma')?>
                    </label>
                    <label for="onlyTable">
                        <input type="radio" name="fullScreen" id="onlyTable">
                        <?php echo Yii::t('app','Jadval')?>
                    </label>
                    <label for="onlyDiagram">
                        <input type="radio" name="fullScreen" id="onlyDiagram">
                        <?php echo Yii::t('app','Diagramma')?>
                    </label>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>