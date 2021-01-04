<?php

namespace backend\modules\adminlte\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

class FastClickAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/adminlte/resources/plugins/fastclick';

    public $css = [

    ];

    public $js = [
        'fastclick.js',
    ];
}
