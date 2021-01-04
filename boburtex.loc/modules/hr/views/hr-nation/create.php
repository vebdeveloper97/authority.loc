<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrNation */

$this->title = Yii::t('app', 'Create Hr Nation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Nations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-nation-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
