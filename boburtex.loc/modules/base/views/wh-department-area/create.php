<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDepartmentArea */

$this->title = Yii::t('app', 'Create Wh Department Area');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Department Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-department-area-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
