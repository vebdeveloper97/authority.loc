<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\Size;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_nastel_detail_items".
 *
 * @property int $id
 * @property int $size_id
 * @property int $bichuv_nastel_detail_id
 * @property int $count
 * @property int $required_count
 * @property string $weight
 * @property string $required_weight
 * @property int $type
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $bichuv_processes_id
 * @property int $order
 * @property int $brak
 * @property int $bichuv_nastel_processes_id
 *
 * @property BichuvNastelDetails $bichuvNastelDetail
 * @property BichuvNastelProcesses $bichuvNastelProcesses
 * @property BichuvProcesses $bichuvProcesses
 * @property Size $size
 * @property int $bichuv_given_roll_items_id [int(11)]
 * @property BichuvGivenRollItems $bichuvGivenRollItems
 * @property BichuvNastelProcessBrak[] $bichuvNastelProcessBraks
 */
class BichuvNastelDetailItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_nastel_detail_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_id', 'bichuv_nastel_detail_id', 'count', 'required_count', 'type', 'status', 'created_by', 'created_at', 'updated_at', 'bichuv_given_roll_items_id', 'bichuv_processes_id', 'order', 'brak', 'bichuv_nastel_processes_id'], 'integer'],
            [['weight', 'required_weight'], 'number'],
            [['bichuv_nastel_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelDetails::className(), 'targetAttribute' => ['bichuv_nastel_detail_id' => 'id']],
            [['bichuv_given_roll_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRollItems::className(), 'targetAttribute' => ['bichuv_given_roll_items_id' => 'id']],
            [['bichuv_nastel_processes_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvNastelProcesses::className(), 'targetAttribute' => ['bichuv_nastel_processes_id' => 'id']],
            [['bichuv_processes_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvProcesses::className(), 'targetAttribute' => ['bichuv_processes_id' => 'id']],
            [['size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Size::className(), 'targetAttribute' => ['size_id' => 'id']],
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
            'bichuv_nastel_detail_id' => Yii::t('app', 'Bichuv Nastel Detail ID'),
            'count' => Yii::t('app', 'Count'),
            'required_count' => Yii::t('app', 'Required Count'),
            'weight' => Yii::t('app', 'Weight'),
            'required_weight' => Yii::t('app', 'Required Weight'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bichuv_given_roll_items_id' => Yii::t('app', 'Bichuv Given Roll Items ID'),
            'bichuv_processes_id' => Yii::t('app', 'Bichuv Processes ID'),
            'order' => Yii::t('app', 'Order'),
            'brak' => Yii::t('app', 'Brak'),
            'bichuv_nastel_processes_id' => Yii::t('app', 'Bichuv Nastel Processes ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetail()
    {
        return $this->hasOne(BichuvNastelDetails::className(), ['id' => 'bichuv_nastel_detail_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcesses()
    {
        return $this->hasOne(BichuvNastelProcesses::className(), ['id' => 'bichuv_nastel_processes_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItems()
    {
        return $this->hasOne(BichuvGivenRollItems::className(), ['id' => 'bichuv_given_roll_items_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcesses()
    {
        return $this->hasOne(BichuvProcesses::className(), ['id' => 'bichuv_processes_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    public function getSizeList()
    {
        $list = Size::find()->asArray()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($list,'id','name');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcessBraks()
    {
        return $this->hasMany(BichuvNastelProcessBrak::className(), ['bichuv_nastel_detail_items_id' => 'id']);
    }
}
