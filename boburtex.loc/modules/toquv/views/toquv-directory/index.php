<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvNeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @var $searchPusFine app\modules\toquv\models\ToquvPusFineSearch */
/* @var $dataPusFine yii\data\ActiveDataProvider */

/* @var $searchThread app\modules\toquv\models\ToquvThreadSearch */
/* @var $dataThread yii\data\ActiveDataProvider */

$this->title = Yii::t('app', "Toquv Ma'lumotnoma");
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <!--ModalNe-->
    <div class="toquv-ne-index col-md-4">
        <h3 style="font-weight:bold;">Ne/Deteks/Denye
            <?php
            if (Yii::$app->user->can('toquv-ne/create')):?>
                <span class="pull-right">
                    <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                        [
                            'value' =>Url::to(['toquv-ne/create']),
                            'class' => 'btn btn-xs btn-success neModelBtn',
                            'id' => 'modalNe',
                            'data-action-type' => 'create',
                        ])
                    ?>
                </span>
                <?php Modal::begin([
                    'header' => '<h4>Ne/Deteks/Denye</h4>',
                    'id' => 'neModelType',
                    'size' => 'modal-sm',
                ]);
                echo "<div id='modaltoquvNe'></div>";
                Modal::end();
                ?>
            <?php endif;?>
        </h3>
        <?= $this->render('_ne',['dataProvider'=> $dataProvider, 'searchModel' => $searchModel])?>
    </div>


    <!--ModalPusFine-->
    <div class="toquv-pus-fine-index col-md-4">
        <h3 style="font-weight: bold;">Pus/Fine
            <?php
            if (Yii::$app->user->can('toquv-pus-fine/create')):?>
                <span class="pull-right">
                    <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                        [
                            'value' =>Url::to(['toquv-pus-fine/create']),
                            'class' => 'btn pusModelBtn btn-xs btn-success',
                            'id' => 'modalPus',
                            'data-action-type' => 'create',
                        ]) ?>
                </span>
                <?php Modal::begin([
                    'header' => '<h4>Pus/Fine</h4>',
                    'id' => 'pusfineModelType',
                    'size' => 'modal-sm',
                ]);
                echo "<div id='modalPusFine'></div>";
                Modal::end();
                ?>
            <?php endif;?>
        </h3>
        <?=$this->render('_pus',['dataPusFine' => $dataPusFine, 'searchPusFine' => $searchPusFine])?>
    </div>


    <!--ModalThread-->
    <div class="toquv-thread-index col-md-4">
        <h3 style="font-weight: bold">Iplik turi
            <?php
            if (Yii::$app->user->can('toquv-thread/create')):?>
                <span class="pull-right">
                    <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                        [
                            'value' =>Url::to(['toquv-thread/create']),
                            'class' => 'btn btn-xs btn-success threadModelBtn',
                            'data-action-type' => 'create',
                        ]) ?>
                </span>
                <?php Modal::begin([
                    'header' => '<h4>Ip</h4>',
                    'id' => 'threadModelType',
                    'size' => 'modal-sm',
                ]);
                echo "<div id='modaltoquvThread'></div>";
                Modal::end();
                ?>
            <?php endif;?>
        </h3>
        <?=$this->render('_thread',['dataThread' => $dataThread, 'searchThread' => $searchThread])?>
    </div>
</div>
<?php $this->registerJsFile(
        Yii::$app->request->baseUrl.'/js/toquv-directory.js',
        [
           'depends' => [\yii\web\JqueryAsset::className()]
        ]
);
?>

