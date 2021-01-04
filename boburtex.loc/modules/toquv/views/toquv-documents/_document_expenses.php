<?php

/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-md-4">
    <?= $form->field($modelTDE, 'price')->textInput()?>
</div>
<div class="col-md-4">
    <?= $form->field($modelTDE, 'pb_id')->dropDownList($modelTDE->getPulBirligi())?>
</div>
<div class="col-md-4">
    <?= $form->field($modelTDE, 'add_info')->textarea(['rows' => 1])?>
</div>
