<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $models app\modules\tikuv\models\TikuvGoodsDocItems */

$this->title = Yii::t('app', 'Dokument yaratish');
$i = Yii::$app->request->get('i');
$floor = Yii::$app->request->get('floor',2);
$name = '';
if($i){switch ($i){case 1:$name = Yii::t('app', 'Qabul qilish');break;case 2:$name = Yii::t('app', 'Ko\'chirish');}}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyor maxsulotlar ({name})', ['name' => $name] ), 'url' => ['index','i' => $i, 'floor' => $floor]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-goods-doc-pack-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
