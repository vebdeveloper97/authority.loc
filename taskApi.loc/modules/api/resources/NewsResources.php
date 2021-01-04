<?php


namespace app\modules\api\resources;

use app\models\News;
use Yii;

class NewsResources extends News
{
    public function fields()
    {
        return [
            'title',
            'content',
            'author'
        ];
    }
}