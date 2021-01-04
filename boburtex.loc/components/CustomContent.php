<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 20.02.20 19:05
 */

namespace app\components;

use unclead\multipleinput\components\BaseColumn;
use yii\base\Model;
use yii\helpers\VarDumper;

class CustomContent extends BaseColumn
{
    public $customLayout;
    public $function_name;
    public $is_custom = false;
    public $custom_type = false;
    public $acs = false;
    public $myModel;

    /**
     * Returns element's name.
     *
     * @param int|null|string $index current row index
     * @param bool $withPrefix whether to add prefix.
     * @return string
     */
    public function getElementName($index, $withPrefix = true)
    {
        if ($index === null) {
            $index = '{' . $this->renderer->getIndexPlaceholder() . '}';
        }

        $elementName = '[' . $index . '][' . $this->name . ']';
        $prefix = $withPrefix ? $this->getModel()->formName() : '';

        return $prefix . $elementName . (empty($this->nameSuffix) ? '' : ('_' . $this->nameSuffix));
    }

    /**
     * Returns first error of the current model.
     *
     * @param $index
     * @return string
     */
    public function getFirstError($index)
    {
        return $this->getModel()->getFirstError($this->name);
    }

    /**
     * Ensure that model is an instance of yii\base\Model.
     *
     * @param $model
     * @return bool
     */
    protected function ensureModel($model)
    {
        return $model instanceof Model;
    }

    /**
     * @inheritdoc
     */
    public function setModel($model)
    {
        if ($model === null) {
            $model = \Yii::createObject(['class' => $this->context->modelClass]);
        }
        parent::setModel($model);
    }

    public function renderInput($name, $options, $contextParams = [])
    {
        if(!$this->is_custom) return parent::renderInput($name, $options, $contextParams = []);
        $model = $this->getModel();
        if($model->tableName()=='toquv_document_items'&&$model->hasMethod('getColor')) {
            return $model->getColor($this->custom_type);
        }
        $counter = ($this->myModel->hasMethod('getCounter'))?$this->myModel->getCounter($this->acs):false;
        return ($model->hasMethod('getChild')&&is_int($counter))?$model->getChild($counter):$counter;
    }
}