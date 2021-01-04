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
    'action' => Url::to('report-accepted-slice'),
    'method' => 'get',
    'id' => 'ip-search-form',
    'options' => ['data-pjax' => true]
]);
?>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-4">
                <?= $form->field($model, 'nastel_no')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'artikul')->label('Model')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'rm_name')->label('Mato')->textInput() ?>
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