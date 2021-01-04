<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_item_category".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $type_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property WhItemTypes $type
 * @property WhItems[] $whItems
 */
class WhItemCategory extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_item_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code','name','type_id'],'required'],
            ['code','unique'],
            [['type_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhItemTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'type_id' => Yii::t('app', 'Type ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(WhItemTypes::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItems()
    {
        return $this->hasMany(WhItems::className(), ['category_id' => 'id']);
    }

    public static function getList($id=null,$array=false)
    {
        $list = self::find();
        if($id){
            $list = $list->where(['type_id'=>$id]);
        }
        $list = $list->asArray()->all();
        if($array){
            $res = [];
            if(!empty($list)){
                foreach ($list as $item) {
                    $res[] = [
                        'id' => $item['id'],
                        'name' => $item['name']
                    ];
                }
            }
            return $res;
        }
        return ArrayHelper::map($list,'id','name');
    }
}
