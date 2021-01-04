<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\SearchFormViaNastel */

?>
<div class="tikuv-doc-search">

    <?php $form = ActiveForm::begin(['method' => 'GET', 'id' => 'tikuvSearchNastel',/*'action' => Url::to(['tikuv/conveyor-in'])*/]); ?>

    <?= $form->field($model, 'nastel_no',
        ['template' => "{beginLabel}{labelTitle}{endLabel}\n<div class=\"input-group input-group-lg\">{input}\n<span class=\"input-group-btn\"><button class=\"btn btn-default\" type=\"submit\"><i class=\"fa fa-search\" aria-hidden=\"true\"></i></button></span></div>\n{hint}\n{error}"])
        ->textInput([
            'placeholder' => Yii::t('app','Nastel No'),
            'id' => 'tikuvNastelNo'
        ])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>