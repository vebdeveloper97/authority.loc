<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployeeUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-employee-users-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if(isset($models) && isset($_GET['id'])): ?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'hr_employee_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => $model->getArrayHelperEmployee(),
                    'options' => [
                        'multiple'=>false,
                        'placeholder' => Yii::t('app', 'Tanlang'),
                        'value' => $_GET['id'],

                    ],
                ]); ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'users_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => $model->getUsersUpdate($_GET['id']),
                    'options' => [
                        'multiple' => true,
                        'value' => $model->cp['rows'],

                    ],
                    'showToggleAll' => false
                ]); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'hr_employee_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => $model->getArrayHelperEmployee(),
                    'options' => [
                        'multiple'=>false,
                        'placeholder' => Yii::t('app', 'Tanlang'),

                    ],
                ]); ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'users_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => $model->getArrayHelperUser(),
                    'options' => [
                        'multiple' => true,
                        'placeholder' => Yii::t('app', 'Tanlang'),

                        ],
                ]); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php  ?>