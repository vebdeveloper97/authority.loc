<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.06.20 2:58
 */

use app\modules\base\models\Musteri;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
?>
<?php
$this->registerJs(
    '$("document").ready(function(){
        $("#reportResultIncoming").on("pjax:end", function() {
            $("caption.btn-toolbar").remove();
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
    });'
);
?>
<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['usluga-remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'nastel_no')
                ->textInput()
                ->label(Yii::t('app', 'Nastel No')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'model_no')
                ->textInput()
                ->label(Yii::t('app', 'Model No')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'from_musteri')
                ->widget(Select2::classname(), [
                    'data' => \app\modules\usluga\models\UslugaDoc::getMusteries(null,3),
                    'language' => 'eng',
                    'options' => ['placeholder' => Yii::t('app','Buyurtmachini tanlang...')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label(Yii::t('app', 'Qayerdan')) ?>
        </div>
        <div class="col-lg-3">
            <?=$form->field($model, 'customer')->widget(Select2::classname(), [
                'data' => app\modules\base\models\Musteri::getList(),
                'language' => 'eng',
                'options' => ['placeholder' => Yii::t('app','Buyurtmachini tanlang...')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Buyurtmachi'));
            ?>
        </div>
        <!--<div class="col-md-6">
            <label class="control-label"> </label>


        </div>-->
        <div class="col-lg-3">
            <?= $form->field($model, 'package_type')
                ->dropDownList($model->getUnitList(),['prompt'=>'']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'color')
                ->textInput()->label(Yii::t('app','Color')) ?>
        </div>
        <div class="col-lg-3">
            <?=$form->field($model, 'doer')->widget(Select2::classname(), [
                'data' => app\modules\base\models\BarcodeCustomers::getBarcodeCustomerList(),
                'language' => 'eng',
                'options' => ['placeholder' => Yii::t('app','Brendni tanlang...')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Brend'));
            ?>
        </div>

        <div class="col-md-3" style="margin-top: 20px;display: flex ">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['usluga-remain']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
