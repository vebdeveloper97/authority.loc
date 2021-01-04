<?php

namespace app\modules\tikuv\models;

use app\modules\wms\models\WmsDepartmentArea;
use Yii;
use app\models\Users;
use app\modules\tikuv\models\TikuvGoodsDoc;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use app\modules\wms\models\WmsDocumentItems;
/**
 * This is the model class for table "tikuv_goods_doc_accepted_by_tmo".
 *
 * @property int $id
 * @property int $tgdp_id
 * @property int $tgd_id
 * @property int $wdi_id
 * @property string $tmo_doc_number
 * @property int $by_employee
 * @property string $reg_date
 * @property string $quantity
 * @property int $type
 * @property string $add_info
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Users $byEmployee
 * @property TikuvGoodsDoc $tgd
 * @property TikuvGoodsDocPack $tgdp
 * @property WmsDocumentItems $wdi
 */
class TikuvGoodsDocAcceptedByTmo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_goods_doc_accepted_by_tmo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tgdp_id', 'tgd_id', 'wdi_id', 'by_employee', 'type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['reg_date'], 'safe'],
            [['quantity'], 'number'],
            [['add_info'], 'string'],
            [['tmo_doc_number'], 'string', 'max' => 255],
            [['by_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['by_employee' => 'id']],
            [['tgd_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvGoodsDoc::className(), 'targetAttribute' => ['tgd_id' => 'id']],
            [['tgdp_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvGoodsDocPack::className(), 'targetAttribute' => ['tgdp_id' => 'id']],
            [['wdi_id'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDocumentItems::className(), 'targetAttribute' => ['wdi_id' => 'id']],
            [['dep_area'], 'exist', 'skipOnError' => true, 'targetClass' => WmsDepartmentArea::className(), 'targetAttribute' => ['dep_area' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
       $this->status = self::STATUS_ACTIVE;
       return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tgdp_id' => Yii::t('app', 'Tgdp ID'),
            'tgd_id' => Yii::t('app', 'Tgd ID'),
            'wdi_id' => Yii::t('app', 'Wdi ID'),
            'tmo_doc_number' => Yii::t('app', 'Tmo Doc Number'),
            'by_employee' => Yii::t('app', 'By Employee'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'quantity' => Yii::t('app', 'Quantity'),
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
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
    public function getByEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'by_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTgd()
    {
        return $this->hasOne(TikuvGoodsDoc::className(), ['id' => 'tgd_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTgdp()
    {
        return $this->hasOne(TikuvGoodsDocPack::className(), ['id' => 'tgdp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWdi()
    {
        return $this->hasOne(WmsDocumentItems::className(), ['id' => 'wdi_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWmsDepArea()
    {
        return $this->hasOne(WmsDepartmentArea::className(), ['id' => 'dep_area']);
    }
}
