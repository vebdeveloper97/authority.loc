<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.04.20 15:38
 */

use app\modules\base\models\ModelOrders;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrdersSearch */
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to('report-mato'),
    'method' => 'get',
    'id' => 'ip-search-form',
    'options' => ['data-pjax' => true]
]);
//echo '<pre>';
//var_dump($model);
//echo '</pre>';
?>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-3">
                <?= $form->field($model, 'nastel_no')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'artikul')->label('Model')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'rm_name')->label('Mato')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'iplik')->label('Iplik')->textInput() ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-3">
                <?= $form->field($model, 'ctone')->label('Rang toni')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'party_nomer')->label("Party No")->textInput() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'musteri_party_nomer')->label('Musteri No')->textInput() ?>
            </div>
            <div class="col-md-3">
                <!--                --><?php //= $form->field($model, 'iplik')->textInput() ?>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4" style="margin-top: 25px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
            <?php $url = Url::to(['index'])?>
            <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>