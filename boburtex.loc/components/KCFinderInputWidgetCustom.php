<?php
namespace app\components;

use iutbay\yii2kcfinder\KCFinderAsset;
use iutbay\yii2kcfinder\KCFinderInputWidget;
use iutbay\yii2kcfinder\KCFinderWidgetAsset;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use iutbay\yii2fontawesome\FontAwesome;
use yii\helpers\Json;

?>

<?php

/**
 * Class KCFinderInputWidgetCustom
 */
class KCFinderInputWidgetCustom extends KCFinderInputWidget{

    public $isMultipleValue = false;
    public $isSingleValue = false;
    public $withTabular = false;
    public $indexTabular = 0;
    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();

        $button = Html::button(FontAwesome::icon('picture-o') . ' ' . $this->buttonLabel, $this->buttonOptions);

        if ($this->iframe) {
            $button.= Modal::widget([
                'id' => $this->getIFrameModalId(),
                'header' => Html::tag('h4', $this->modalTitle, ['class' => 'modal-title']),
                'size' => Modal::SIZE_LARGE,
                'options' => [
                    'class' => 'kcfinder-modal',
                ],
            ]);
        }

        $thumbs = '';
        if($this->withTabular && !empty($this->value)){
            if ($this->hasModel() && !empty($this->model->{$this->attribute})) {
                $path = $this->model->{$this->attribute};
                $thumbs .= strtr($this->thumbTemplate, [
                    '{thumbSrc}' => $this->getThumbSrc($path),
                    '{inputName}' => $this->getInputNameCustom($this->model, $this->attribute, $this->indexTabular),
                    '{inputValue}' => $path,
                ]);
            }
        }elseif ($this->isMultipleValue && !empty($this->value)) {
            $images = $this->value;
            foreach ($images as $path) {
                $thumbs .= strtr($this->thumbTemplate, [
                    '{thumbSrc}' => $this->getThumbSrc($path),
                    '{inputName}' => $this->getInputName(),
                    '{inputValue}' => $path,
                ]);
            }
        }elseif ($this->isSingleValue) {
            if ($this->hasModel() && !empty($this->model->{$this->attribute})) {
                $path = $this->model->{$this->attribute};
                $thumbs .= strtr($this->thumbTemplate, [
                    '{thumbSrc}' => $this->getThumbSrc($path),
                    '{inputName}' => $this->getInputName(),
                    '{inputValue}' => $path,
                ]);
            }
        }else{
            if ($this->hasModel() && is_array($this->model->{$this->attribute})) {
                $images = $this->model->{$this->attribute};
                foreach ($images as $path) {
                    $thumbs.= strtr($this->thumbTemplate, [
                        '{thumbSrc}' => $this->getThumbSrc($path),
                        '{inputName}' => $this->getInputName(),
                        '{inputValue}' => $path,
                    ]);
                }
            }
        }
        $thumbs = Html::tag('ul', $thumbs, ['id' => $this->getThumbsId(), 'class' => 'kcf-thumbs']);
        echo Html::tag('div', strtr($this->template, [
            '{button}' => $button,
            '{thumbs}' => $thumbs,
        ]), ['class' => 'kcf-input-group']);
    }

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if($this->withTabular){
            $this->clientOptions['inputName'] = $this->getInputNameCustom($this->model, $this->attribute, $this->indexTabular);
        }
    }

    public function getInputNameCustom($model, $attribute, $index)
    {
        $formName = $model->formName();
        if (!preg_match(Html::$attributeRegex, $attribute, $matches)) {
            throw new InvalidArgumentException('Attribute name must contain word characters only.');
        }
        $prefix = $matches[1];
        $attribute = $matches[2];
        $suffix = $matches[3];
        if ($formName === '' && $prefix === '') {
            return $attribute . $suffix;
        } elseif ($formName !== '') {
            return $formName ."[{$index}]". $prefix . "[$attribute]" . $suffix;
        }

        throw new InvalidArgumentException(get_class($model) . '::formName() cannot be empty for tabular inputs.');
    }
}
?>
