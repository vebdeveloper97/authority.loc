<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.03.20 0:19
 */

/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\tikuv\models\TikuvKonveyer */
/* @var $dataProvider  */

use yii\bootstrap\Tabs;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
    <ul class="nav nav-tabs tab__ul">
        <li >
            <a href="<?= Url::to('/bichuv/bichuv-given-rolls/index')?>">
                <?= Yii::t('app', 'Ishlab chiqarish')?>
            </a>
        </li>
        <li class="active">
            <a>
                <?= Yii::t('app', 'Navbat')?>
            </a>
        </li>
    </ul>
<br>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function($model){
       if($model['status'] == \app\modules\bichuv\models\BichuvTableRelWmsDoc::STATUS_STARTED){
           return ['class' => 'success'];
       }
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'musteri',
            'label' => Yii::t('app', 'Buyurtmachi'),
        ],
        [
            'attribute' => 'nastel_no',
            'label' => Yii::t('app', 'Nastel Party'),
        ],
        [
            'attribute' => 'model',
            'label' => Yii::t('app', 'Model'),
        ],
        [
            'attribute' => 'color',
            'label' => Yii::t('app', 'Color'),
        ],
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'Status'),
            'value' => function($model){
                return \app\modules\bichuv\models\BichuvTableRelWmsDoc::getStatusProcess($model['status']);
            },
            'format' => 'raw'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {given-roll}',
            'contentOptions' => ['class' => 'no-print'],
            'headerOptions' => ['style'=>'width:90px'],
            'visibleButtons' => [
                'given-roll' => function($model){
                    return \app\modules\bichuv\models\BichuvTableRelWmsDoc::getPermissionByICH($model['id']);
                },
            ],
            'buttons' => [
                'view' => function ($url, $m) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $m['id']], [
                        'title' => Yii::t('app', 'View'),
                        'class'=> 'btn btn-xs btn-primary',
                    ]);
                },
                'given-roll' => function ($url, $m) {
                    return Html::a('<span class="fa fa-sign-out"></span>', ['/bichuv/bichuv-given-rolls/create', 'id' => $m['id']], [
                        'title' => Yii::t('app', 'View'),
                        'class'=> 'btn btn-xs btn-success',
                    ]);
                },
            ],
        ],
    ],
]); ?>