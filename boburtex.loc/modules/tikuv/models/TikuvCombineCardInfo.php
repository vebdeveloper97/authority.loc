<?php

namespace app\modules\tikuv\models;

use app\modules\base\models\Size;
use Yii;

/**
 * This is the model class for table "tikuv_combine_card_info".
 *
 * @property int $id
 * @property int $size_id
 * @property string $nastel_no
 * @property int $parent
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Size $size
 */
class TikuvCombineCardInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_combine_card_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_id', 'parent', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['nastel_no'], 'string', 'max' => 50],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::class, 'targetAttribute' => ['size_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'size_id' => Yii::t('app', 'Size ID'),
            'nastel_no' => Yii::t('app', 'Nastel No'),
            'parent' => Yii::t('app', 'Parent'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::class, ['id' => 'size_id']);
    }
}
