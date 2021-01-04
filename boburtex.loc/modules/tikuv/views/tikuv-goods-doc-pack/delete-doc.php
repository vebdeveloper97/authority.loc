<?php

use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $data array */
/* @var $action string */

?>

<?php $form = ActiveForm::begin(['method' => 'POST'])?>
<label for="nastel-no">Tayyor maxsulot hujjat ID raqamini kiriting</label>
<?= $form->field($model,'topp_id')->textInput()?>
<input type="submit" value="OK">
<?php ActiveForm::end()?>
