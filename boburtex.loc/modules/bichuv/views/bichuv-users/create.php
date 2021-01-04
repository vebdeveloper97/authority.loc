<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $info app\models\UsersInfo */

$this->title = Yii::t('app', 'Create Bichuv Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-users-create">

    <?= $this->render('_form', [
        'model' => $model,
        'info' => $info
    ]) ?>

</div>
