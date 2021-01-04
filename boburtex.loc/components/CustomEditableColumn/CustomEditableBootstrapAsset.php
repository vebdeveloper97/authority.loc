<?php

namespace app\components\CustomEditableColumn;

use yii\web\AssetBundle;

/**
 * Class EditableBootstrapAsset
 *
 * @package yii2mod\editable\bundles
 */
class CustomEditableBootstrapAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/vendor/bower-asset/x-editable/dist/bootstrap3-editable';

    /**
     * @var array
     */
    public $css = [
        'css/bootstrap-editable.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**
     * Init object
     */
    public function init()
    {
        $this->js[] = 'js/bootstrap-editable.min.js';
    }
}
