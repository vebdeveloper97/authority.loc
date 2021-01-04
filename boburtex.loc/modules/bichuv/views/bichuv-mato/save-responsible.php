<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.05.20 0:39
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\bichuv\models\BichuvDocResponsible */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="save-responsible-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'users_id')->widget(Select2::className(),[
        'data' => \app\models\Users::getUserList(),
        'options' => [
            'prompt' =>Yii::t('app','Tanlang')
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>
    <?= $form->field($model,'add_info')->textarea()?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
