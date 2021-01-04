<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\FinanceExpenseList */

$this->title = Yii::t('app', 'Update Finance Expense List: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Expense Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-expense-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
