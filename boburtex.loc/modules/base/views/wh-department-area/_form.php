<?php

    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDepartmentArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wh-department-area-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dep_id')->widget(Select2::classname(), [
        'data' => $model->getMyDepartments(),
        'size' => Select2::SIZE_SMALL,
        'options' => ['placeholder' => Yii::t('app', 'Placeholder Select')],
        'pluginOptions' => [
            'allowClear' => true
        ]]); ?>

    <?php
        echo $form->field($model, 'parent_id')->widget(Select2::classname(), [
        'data' => $model->getMyParents(),
        'size' => Select2::SIZE_SMALL,
        'options' => ['placeholder' => Yii::t('app', 'Placeholder Select')],
        'pluginOptions' => [
            'allowClear' => true
        ]]); ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
/*$js = <<<JS
    function rand(max) {
      return Math.floor(Math.random() * Math.floor(max));
    }
    $('#area_name').keyup(function(e) {
        var code = $('#area_code');
        if (code.val().length > 4 && code.val().length < 9) {
            code.val(code.val() + '_' + rand(10000));
            return false;
        } else if (code.val().length > 9) {
            return false;
        } else {
            code.val($(this).val().replace(/ |'|"/gi, '_').toUpperCase());
        }
    });
JS;
$this->registerJs($js, $this::POS_READY);*/
?>