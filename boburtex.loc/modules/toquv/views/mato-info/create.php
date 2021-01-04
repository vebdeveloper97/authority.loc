<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\MatoInfo */

$this->title = Yii::t('app', 'Create Mato Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mato-info-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
