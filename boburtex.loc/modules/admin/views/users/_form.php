<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $info app\models\UsersInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => [
        'data-pjax' => true,
        'class'=> 'customAjaxForm'
    ]]); ?>
    <div class="toquv-users-form row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?php if(Yii::$app->user->identity->user_role == 1){?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'value' => '']) ?>
                <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true,'value' => '']) ?>
            <?php }?>
            <?= $form->field($model, 'user_fio')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            <?php if(Yii::$app->user->identity->user_role == 1){?>
                <?= $form->field($info, 'rfid_key')->textInput() ?>
            <?php }?>
            <?= $form->field($info, 'tabel')->textInput() ?>

            <?= $form->field($info, 'tel')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php // $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true,'value' => '']) ?>

            <?= $form->field($model, 'lavozimi')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'user_role')->widget(Select2::classname(), [
                'data' => \app\models\UserRoles::getUserRoles(),
                'options' => ['placeholder' => Yii::t('app','Ish Bo\'limini tanlang' )], 'value' => $model->user_role,'class' => 'col-md-6',
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <?=
            $form->field($model, 'roles')->widget(Select2::classname(), [
                'data' => \app\modules\admin\models\AuthItem::getRoles(),
                'options' => ['placeholder' => Yii::t('app','Select Roles'),'value' => $model->getPermissions($model->id),'class' => 'col-md-6'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($info, 'smena')->dropDownList(['A'=>'A','B'=>'B','C'=>'C'],['prompt'=>'']) ?>
            <?= $form->field($info, 'razryad')->textInput() ?>
            <?= $form->field($info, 'adress')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <?= $form->field($info, 'add_info')->textarea(['rows' => 1]) ?>
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
