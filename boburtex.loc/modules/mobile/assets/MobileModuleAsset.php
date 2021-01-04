<?php


namespace app\modules\mobile\assets;


use yii\web\AssetBundle;

class MobileModuleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
//        'css/alt/AdminLTE-select2.min.css',
//        'css/pnotify.css',
        'css/site.css',
        'css/mobile/main.css',
    ];
    public $js = [
        'js/adminlte.min.js',
        'js/demo.js',
        //'js/toquv-directory.js',
        'js/device.js',
        'js/myjs.js',
//        'js/pnotify.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        PNotify5Asset::class,
    ];
}