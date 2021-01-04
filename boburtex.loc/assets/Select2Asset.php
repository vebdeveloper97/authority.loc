<?php


namespace app\assets;


use yii\web\JqueryAsset;

class Select2Asset extends AppAsset
{
    public $baseUrl = '@web';
    public $basePath = '@webroot';

    public $css = [
        'select2/4.0.13/select2.min.css',
    ];

    public $js = [
        'select2/4.0.13/select2.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}