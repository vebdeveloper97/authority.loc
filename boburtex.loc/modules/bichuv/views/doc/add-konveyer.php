<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 01.05.20 12:07
 */



/* @var $this \yii\web\View */
/* @var $id  */
/* @var $dept  */
/* @var $model \app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls */
/* @var $tikuv_konveyer \app\modules\tikuv\models\TikuvKonveyer */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="tikuv-konveyer-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'tikuv_konveyer_id')->widget(Select2::className(),[
        'data' => $tikuv_konveyer,
        'options' => [
            'prompt' =>Yii::t('app','Tanlang')
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
