<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $models app\modules\tikuv\models\TikuvGoodsDoc */

$this->title = Yii::t('app', 'Dokument o\'zgartirish: {name}', [
    'name' => $model->doc_number,
]);
$i = Yii::$app->request->get('i');
$fl = Yii::$app->request->get('floor', 2);
$name = '';
if($i){switch ($i){case 1:$name = Yii::t('app', 'Qabul qilish');break;case 2:$name = Yii::t('app', 'Ko\'chirish');}}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyor maxsulotlar ({name})', ['name' => $name] ), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id,'floor' => $fl]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tikuv-goods-doc-pack-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
