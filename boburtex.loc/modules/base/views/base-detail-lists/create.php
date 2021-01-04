<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseDetailLists */

$this->title = Yii::t('app', 'Create Base Detail Lists');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Detail Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-detail-lists-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
