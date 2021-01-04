<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTablesUsers */

$this->title = Yii::t('app', 'Update Bichuv Tables Users: {name}', [
    'name' => $model->bichuv_tables_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Tables Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bichuv_tables_id, 'url' => ['view', 'bichuv_tables_id' => $model->bichuv_tables_id, 'users_id' => $model->users_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-tables-users-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
