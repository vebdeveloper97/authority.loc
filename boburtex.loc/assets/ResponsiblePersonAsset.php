<?php


namespace app\assets;


use yii\web\AssetBundle;

class ResponsiblePersonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/responsible_person.js',
    ];

    public $depends = [
        AppAsset::class,
    ];
}