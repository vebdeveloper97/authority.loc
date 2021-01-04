<?php


namespace app\modules\mobile\assets;


use yii\web\AssetBundle;

class PNotify5Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'js/PNotify5/core/BrightTheme.css',
        'js/PNotify5/core/Material.css',
        'js/PNotify5/core/PNotify.css',
        'js/PNotify5/mobile/PNotifyMobile.css',
        'js/PNotify5/confirm/PNotifyConfirm.css',
    ];

    public $js = [
        'js/PNotify5/core/PNotify.js',
        'js/PNotify5/mobile/PNotifyMobile.js',
        'js/PNotify5/confirm/PNotifyConfirm.js',
        'js/PNotify5/animate/PNotifyAnimate.js',
        'js/PNotify5/font-awesome4/PNotifyFontAwesome4.js',
        'js/PNotify5/font-awesome5/PNotifyFontAwesome5.js',
        'js/PNotify5/font-awesome5-fix/PNotifyFontAwesome5Fix.js',
    ];

}