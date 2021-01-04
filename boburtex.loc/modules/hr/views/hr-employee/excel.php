<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;

    $form = ActiveForm::begin();
        echo $form->field($model, 'file')->fileInput();
        echo \yii\helpers\Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-sm']);
    ActiveForm::end();