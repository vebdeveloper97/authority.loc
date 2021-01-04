<?php

namespace backend\modules\adminlte\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/adminlte/resources/plugins/adminlte';

    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/skin-blue.min.css',
    ];

    public $js = [
        'js/adminlte.min.js',
    ];
}
