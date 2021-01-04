<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Brend */

$this->title = Yii::t('app', 'Create Brend');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brends'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brend-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
