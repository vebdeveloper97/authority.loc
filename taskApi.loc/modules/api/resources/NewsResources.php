<?php


namespace app\modules\api\resources;

use app\models\News;

class NewsResources extends News
{
    public function rules()
    {
        return parent::rules();
    }

    public function fields()
    {
        return [
            'title',
            'content',
            'author_id',
            'id'
        ];
    }

    public function extraFields()
    {
        return ['users'];
    }

}