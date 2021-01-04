<?php

namespace app\components\CustomFileInput;

use kartik\base\AssetBundle;

/**
 * BaseAsset is the base asset bundle class used by all FileInput widget asset bundles.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class CustomBaseAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public $sourcePath = '@webroot/bootstrap-fileinput';
}