<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TOPPSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tikuv Outcome Products Reports');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-outcome-products-pack-index">
        <p class="pull-right no-print">
            <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                ['export-excel?type=report'], ['class' => 'btn btn-sm btn-info']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'doc_number',
            'model_no',
            'color_code',
            'size_name',
            'barcode',
            'quantity',
            'accepted'
           ],
    ]); ?>
</div>
