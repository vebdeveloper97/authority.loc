<?php

use yii\helpers\Html;
use \app\modules\hr\models\HrServices;
/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrServices */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', HrServices::getServiceTypeBySlug($this->context->slug)), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-services-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
