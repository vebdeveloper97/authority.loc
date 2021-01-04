<?php

namespace app\modules\base\models;

use Yii;
use app\models\PulBirligi;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "wh_document_items".
 *
 * @property int $id
 * @property int $wh_document_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $lot
 * @property string $document_qty
 * @property string $quantity
 * @property int $dep_section
 * @property int $dep_area
 * @property string $incoming_price
 * @property int $incoming_pb_id
 * @property string $wh_price
 * @property int $wh_pb_id
 * @property int $package_type
 * @property string $package_qty
 * @property string $add_info
 * @property int $status
 * @property int $wh_item_balance_id
 * @property string $sell_price
 * @property int $sell_pb_id
 *
 * @property WhDepartmentArea $depArea
 * @property WhDepartmentArea $depSection
 * @property PulBirligi $incomingPb
 * @property PulBirligi $sellPb
 * @property WhDocument $whDocument
 * @property WhItemBalance $whItemBalance
 * @property PulBirligi $whPb
 */
class WhDocumentItems extends \yii\db\ActiveRecord
{
    public $remain;
    public $package_remain;
    public $mixing_unit_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_document_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wh_document_id', 'entity_id', 'entity_type', 'dep_section', 'dep_area', 'incoming_pb_id', 'wh_pb_id', 'package_type', 'wh_item_balance_id','status'], 'integer'],
            [['document_qty', 'wh_price', 'package_qty'], 'number'],
            [['quantity'], 'number', 'min' => 0.001],
            [['add_info'], 'string'],
            [['lot'], 'string', 'max' => 50],
            [['quantity'], 'required'],
            [['entity_id'], 'required', 'when' => function ($model) {
                return $model->whDocument->document_type != WhDocument::DOC_TYPE_MIXING;
            }],
            //[ 'price_usd', 'validatePrice', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['incoming_price', 'incoming_pb_id'], 'required', 'when' => function ($model) {
                return $model->whDocument->document_type == WhDocument::DOC_TYPE_INCOMING
                    && $model->whDocument->action == 1;
            }],
            [['incoming_price'], 'number', 'min' => 0.001, 'when' => function ($model) {
                return $model->incoming_price < 0.001 && $model->whDocument->document_type == WhDocument::DOC_TYPE_INCOMING;
            }],
            [['dep_area'], 'exist', 'skipOnError' => true, 'targetClass' => WhDepartmentArea::className(), 'targetAttribute' => ['dep_area' => 'id']],
            [['dep_section'], 'exist', 'skipOnError' => true, 'targetClass' => WhDepartmentArea::className(), 'targetAttribute' => ['dep_section' => 'id']],
            [['incoming_pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['incoming_pb_id' => 'id']],
            [['sell_pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['sell_pb_id' => 'id']],
            [['wh_document_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhDocument::className(), 'targetAttribute' => ['wh_document_id' => 'id']],
            [['wh_item_balance_id'], 'exist', 'skipOnError' => true, 'targetClass' => WhItemBalance::className(), 'targetAttribute' => ['wh_item_balance_id' => 'id']],
            [['wh_pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['wh_pb_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'wh_document_id' => Yii::t('app', 'Wh Document ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'lot' => Yii::t('app', 'Lot'),
            'document_qty' => Yii::t('app', 'Document Qty'),
            'quantity' => Yii::t('app', 'Quantity'),
            'dep_section' => Yii::t('app', 'Dep Section'),
            'dep_area' => Yii::t('app', 'Dep Area'),
            'incoming_price' => Yii::t('app', 'Incoming Price'),
            'incoming_pb_id' => Yii::t('app', 'Incoming Pb ID'),
            'wh_price' => Yii::t('app', 'Wh Price'),
            'wh_pb_id' => Yii::t('app', 'Wh Pb ID'),
            'package_type' => Yii::t('app', 'Package Type'),
            'package_qty' => Yii::t('app', 'Package Qty'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'wh_item_balance_id' => Yii::t('app', 'Wh Item Balance ID'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'sell_pb_id' => Yii::t('app', 'Sell Pb ID'),
        ];
    }

    /**
     *
     */
    public function afterValidate()
    {
        if ($this->hasErrors()) {
            $res = [
                'status' => 'error',
                'table' => self::tableName() ?? '',
                'url' => \yii\helpers\Url::current([], true),
                'message' => $this->getErrors(),
            ];
            Yii::error($res, 'save');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItem()
    {
        return $this->hasOne(WhItems::className(), ['id' => 'entity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepArea()
    {
        return $this->hasOne(WhDepartmentArea::className(), ['id' => 'dep_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepSection()
    {
        return $this->hasOne(WhDepartmentArea::className(), ['id' => 'dep_section']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncomingPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'incoming_pb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'sell_pb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhDocument()
    {
        return $this->hasOne(WhDocument::className(), ['id' => 'wh_document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItemBalance()
    {
        return $this->hasOne(WhItemBalance::className(), ['id' => 'wh_item_balance_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'wh_pb_id']);
    }

    /**
     * @return string
     */
    public static function getModelName()
    {
        return StringHelper::basename(get_class(new self()));
    }

    /**
     * @param null $id
     * @return array|string|null
     */
    public function getPulBirligi($id = null)
    {
        if (!empty($key)) {
            $unit = PulBirligi::findOne($id);
            if ($unit !== null) {
                return $unit->name;
            }
        } else {
            $units = PulBirligi::find()->asArray()->all();
            if (!empty($units)) {
                return ArrayHelper::map($units, 'id', 'name');
            }
            return null;
        }

    }

    /**
     * @param int $type
     * @return float|int
     */
    public function getSumIncomePrice()
    {

        $this->incoming_price = $this->incoming_price ? $this->incoming_price : 0;
        return $this->incoming_price * $this->quantity;
    }

    /**
     * @param $provider
     * @param array $fields
     * @param bool $calcSeparately
     * @return int
     */
    public static function getTotal($provider, $fields = [], $calcSeparately = false)
    {
        $total = 0;
        if (!empty($fields)) {
            foreach ($fields as $fieldName) {
                foreach ($provider as $item) {
                    $total += $item[$fieldName];
                }
            }
        }
        return $total;
    }

    /**
     * @param $provider
     * @param array $fields
     * @param bool $calcSeparately
     * @return int
     */
    public static function getTotalPrice($provider, $fields = [], $calcSeparately = false)
    {
        $total = 0;

        if ($calcSeparately) {
            foreach ($provider as $item) {
                $priceSUM = $item[$fields[0]];
                $qty = $item[$fields[1]];
                if (!empty($priceSUM) && $priceSUM > 0) {
                    $total += $priceSUM * $qty;
                }
            }
        }

        return $total;
    }


    /**
     * @param array $summ
     * @return string
     */
    public static function getSummPrice($summ = [])
    {
        $return = "";
        if (!empty($summ)) {
            foreach ($summ as $k => $v) {
                $return .= $v . " " . $k;
            }
        }
        return $return;
    }

    /**
     * @param $provider
     * @param array $fields
     * @return float|int
     */
    public static function getTotalSum($provider, $fields = [])
    {
        $total = 0;
        foreach ($provider as $item) {
            $priceSUM = $item[$fields[0]];
            if (!empty($priceSUM) && $priceSUM > 0) {
                $total += $priceSUM * $item[$fields[1]];
            }

        }
        return $total;
    }
}
