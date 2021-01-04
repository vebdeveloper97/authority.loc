<?php


namespace app\models\rest;


use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

class ProductDocument extends \app\models\ProductDocument implements Linkable
{
    public function fields()
    {
        return [
            'doc_number',
            'doc_type'
        ];
    }

    public function extraFields()
    {
        return [
           'productDocumentItems'
        ];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
    }
}