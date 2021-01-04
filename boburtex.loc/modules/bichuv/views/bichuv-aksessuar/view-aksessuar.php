<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="toquv-documents-index">


        <?php $gridColumns = [
            [
                'class'=>'kartik\grid\SerialColumn',
                 'pageSummaryOptions' => ['colspan' => 2],
                'width'=>'36px',
                 'header'=>'',
                'headerOptions'=>['class'=>'kartik-sheet-style']
            ],

            [
                'attribute' => 'doc',
                'value' => function ($model ) {
                    $name = '№'.$model['doc'].'<br>'.date('d.m.Y H:i', strtotime($model['reg_date']));
                    return "<code>" . $name. '</code>';
                },
                'label' => Yii::t('app','Hujjat'),
                'width' => '20%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('app','Qayerga'),
                'value' => function ($model) {
                    return  $model['name'];
                },
                'width' => '16%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
            ],
            [
                'attribute' => 'madel_nomi',
                'label' => Yii::t('app','Model'),
                'value' => function($model){
                    return $model['madel_nomi'];
                },
                'width' => '16%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
            ],
            [
                'attribute' => 'nastel_no',
                'label' => Yii::t('app','Model'),
                'value' => function($model){
                    return $model['nastel_no'];
                },
                'width' => '16%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
            ],
         ] ; ?>
        <?= \kartik\grid\GridView::widget([
            'id' => 'kv-grid-demo',
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $model,
            'floatHeader' => ($model->float_header&&$model->float_header==1)?true:false,
            /* 'floatHeaderOptions'=>['top'=>'0'],*/
            'floatOverflowContainer' => ($model->float_header&&$model->float_header==1)?true:false,
//                'responsiveWrap' => true,
            'columns' => $gridColumns,
            'perfectScrollbar' => true,
            'autoXlFormat'=>true,
            'toolbar' =>  [
       //         '{export}',
         //       '{toggleData}',
                /*$fullExportMenu*/
            ],
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'export' => [
                'label' => 'Page',
            ],
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            // parameters from the demo form
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'hover' => true,
            'showPageSummary' => true,
            'panel' => [
                'type' => \kartik\grid\GridView::TYPE_DEFAULT,
           //     'heading' => Yii::t('app', 'Qabul qilingan tayyor maxsulotlar'),
            ],
            'persistResize' => true,
            //'toggleDataOptions' => ['minCount' => 10],
            /*'exportConfig' => $exportConfig,*/
        ]); ?>


    </div>
<?php
$expand_header = Yii::t('app', 'Dokumentlar');
$js = <<< JS
    $('.expand-header').html("<code>{$expand_header}</code><br>{$data}");
     $('#bichuvmatosearch-status').find('option[value=0]').remove();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
