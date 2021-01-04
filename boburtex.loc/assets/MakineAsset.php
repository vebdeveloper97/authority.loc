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
class MakineAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "/makine/monthPicker/jquery-ui.css",
        "/makine/bootstrap-daterangepicker/daterangepicker.scss"
    ];
    public $js = [
        "makine/moment/moment.js",
        "makine/bootstrap-daterangepicker/daterangepicker.js",
        "makine/monthPicker/jquery-ui.js",
        "makine/monthPicker/jquery.mtz.monthpicker.js",
        "makine/amcharts4/core.js",
        "makine/amcharts4/charts.js",
        "makine/amcharts4/animated.js",
        "makine/makine/fancywebsocket.js",
        "makine/makine/MachineAtchot.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
