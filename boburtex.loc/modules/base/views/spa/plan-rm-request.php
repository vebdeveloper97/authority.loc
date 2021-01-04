<?php

use app\assets\ReactSPAAsset;

/* @var $this \yii\web\View */

$this->title = Yii::t('app','Karta yaratish');
ReactSPAAsset::$reactFileName = 'index';
ReactSPAAsset::register($this);
?>
<div id="root"></div>
