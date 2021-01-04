<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */
namespace app\components\CustomFileInput;

use kartik\file\DomPurifyAsset;
use kartik\file\FileInput;
use kartik\file\FileInputThemeAsset;
use kartik\file\PiExifAsset;
use kartik\file\SortableAsset;
use yii\helpers\ArrayHelper;

/**
 * Class CustomFileInput
 */
class CustomFileInput extends FileInput{
    /**
     * Registers the asset bundle and locale
     * @throws \yii\base\InvalidConfigException
     */
    public function registerAssetBundle()
    {
        $view = $this->getView();
        $this->pluginOptions['resizeImage'] = $this->resizeImages;
        $this->pluginOptions['autoOrientImage'] = $this->autoOrientImages;
        if ($this->resizeImages || $this->autoOrientImages) {
            PiExifAsset::register($view);
        }
        if (empty($this->pluginOptions['theme']) && $this->isBs4()) {
            $this->pluginOptions['theme'] = 'fas';
        }
        $theme = ArrayHelper::getValue($this->pluginOptions, 'theme');
        if (!empty($theme) && in_array($theme, self::$_themes)) {
            FileInputThemeAsset::register($view)->addTheme($theme);
        }
        if ($this->sortThumbs) {
            SortableAsset::register($view);
        }
        if ($this->purifyHtml) {
            DomPurifyAsset::register($view);
            $this->pluginOptions['purifyHtml'] = true;
        }
        CustomFileInputAsset::register($view)->addLanguage($this->language, '', 'js/locales');
    }
}
