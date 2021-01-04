<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 23.04.20 18:21
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;

function __($text, $category = 'app'){
    return Yii::t($category, $text);
}
function url($url = '', $scheme = false)
{
    return Url::to($url, $scheme);
}

function he($text)
{
    return Html::encode($text);
}

function ph($text)
{
    return HtmlPurifier::process($text);
}


function param($name, $default = null)
{
    return ArrayHelper::getValue(Yii::$app->params, $name, $default);
}

function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

