<?php

namespace app\modules\toquv\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_pus_fine".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 */
class ToquvPusFine extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_pus_fine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name','unique'],
            [['status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'ne_name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public static function getNameById($id)  {

        $model = self::find()->where(['id' => $id])->one();
        return $model->name;
    }

    public static function getList()
    {
        $list = static::find()->where(['status'=>self::STATUS_ACTIVE])->asArray()->all();
        return ArrayHelper::map($list,'id', 'name');
    }

    public function getToquvMakine()
    {
        return $this->hasMany(ToquvMakine::className(), ['pus_fine_id' => 'id']);
    }

    public static function getPusFineList($type=null,$map=false,$is_not=null)
    {
        $list = ToquvPusFine::find()->joinWith('toquvMakine');
        if($type){
            $list = $list->where(['toquv_makine.type'=>$type]);
        }
        if($is_not){
            $list = $list->where(['!=','toquv_makine.type',$is_not])->orWhere(['IS','toquv_makine.type', new Expression('NULL')]);
        }
        $list = $list->asArray()->all();
        if($map){
            return ArrayHelper::map($list,'id','name');
        }
        return $list;
    }
}
