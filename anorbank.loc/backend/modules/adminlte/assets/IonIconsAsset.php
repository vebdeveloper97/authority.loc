<?php

namespace backend\modules\adminlte\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

class IonIconsAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/adminlte/resources/plugins/ionicons';

    public $css = [
        'css/ionicons.min.css',
    ];

    public $js = [
    ];
}
