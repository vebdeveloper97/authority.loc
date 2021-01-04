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
class ReactSPAAsset extends AssetBundle
{
    public static $reactFileName = 'index';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
        'css/jquery-ui.min.css',
        'newreactjs/dist/css/style-react.css',
        'newreactjs/dist/css/ReactToastify.css',
    ];

    public function init()
    {
        parent::init();
        $reactFileName = self::$reactFileName;
        $this->js[] = "newreactjs/dist/app/{$reactFileName}.bundle.js";
    }

    public $js = [
        'js/adminlte.min.js',
        'newreactjs/dist/lib/common.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
