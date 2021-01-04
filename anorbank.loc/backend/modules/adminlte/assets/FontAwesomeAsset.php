<?php

namespace backend\modules\adminlte\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/adminlte/resources/plugins/font-awesome';

    public $css = [
        'css/font-awesome.min.css',
    ];

    public $js = [

    ];
}
