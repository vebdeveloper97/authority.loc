<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.9
 */

namespace app\components\CustomFileInput;

/**
 * Asset bundle for FileInput Widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class CustomFileInputAsset extends CustomBaseAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setupAssets('css', ['css/fileinput']);
        $this->setupAssets('js', ['js/fileinput']);
        parent::init();
    }
}
