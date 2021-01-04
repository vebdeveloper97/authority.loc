<?php

use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $nastels array*/
/* @var $form yii\widgets\ActiveForm */

$url = Url::to(['get-own-nastel']);
$form = ActiveForm::begin();
?>

<div class="combine-nastel">
    <?= $form->field($model, 'main_nastel_no')->widget(Select2::className(),[
        'data' => $nastels,
        'options' => [
             'id' => 'mainNatelNo'
        ],
    ])?>
    <?= $form->field($model, 'nastel')->widget(Select2::className(),[
        'data' => $nastels,
        'options' => [
            'id' => 'childNastelNo',
            'multiple' => true,
        ],
        'pluginEvents' => [

        ]
    ])?>
    <button class="btn btn-success" id="searchNastel">Qidirish</button>
    <div id="inputNastelCombineBox">

    </div>
    <div class="form-group">
        <?= Html::submitButton('Save',['class' => 'btn btn-primary'])?>
    </div>
</div>
<?php
$url = Url::current();
\app\widgets\helpers\Script::begin()
?>
<script>
    $('body').delegate('#searchNastel','click', function (e) {
        let child = $('#childNastelNo').val();
        let main = $('#mainNatelNo').val();
        let params = {};
        params.child = child;
        params.main = main;
        $.ajax({
           url: "<?= $url?>",
           type: 'POST',
           data: params,
           success: function (response) {
                console.log(response);
           } 
        });
    });
</script>
<?php
$this->registerJs("");
\app\widgets\helpers\Script::end();
?>
