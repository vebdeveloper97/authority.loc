<?php


namespace app\components\CustomBehavior;

use yii\base\InvalidCallException;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

class CustomTimestampBehavior extends TimestampBehavior
{
    /** begin Yaratgan userni id sini yozish uchun */
    public $createdByAttribute = 'created_by';
    /** /end Yaratgan userni id sini yozish uchun */

    /** begin Yangilangan userni id sini yozish uchun */
    public $updatedByAttribute = 'updated_by';
    /** end Yangilangan userni id sini yozish uchun */

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->createdByAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedByAttribute,
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](https://secure.php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return \Yii::$app->user->id;
        }

        return parent::getValue($event);
    }
    /**
     * Updates a timestamp attribute to the current timestamp.
     *
     * ```php
     * $model->touch('lastVisit');
     * ```
     * @param string $attribute the name of the attribute to update.
     * @throws InvalidCallException if owner is a new record (since version 2.0.6).
     */
    public function touch($attribute)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            throw new InvalidCallException('Updating the timestamp is not possible on a new record.');
        }
        $owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
    }

}