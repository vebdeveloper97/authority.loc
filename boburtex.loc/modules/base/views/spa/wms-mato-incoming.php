<?php

use app\assets\ReactSPAAsset;

/* @var $this \yii\web\View */

$this->title = Yii::t('app','Bichuv: Kesim detallarini birlashtirish');
ReactSPAAsset::$reactFileName = 'wmsmatoincoming';
ReactSPAAsset::register($this);
?>
<div id="root"></div>
