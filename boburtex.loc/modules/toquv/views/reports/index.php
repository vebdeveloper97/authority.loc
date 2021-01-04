<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Item Balances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-item-balance-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Toquv Item Balance'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'entity_id',
            'entity_type',
            'count',
            'inventory',
            //'reg_date',
            //'department_id',
            //'is_own',
            //'price_uzs',
            //'price_usd',
            //'sold_price_uzs',
            //'sold_price_usd',
            //'sum_uzs',
            //'sum_usd',
            //'document_id',
            //'document_type',
            //'version',
            //'comment:ntext',
            //'created_by',
            //'status',
            //'created_at',
            //'updated_at',
            //'price_rub',
            //'sold_price_rub',
            //'price_eur',
            //'sold_price_eur',
            //'lot',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
