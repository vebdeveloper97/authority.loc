<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrPosition */

$this->title = Yii::t('app','Position');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-position-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
