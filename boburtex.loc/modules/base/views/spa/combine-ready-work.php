<?php

use app\assets\ReactSPAAsset;

/* @var $this \yii\web\View */

$this->title = Yii::t('app','Tikuv: Tayyor ish kartalarini birlashtirish');
ReactSPAAsset::$reactFileName = 'readywork';
ReactSPAAsset::register($this);
?>
<div id="root"></div>
