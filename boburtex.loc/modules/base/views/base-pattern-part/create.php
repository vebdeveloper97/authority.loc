<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatternPart */

$this->title = Yii::t('app', 'Create Base Pattern Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Pattern Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-pattern-part-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
