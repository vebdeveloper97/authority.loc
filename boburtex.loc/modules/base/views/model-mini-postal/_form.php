<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelMiniPostal */
/* @var $form yii\widgets\ActiveForm */
$razmer = Yii::t('app', 'Razmerlar');
$count = Yii::t('app', 'Miqdori');
$detal = Yii::t('app', 'Detallar miqdori');
?>

<div class="model-mini-postal-form">

    <?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxForm','enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'models_list_id')->textInput() ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'users_id')->textInput() ?>
            <?= $form->field($model, 'size_collection_id')->widget(Select2::className(), [
                'data' => \app\modules\bichuv\models\BichuvGivenRolls::getSizeCollectionList(),
                'options' => [
                    'id' => 'sizeCollectionId',
                    'options' => \app\modules\bichuv\models\BichuvGivenRolls::getSizeCollectionList(true),
                    'prompt' => Yii::t('app', 'Tanlang')
                ],
                'pluginEvents' => [
                    "change" => new JsExpression("function(e) {
                                    let sizeListJson = $(this).find('option:selected').attr('data-size-list');
                                    let sizeList = JSON.parse(sizeListJson);
                                    let inputList = '';
                                    let counter = 100;
                                    let nastel = $('#barcodeInput').val();
                                    let table = $('#table-sizes');
                                    let razmer = '<tr><td>{$razmer}</td>';
                                    let count = '<tr><td>{$count}</td>';
                                    let detal = '<tr><td>{$detal}</td>';
                                    Object.keys(sizeList).map(function(key){
                                        razmer += '<td>'+sizeList[key].name+'</td>';
                                        count += '<td><input type=\"text\" class=\"form-control number\" name=\"ModelMiniPostalSize['+sizeList[key].id+'][count]\" value=\"0\"></td>';
                                        detal += '<td><input type=\"text\" class=\"form-control number\" name=\"ModelMiniPostalSize['+sizeList[key].id+'][count_detail]\" value=\"0\"></td>';
                                    });
                                    razmer += '</tr>';
                                    count += '</tr>';
                                    detal += '</tr>';
                                    table.html('<tbody>'+razmer+count+detal+'</tbody>');
                                }"),
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'eni')->textInput() ?>
            <?= $form->field($model, 'uzunligi')->textInput() ?>
            <?= $form->field($model, 'samaradorlik')->textInput() ?>
            <?= $form->field($model, 'type')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'count_items')->textInput() ?>
            <?= $form->field($model, 'total_patterns')->textInput() ?>
            <?= $form->field($model, 'total_patterns_loid')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'specific_weight')->textInput() ?>
            <?= $form->field($model, 'total_weight')->textInput() ?>
            <?= $form->field($model, 'used_weight')->textInput() ?>
            <?= $form->field($model, 'lossed_weight')->textInput() ?>
        </div>
    </div>
    <table class="table table-bordered" id="table-sizes">
        <tbody>
            <tr>
                <td><?php echo Yii::t('app',"O'lchovlar to'plamini tanlang")?></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead>
            <tr><td colspan="4" style="text-align:left;"><?php echo Yii::t('app','Ishlatilgan mato')?></td></tr>
            <tr>
                <td></td>
                <td><?php echo Yii::t('app','Ishlatilgan')?></td>
                <td><?php echo Yii::t('app',"Yo'qotilgan")?></td>
                <td><?php echo Yii::t('app','Sarflangan')?></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo Yii::t('app','Yuzasi')?></td>
                <td><?= $form->field($model, 'cost_surface',[
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon" style="padding: 1px 8px">sq m</span></div>{error}',
                    ])->textInput(['maxlength' => true])->label(false) ?></td>
                <td><?= $form->field($model, 'loss_surface',[
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon" style="padding: 1px 8px">sq m</span></div>{error}',
                    ])->textInput(['maxlength' => true])->label(false) ?></td>
                <td><?= $form->field($model, 'spent_surface',[
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon" style="padding: 1px 8px">Lm</span></div>{error}',
                    ])->textInput(['maxlength' => true])->label(false) ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('app',"Og'irligi")?></td>
                <td><?= $form->field($model, 'cost_weight',[
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon" style="padding: 1px 8px">G</span></div>{error}',
                    ])->textInput(['maxlength' => true])->label(false) ?></td>
                <td><?= $form->field($model, 'loss_weight',[
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon" style="padding: 1px 8px">G</span></div>{error}',
                    ])->textInput(['maxlength' => true])->label(false) ?></td>
                <td><?= $form->field($model, 'spent_weight',[
                        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon" style="padding: 1px 8px">KG</span></div>{error}',
                    ])->textInput(['maxlength' => true])->label(false) ?></td>
            </tr>
        </tbody>
    </table>
    <div class="flex-container">
        <div class="flex-div">
            <input type="file" name="ModelMiniPostalFiles[]">
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success submitPostal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$css = <<< CSS
    .table td{
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
    .table td input{
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
    /*input.form-control{
        border-color: #000;
    }*/
CSS;
$this->registerCss($css);