<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Document Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-document-items-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Toquv Document Items'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'toquv_document_id',
            'entity_id',
            'entity_type',
            'quantity',
            //'price_sum',
            //'price_usd',
            //'current_usd',
            //'is_own',
            //'package_type',
            //'package_qty',
            //'lot',
            //'status',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'unit_id',
            //'document_qty',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
