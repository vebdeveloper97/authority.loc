<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmploymentHistory */

$this->title = Yii::t('app', 'Add Employment Histories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Employment Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-employment-history-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
