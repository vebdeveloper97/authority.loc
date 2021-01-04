<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_acs_attachment".
 *
 * @property int $id
 * @property int $bichuv_acs_id
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property string $type
 * @property string $path
 * @property int $isMain
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvAcs $bichuvAcs
 */
class BichuvAcsAttachment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_acs_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_acs_id', 'size', 'isMain', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 10],
            [['type'], 'string', 'max' => 120],
            [['bichuv_acs_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvAcs::className(), 'targetAttribute' => ['bichuv_acs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bichuv_acs_id' => Yii::t('app', 'Bichuv Acs ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'extension' => Yii::t('app', 'Extension'),
            'type' => Yii::t('app', 'Type'),
            'path' => Yii::t('app', 'Path'),
            'isMain' => Yii::t('app', 'Is Main'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvAcs()
    {
        return $this->hasOne(BichuvAcs::className(), ['id' => 'bichuv_acs_id']);
    }
    public function deleteOne(){
        $path = str_replace('/web/', '', $this->path);
        if (file_exists($path)){
            unlink($path);
        }
        if($this->delete()){
            return true;
        }
        return false;
    }
}
