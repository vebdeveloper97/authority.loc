<?php

namespace backend\modules\admin\assets;

use yii\web\YiiAsset;
use yii\web\AssetBundle;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use backend\modules\adminlte\assets\AdminLteAsset;
use backend\modules\adminlte\assets\FontAwesomeAsset;
use backend\modules\adminlte\assets\JquerySlimScrollAsset;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/admin.css',
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        AdminLteAsset::class,
        FontAwesomeAsset::class,
        JquerySlimScrollAsset::class,
    ];
}
