<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ToquvUserDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-user-department-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'user_id')->widget(Select2::className(),[
                'data' => $model->getUsers()
            ])
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="control-label"><?= Yii::t('app','Departments')?></label>
            <?= Select2::widget([
                'name' => 'departments',
                'data' => $model->getDepartments(),
                'value' => $model->cp['rows'],
                'options' => [
                    'multiple' => true,
                ],
                'showToggleAll' => false
            ])?>
        </div>

        <div class="col-md-6">
            <label class="control-label"><?= Yii::t('app','Departments') // TODO change label?></label>
            <?= Select2::widget([
                'name' => 'departments_2',
                'data' => $model->getDepartments(true),
                'value' => $model->cp['rows2'],
                'options' => [
                    'multiple' => true,
                ],
                'showToggleAll' => false
            ])?>
        </div>
    </div>


    <div class="form-group pull-right">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
