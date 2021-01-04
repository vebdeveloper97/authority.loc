<?php


namespace app\models;

use Yii;

/**
 * Class PaymentMethod
 * @package app\models
 */
class PaymentMethod
{
    /**
     * @param int $id
     * @return array
     */
    public static function getData($id = 0)
    {
        $data = ['1'=>Yii::t('app','Cash'),
                '2'=>Yii::t('app','Bank Card'),
                '3'=>Yii::t('app','Bank Transfer')];


        if ($id) {
            return $data[$id];
        }

        return $data;
    }
}