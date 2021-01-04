<?php

use yii\helpers\Html;
use app\modules\hr\models\HrServices;
/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrServices */

$this->title = Yii::t('app', 'Update', [
    'name' => $model->hrEmployee->fish,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app',HrServices::getServiceTypeBySlug($this->context->slug) ), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->hrEmployee->fish, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hr-services-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
