<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrServices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-services-form">

    <?php $form = ActiveForm::begin([]); ?>


    <?php

    echo $this->render("_{$this->context->slug}",[
            'model' => $model,
            'form' => $form
    ])
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
