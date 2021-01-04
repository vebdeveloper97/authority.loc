<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatternItems */

$this->title = Yii::t('app', 'Create Base Pattern Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Pattern Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-pattern-items-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
