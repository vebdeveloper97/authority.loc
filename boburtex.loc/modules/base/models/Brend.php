<?php

namespace app\modules\base\models;

use app\modules\bichuv\models\BichuvAcsAttachment;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brend".
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property string $code
 * @property string $image
 * @property string $token
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 */
class Brend extends BaseModel
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brend';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['full_name', 'image'], 'string', 'max' => 255],
            [['code', 'token'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'full_name' => Yii::t('app', 'Full Name'),
            'code' => Yii::t('app', 'Code'),
            'image' => Yii::t('app', 'Image'),
            'token' => Yii::t('app', 'Token'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getBrends(){
        $brends = self::find()
            ->where(['status' => BichuvAcsAttachment::STATUS_ACTIVE])
            ->asArray()
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $brends = ArrayHelper::map($brends, 'id','name');

        return $brends;
    }
}
