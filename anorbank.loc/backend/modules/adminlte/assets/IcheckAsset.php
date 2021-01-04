<?php

namespace backend\modules\adminlte\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

class IcheckAsset extends AssetBundle
{

    public $sourcePath = '@backend/modules/adminlte/resources/plugins/icheck';

    public $css = [
        'skins/square/blue.css',
    ];

    public $js = [
        'icheck.min.js',
    ];
}
