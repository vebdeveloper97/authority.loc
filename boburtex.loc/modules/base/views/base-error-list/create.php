<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseErrorList */

$this->title = Yii::t('app', 'Create Base Error List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Error Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-error-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
