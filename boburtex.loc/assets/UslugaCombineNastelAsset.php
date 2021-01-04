<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.06.20 12:20
 */

namespace app\assets;


use yii\web\AssetBundle;

class UslugaCombineNastelAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
        'reactjs/dist/css/style-react.css',
        'reactjs/dist/css/ReactToastify.css',
    ];
    public $js = [
        /*'js/adminlte.min.js',
        'reactjs/dist/app/nastel.js'*/
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
