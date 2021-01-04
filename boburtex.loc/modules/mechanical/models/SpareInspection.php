<?php

namespace app\modules\mechanical\models;

use app\modules\bichuv\models\SpareItem;
use Yii;

/**
 * This is the model class for table "spare_inspection".
 *
 * @property int $id
 * @property int $spare_passport_item_id
 * @property int $control_type
 * @property string $reg_date
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property SparePassportItems $sparePassportItem
 */
class SpareInspection extends BaseModel
{

    /** Control type**/
    const CONTROL_TYPE_UNEXPECTED = 1;
    const CONTROL_TYPE_EXPECTED = 2;

    /** Scenario list**/
    const SCENARIO_UNEXPEXTED = "scenario-unexpected";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spare_inspection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['spare_passport_item_id', 'control_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['spare_passport_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SparePassportItems::class, 'targetAttribute' => ['spare_passport_item_id' => 'id']],
            [['sirhe_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpareItemRelHrEmployee::class, 'targetAttribute' => ['sirhe_id' => 'id']],
            [['sirhe_id'],'required', 'on' => self::SCENARIO_UNEXPEXTED]
        ];
    }

    public function afterFind()
    {
        if (!empty($this->reg_date)) {
            $this->reg_date = date('d.m.yy', strtotime($this->reg_date));
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->reg_date)) {
            $this->reg_date = date('Y-m-d H:i:s', strtotime($this->reg_date));
        }
        return true;
    }
    /**
     * @param string $slug
     * @return bool
     * shunday slug tokeni bor yo'qligini tekshiradi
     */
    public static function hasControlTypeLabel(string $slug): bool
    {
        return in_array($slug, self::getControlTypeTokens());
    }

    /**
     * @return array
     * Nazorat turlari tokeni
     */
    public static function getControlTypeTokens(): array
    {
        return [
            self::CONTROL_TYPE_UNEXPECTED => "unexpected",
            self::CONTROL_TYPE_EXPECTED => "expected",
        ];
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public static function getControlTypeBySlug(string $slug)
    {
        return array_search($slug, self::getControlTypeTokens());
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'spare_passport_item_id' => Yii::t('app', 'Spare Passport Item ID'),
            'sirhe_id' => Yii::t('app', 'Spare Item Employee'),
            'control_type' => Yii::t('app', 'Control Type'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @return \yii\db\ActiveQuery
     */
    public function getSparePassportItem()
    {
        return $this->hasOne(SparePassportItems::class, ['id' => 'spare_passport_item_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSirhe()
    {
        return $this->hasOne(SpareItemRelHrEmployee::class, ['id' => 'sirhe_id']);
    }

    public function getSpareInspectionItems()
    {
        return $this->hasMany(SpareInspectionItems::class, ['spare_inspection_id' => 'id']);
    }
}
