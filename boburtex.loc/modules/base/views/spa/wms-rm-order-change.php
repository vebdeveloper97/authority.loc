<?php
use app\assets\ReactSPAAsset;

/* @var $this \yii\web\View */

$this->title = Yii::t('app',"Mato ombori: Matoni boshqa buyurtmaga ko'chirish");
ReactSPAAsset::$reactFileName = 'wmsmatoorderchange';
ReactSPAAsset::register($this);
?>
<div id="root"></div>
