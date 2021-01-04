<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "base_detail_lists".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $code
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BasePatternItems[] $basePatternItems
 */
class BaseDetailLists extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_detail_lists';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternItems()
    {
        return $this->hasMany(BasePatternItems::className(), ['base_detail_list_id' => 'id']);
    }

    /**
     * @param bool $keyVal
     * @param bool $single
     * @return BaseDetailLists[]|array|mixed|\yii\db\ActiveRecord[]|null
     */
    public function getParentList($keyVal = false, $single = false){
        $id = $this->id;
        $dl = BaseDetailLists::find();
        if($single){
            $res = $dl->where(['id' => $this->parent_id])->asArray()->one();
            if(!empty($res)){
                return $res['name'];
            }
            return null;
        }else{
            if(!empty($id)){
                $dl->where(['<>','id',$id]);
            }
        }
        $out = $dl->asArray()->orderBy(['name' => SORT_ASC])->all();
        if($keyVal){
            return ArrayHelper::map($out,'id','name');
        }
        return $out;
    }

    /** Barcha Malumotlarini olish */
    public static function getArrayList(){
        return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE])->all(), 'id', 'name');
    }
}
