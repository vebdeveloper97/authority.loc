<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItemCountry */

$this->title = Yii::t('app', 'Create Wh Item Country');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Item Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-item-country-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
