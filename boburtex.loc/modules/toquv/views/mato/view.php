<?php

use app\modules\toquv\models\ToquvKalite;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\toquv\models\MatoSearch */
/* @var $dataProvider \yii\data\SqlDataProvider */
/* @var $dataProvider1 \yii\data\SqlDataProvider */
/* @var $model array|false */
/* @var $moi array|false */
/* @var $brak string|null */

$this->title = "{$model['musteri_id']} - {$model['mato']} - {$model['quantity']}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyorlangan matolar'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$index = (!$brak)?'index':'brak';
\yii\web\YiiAsset::register($this);
?>
<?php if(!Yii::$app->request->isAjax){?>
<div class="pull-left" style="padding-left: 15px;padding-bottom: 5px;">
    <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', [$index], ['class' => 'btn btn-info btn-sm','title'=>Yii::t('app', 'Orqaga qaytish')]) ?>
</div>
<?php }?>
<div class="mato-view row">
    <div class="col-md-12">
    <?php if($model){?>
        <div class="col-md-12">
            <table class="table table-bordered text-center">
                <tr>
                    <td>
                        <?=Yii::t('app','Buyurtmachi')?>
                    </td>
                    <th>
                        <?=$model['musteri_id']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Buyurtma')?>
                    </td>
                    <th>
                        <?=$model['doc_number']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Mato nomi')?>
                    </td>
                    <th>
                        <?=$model['mato']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Buyurtma miqdori')?>
                    </td>
                    <th>
                        <?=$model['quantity']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Tayyor bo\'lgan miqdori')?>
                    </td>
                    <th>
                        <?=$model['summa']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Brak bo\'lgan mato')?>
                    </td>
                    <th>
                        <?=($brak_mato = ToquvKalite::getOneKalite($model['tir_id'], null, 'BRAK')['summa'])?$brak_mato:0?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Tayyorlanishi kerak bo\'lgan miqdor')?>
                    </td>
                    <th>
                        <?php $remain = $model['quantity'] - $model['summa']?>
                        <?=($remain>0)?$remain:Yii::t('app', 'Buyurtma bajarildi');?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Pus/Fine')?>
                    </td>
                    <th>
                        <?=$model['pus_fine']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app', 'Thread Length')." - ".Yii::t('app', 'Finish En').' - '.Yii::t('app', 'Finish Gramaj')?>
                    </td>
                    <th>
                        <?=$model['info']?>
                    </th>
                </tr>
                <tr>
                    <td>
                        <?=Yii::t('app','Buyurtma turi')?>
                    </td>
                    <th>
                        <?=\app\modules\toquv\models\ToquvOrders::getOrderTypeList($model['order_type'])?>
                    </th>
                </tr>
                <?php if($moi){?>
                    <tr>
                        <td>
                            <?=Yii::t('app', 'Model')?>
                        </td>
                        <th>
                            <?=$moi->info?>
                        </th>
                    </tr>
                <?php }?>
                <tr>
                    <td>
                        <?=Yii::t('app', 'Rang')?>
                    </td>
                    <th>
                        <?=$model['code']?> <?=(!empty($model['color_id']))?" | ".$model['color_id']:''?>
                    </th>
                </tr>
            </table>
        </div>
    <?php }?>
    </div>
    <div class="col-md-6">
        <h4><?php echo Yii::t('app','Omborga jo\'natilmagan mato')?></h4>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'emptyText' => Yii::t('app', "Ma'lumot mavjud emas"),
            'showFooter' => true,
            'footerRowOptions' => ['style'=>'font-weight:bold'],
            'layout'=> "{items}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'code',
                    'label' => Yii::t('app', 'Kodi'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user_fio',
                    'label' => Yii::t('app', 'To\'quvchi'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'tabel',
                    'label' => Yii::t('app', 'Tabel'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'summa',
                    'label' => Yii::t('app', 'Quantity'),
                    'contentOptions' => [
                        'class' => 'summa_all'
                    ],
                    'footerOptions' => [
                        'id' => 'summa_all'
                    ],
                ],
                [
                    'attribute' => 'sort',
                    'label' => Yii::t('app', 'Sort Name ID'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'kalite_user',
                    'label' => Yii::t('app', 'Tekshiruvchi'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return (time()-$model['created_at']<(60*60*24))?Yii::$app->formatter->format(date($model['created_at']), 'relativeTime'):date('d.m.Y H:i',$model['created_at']);
                    },
                    'label' => Yii::t('app', 'Tayyorlangan vaqt'),
                    'footer' => (Yii::$app->user->can('mato/save-and-finish') && ToquvKalite::getOneKalite($model['tir_id'],1, $brak)['summa']>0)?Html::a('Omborga jo\'natish',
                            \yii\helpers\Url::to(['mato/save-and-finish',
                                'id'=>$model['tir_id'],
                                'mato_id'=>$model['mato_id'],
                                'pus_fine_id'=>$model['pus_fine_id'],
                                'thread_length'=>$model['thread_length'],
                                'finish_en'=>$model['finish_en'],
                                'finish_gramaj'=>$model['finish_gramaj'],
                                'brak' => $brak
                            ]),
                            [
                                'title' => Yii::t('app', 'Omborga jo\'natish'),
                                'class'=> 'btn btn-xs btn-success',
                            ]
                    ):'',
                ],
            ],
        ]); ?>
    </div>
    <div class="col-md-6">
        <h4><?php echo Yii::t('app','Omborga jo\'natilgan mato')?></h4>
        <?= GridView::widget([
            'dataProvider' => $dataProvider1,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'emptyText' => Yii::t('app', "Ma'lumot mavjud emas"),
            'showFooter' => true,
            'footerRowOptions' => ['style'=>'font-weight:bold'],
            'layout'=> "{items}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'code',
                    'label' => Yii::t('app', 'Kodi'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user_fio',
                    'label' => Yii::t('app', 'To\'quvchi'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'tabel',
                    'label' => Yii::t('app', 'Tabel'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'summa',
                    'label' => Yii::t('app', 'Quantity'),
                    'contentOptions' => [
                        'class' => 'summa_send'
                    ],
                    'footerOptions' => [
                        'id' => 'summa_send'
                    ],
                ],
                [
                    'attribute' => 'sort',
                    'label' => Yii::t('app', 'Sort Name ID'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'kalite_user',
                    'label' => Yii::t('app', 'Tekshiruvchi'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return (time()-$model['created_at']<(60*60*24))?Yii::$app->formatter->format(date($model['created_at']), 'relativeTime'):date('d.m.Y H:i',$model['created_at']);
                    },
                    'label' => Yii::t('app', 'Tayyorlangan vaqt'),
                ],
            ],
        ]); ?>
    </div>
</div>
<?php
$js = <<< JS
var summa = 0;
$('.summa_all').each(function (index, value){
    summa += 1*$(this).text();    
});
$('#summa_all').html(summa.toFixed(2));
var summa_send = 0;
$('.summa_send').each(function (index, value){
    summa_send += 1*$(this).text();    
});
$('#summa_send').html(summa_send.toFixed(2));
JS;
$this->registerJs($js,\yii\web\View::POS_READY);?>
