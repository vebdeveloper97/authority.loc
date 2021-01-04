<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\BarcodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \app\modules\base\models\Goods */

$this->title = Yii::t('app', 'Barcode');
$this->params['breadcrumbs'][] = $this->title;
$urlRemain = Yii::$app->urlManager->createUrl('base/model-orders/ajax-request');
?>
    <div class="barcode-index">
        <p class="pull-right no-print">
            <button id="addNewModelBarcodeBtn" class="btn btn-sm btn-success"><span class="fa fa-plus"></span></button>
            <?= Html::a(Yii::t('app', 'Filtrni tozalash'), ['index'],
                ['class' => 'index btn btn-sm btn-default']) ?>
        </p>
        <?php Pjax::begin(['id' => 'barcode_pjax']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'barcode',
                    'headerOptions' => [
                        'style' => 'width:20%'
                    ],
                    'format' => 'raw'
                ],
                'model_no',
                [
                    'attribute' => 'size',
                    'value' => function ($model) {
                        return $model->sizeName->name;
                    },
                    'headerOptions' => [
                        'style' => 'width:5%'
                    ],
                ],
                [
                    'attribute' => 'color',
                    'value' => function ($model) {
                        return $model->colorPantone->code;
                    },
                    'headerOptions' => [
                        'style' => 'width:20%'
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'contentOptions' => ['class' => 'no-print', 'style' => 'width:10px;'],
                    'headerOptions' => ['class' => 'no-print', 'style' => 'width:10px;'],
                    'visibleButtons' => [
                        'view' => Yii::$app->user->can('barcode/view'),
                    ],
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class' => 'btn btn-xs btn-primary view-dialog',
                                'data-form-id' => $model->id,
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'addNewModelBarcode',
    'header' => '<h2 class="text-center">' . Yii::t('app', 'Yangi barkod') . '</h2>',
]) ?>

<?php \yii\bootstrap\Modal::end() ?>
<?php $url = Url::to(['add-new-model-barcode'])?>
<?php \app\widgets\helpers\Script::begin()?>
<script>
    $("body").delegate('#addNewModelBarcodeBtn', 'click', function (e) {
        $('#addNewModelBarcode .modal-body').load('<?= $url?>');
        $('#addNewModelBarcode').modal();
    });
</script>
<?php \app\widgets\helpers\Script::end()?>


