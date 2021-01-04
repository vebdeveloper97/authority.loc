<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.03.20 0:19
 */



/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\tikuv\models\TikuvKonveyer */
/* @var $dataProvider  */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax; ?>
<?php Pjax::begin(['id' => 'nastelListPjax'])?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterRowOptions' => ['class' => 'filters no-print tableexport-ignore'],
    'filterModel' => $searchModel,
    'showFooter' => false,
    'footerRowOptions' => ['style'=>'font-weight:bold'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
            'attribute' => 'detail',
            'label' => Yii::t('app', 'Detail Name'),
//            'filter' => Html::dropDownList()
        ],
        [
            'attribute' => 'nastel_party',
            'label' => Yii::t('app', 'Nastel Party'),
        ],
        [
            'attribute' => 'musteri',
            'label' => Yii::t('app', 'Buyurtmachi'),
        ],
        [
            'attribute' => 'mato',
            'label' => Yii::t('app', 'Mato'),
        ],
        [
            'attribute' => 'model',
            'label' => Yii::t('app', 'Model'),
        ],
        [
            'attribute' => 'required_count',
            'label' => Yii::t('app', 'Required Count'),
        ],
        [
            'attribute' => 'color',
            'label' => Yii::t('app', 'Color'),
        ],
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Status'),
            'value' => function($model){
                return \app\modules\bichuv\models\BichuvGivenRollItems::getStatusProcess($model['status']);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{confirm}',
            'contentOptions' => ['class' => 'no-print'],
            'headerOptions' => ['style'=>'width:90px'],
            'visibleButtons' => [
//                'view' => Yii::$app->user->can('mato/view'),
                'confirm' => function($model){
                    return $model['status'] < \app\modules\bichuv\models\BichuvGivenRollItems::STATUS_END;
                },
            ],
            'buttons' => [
                'view' => function ($url, $m) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $m['id']], [
                        'title' => Yii::t('app', 'View'),
                        'class'=> 'btn btn-xs btn-primary view-dialog',
                    ]);
                },
                'confirm' => function ($url, $m) {
                    return Html::a('<i class="fa fa-check"></i>', ['confirm', 'id' => $m['id']], [
                        'title' => Yii::t('app', 'View'),
                        'data-form-id' => $m['id'],
                        'class'=> 'btn btn-xs btn-success default-button check-nastel',
                    ]);
                },
            ],
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
<?php
$js = <<< JS
    $('body').delegate('.check-nastel', 'click', function(e){
        e.preventDefault();
        if(confirm("Siz rostdan ham ushbu amalni bajarmoqchimisiz?")){
            $.ajax({
                url: $(this).attr('href'),
                data: {id:$(this).data('form-id')},
                type: "GET",
                success: function (response) {
                    if(response.status == 1){
                        call_pnotify('success',response.message);
                        $.pjax.reload({container:"#nastelListPjax"});  
                    }else{
                        call_pnotify('fail',response.message);
                    }
                }
            });
        }
    });
    function call_pnotify(status,message) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:message,type:'success'});
                break;
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:message,type:'error'});
                break;
        }
    }
JS;
$this->registerJs($js,\yii\web\View::POS_READY);