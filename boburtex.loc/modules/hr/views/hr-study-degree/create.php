<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrStudyDegree */

$this->title = Yii::t('app', 'Create Hr Study Degree');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Study Degrees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-study-degree-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
