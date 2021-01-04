<?php


namespace app\modules\hr\assets;


use Yii;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

class TinymceAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    private $tinyApiKey = 'yq1yc4dka9q9w5gek9vad644jagm5x5cruaqprbb9h2r25u3';

    public function init()
    {
        parent::init();
        $this->js[] = 'https://cdn.tiny.cloud/1/'.$this->tinyApiKey.'/tinymce/5/tinymce.min.js';
    }
    
    public $jsOptions = [
        'referrerpolicy' => "origin",
    ];

    public $depends = [
        YiiAsset::class,
    ];
}