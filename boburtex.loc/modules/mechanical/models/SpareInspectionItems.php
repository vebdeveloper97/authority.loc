<?php

namespace app\modules\mechanical\models;

use app\modules\bichuv\models\SpareItem;
use app\modules\bichuv\models\SpareItemDocItemBalance;
use Yii;

/**
 * This is the model class for table "spare_inspection_items".
 *
 * @property int $id
 * @property int $spare_inspection_id
 * @property int $spare_control_list_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SpareControlList $spareControlList
 * @property SpareInspection $spareInspection
 */
class SpareInspectionItems extends BaseModel
{

    /** extiyot qismlari omboridagi qoldiq uchun**/
    public $spare_remain;


    /** scenatio list */
    const SCENARIO_UNEXPECTED = "scenario-unexpected";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_inspection_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spare_inspection_id', 'spare_control_list_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','quantity'], 'integer'],
            [['add_info'], 'string'],
            [['spare_control_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareControlList::class, 'targetAttribute' => ['spare_control_list_id' => 'id']],
            [['spare_inspection_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareInspection::class, 'targetAttribute' => ['spare_inspection_id' => 'id']],
            [['spare_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItem::class, 'targetAttribute' => ['spare_item_id' => 'id']],
            [['spare_item_balance_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemDocItemBalance::class, 'targetAttribute' => ['spare_item_balance_id' => 'id']],
            [['add_info','quantity'],'required', 'on' => self::SCENARIO_UNEXPECTED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'spare_inspection_id' => Yii::t('app', 'Spare Inspection ID'),
            'spare_control_list_id' => Yii::t('app', 'Spare Control List ID'),
            'spare_item_id' => Yii::t('app', 'Spare Item ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'quantity' => Yii::t('app', 'Quantity'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareControlList()
    {
        return $this->hasOne(SpareControlList::class, ['id' => 'spare_control_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareInspection()
    {
        return $this->hasOne(SpareInspection::class, ['id' => 'spare_inspection_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItemBalance()
    {
        return $this->hasOne(SpareItemDocItemBalance::class, ['id' => 'spare_item_balance_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpareItem()
    {
        return $this->hasOne(SpareItem::class, ['id' => 'spare_item_id']);
    }
}
