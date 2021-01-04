<?php

namespace app\modules\bichuv\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_detail_types".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $type_order
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $token
 * @property int $bichuv_process_id
 * @property string $slug
 *
 * @property BichuvProcesses $bichuvProcess
 * @property BichuvGivenRollItems[] $bichuvGivenRollItems
 * @property BichuvGivenRolls[] $bichuvGivenRolls
 * @property BichuvNastelDetails[] $bichuvNastelDetails
 * @property BichuvNastelProcesses[] $bichuvNastelProcesses
 */
class BichuvDetailTypes extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_detail_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'type_order', 'status', 'created_by', 'created_at', 'updated_at', 'bichuv_process_id'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [ 'slug', 'unique'],
            [['token'], 'string', 'max' => 50],
            [['bichuv_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvProcesses::className(), 'targetAttribute' => ['bichuv_process_id' => 'id']],
        ];
    }
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => SluggableBehavior::className(),
                    'attribute' => 'name',
                    'ensureUnique' => true
                ]
            ]
        );
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'type_order' => Yii::t('app', 'Type Order'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'token' => Yii::t('app', 'Token'),
            'bichuv_process_id' => Yii::t('app', 'Bichuv Process ID'),
            'slug' => Yii::t('app', 'Slug'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcess()
    {
        return $this->hasOne(BichuvProcesses::className(), ['id' => 'bichuv_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItems()
    {
        return $this->hasMany(BichuvGivenRollItems::className(), ['bichuv_detail_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRolls()
    {
        return $this->hasMany(BichuvGivenRolls::className(), ['bichuv_detail_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetails()
    {
        return $this->hasMany(BichuvNastelDetails::className(), ['detail_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcesses()
    {
        return $this->hasMany(BichuvNastelProcesses::className(), ['bichuv_detail_type_id' => 'id']);
    }

    public static function getType($token)
    {
        $type = BichuvDetailTypes::findOne(['token' => $token]);
        if($type){
            return $type['id'];
        }
        return false;
    }

    public function getProcessList()
    {
        $list = BichuvProcesses::find()->asArray()->all();
        return ArrayHelper::map($list, 'id', 'name');
    }
}
