<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\FinanceExpenseList */

$this->title = Yii::t('app', 'Create Finance Expense List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Expense Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-expense-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
