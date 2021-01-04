<?php

use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this \yii\web\View */
/* @var $data array */
/* @var $action string */

?>

<?php $form = ActiveForm::begin(['method' => 'POST'])?>
    <label for="nastel-no">Ochirish uchun nastel raqamini tanlang</label>
    <?= $form->field($model,'nastel_no')->widget(Select2::className(),
        [
            'data' => $data,
            'options' => [
                'id' => 'nastel-no'
            ]
    ])?>
    <input type="submit" value="OK">
<?php ActiveForm::end()?>
