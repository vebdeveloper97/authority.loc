<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvRawMaterials */

$this->title = Yii::t('app', 'Toquv Raw Materials');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Raw Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-raw-materials-create">

    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => [],
    ])
    ?>

</div>
