<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatterns */
/* @var $postals \app\modules\base\models\BasePatternMiniPostal */
$this->title = Yii::t('app', 'Create Base Patterns');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Patterns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-patterns-create">

    <?= $this->render('_form', [
        'model' => $model,
        'postals' => $postals,
    ]) ?>

</div>
