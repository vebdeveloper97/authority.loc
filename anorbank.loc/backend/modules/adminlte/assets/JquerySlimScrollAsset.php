<?php

namespace backend\modules\adminlte\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

class JquerySlimScrollAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/adminlte/resources/plugins/jquery-slimscroll';

    public $css = [
    ];

    public $js = [
        'jquery.slimscroll.min.js',
    ];
}
