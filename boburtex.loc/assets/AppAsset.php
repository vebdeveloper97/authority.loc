<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
//        'css/alt/AdminLTE-select2.min.css',
        'css/pnotify.css',
        'css/site.css',
    ];
    public $js = [
        'js/adminlte.min.js',
        'js/demo.js',
        //'js/toquv-directory.js',
        'js/device.js',
        'js/myjs.js',
        'js/pnotify.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        //'app\assets\SweetAlertAsset',
    ];
}
