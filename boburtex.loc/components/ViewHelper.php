<?php


namespace app\components;


class ViewHelper
{
    public static function indexBy($array, $key = 'id') {
        $rez = array();
        foreach ($array as $item)
            $rez[$item[$key]] = $item;
        return $rez;
    }
}