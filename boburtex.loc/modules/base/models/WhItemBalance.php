<?php

namespace app\modules\base\models;

use Yii;
use app\modules\toquv\models\ToquvDepartments;
use app\models\PulBirligi;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $lot
 * @property string $quantity
 * @property string $inventory
 * @property string $reg_date
 * @property int $department_id
 * @property int $dep_section
 * @property int $dep_area
 * @property int $wh_document_id
 * @property string $incoming_price
 * @property int $incoming_pb_id
 * @property string $wh_price
 * @property int $wh_pb_id
 * @property int $package_type
 * @property string $package_qty
 * @property string $package_inventory
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property string $sell_price
 * @property int $sell_pb_id
 *
 * @property WhDepartmentArea $depArea
 * @property WhDepartmentArea $depSection
 * @property ToquvDepartments $department
 * @property PulBirligi $incomingPb
 * @property WhDocument $whDocument
 * @property PulBirligi $whPb
 * @property PulBirligi $sellPb
 */
class WhItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_item_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'department_id',
                'dep_section', 'dep_area', 'wh_document_id',
                'incoming_pb_id', 'wh_pb_id', 'sell_pb_id',
                'package_type', 'status', 'created_by',
                'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['quantity', 'inventory', 'incoming_price',
                'wh_price', 'sell_price', 'package_qty',
                'package_inventory'], 'number'],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['lot'], 'string', 'max' => 50],
            [['dep_area'], 'exist', 'skipOnError' => true,
                'targetClass' => WhDepartmentArea::className(),
                'targetAttribute' => ['dep_area' => 'id']],
            [['dep_section'], 'exist', 'skipOnError' => true,
                'targetClass' => WhDepartmentArea::className(),
                'targetAttribute' => ['dep_section' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true,
                'targetClass' => ToquvDepartments::className(),
                'targetAttribute' => ['department_id' => 'id']],
            [['incoming_pb_id'], 'exist', 'skipOnError' => true,
                'targetClass' => PulBirligi::className(),
                'targetAttribute' => ['incoming_pb_id' => 'id']],
            [['wh_document_id'], 'exist', 'skipOnError' => true,
                'targetClass' => WhDocument::className(),
                'targetAttribute' => ['wh_document_id' => 'id']],
            [['sell_pb_id'], 'exist', 'skipOnError' => true,
                'targetClass' => PulBirligi::className(),
                'targetAttribute' => ['sell_pb_id' => 'id']],
            [['wh_pb_id'], 'exist', 'skipOnError' => true,
                'targetClass' => PulBirligi::className(),
                'targetAttribute' => ['wh_pb_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'lot' => Yii::t('app', 'Lot'),
            'quantity' => Yii::t('app', 'Quantity'),
            'inventory' => Yii::t('app', 'Inventory'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'department_id' => Yii::t('app', 'Department ID'),
            'dep_section' => Yii::t('app', 'Dep Section'),
            'dep_area' => Yii::t('app', 'Dep Area'),
            'wh_document_id' => Yii::t('app', 'Wh Document ID'),
            'incoming_price' => Yii::t('app', 'Incoming Price'),
            'incoming_pb_id' => Yii::t('app', 'Incoming Pb ID'),
            'wh_price' => Yii::t('app', 'Wh Price'),
            'wh_pb_id' => Yii::t('app', 'Wh Pb ID'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'sell_pb_id' => Yii::t('app', 'Sell Pb ID'),
            'package_type' => Yii::t('app', 'Package Type'),
            'package_qty' => Yii::t('app', 'Package Qty'),
            'package_inventory' => Yii::t('app', 'Package Inventory'),
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
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
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
    public function getWhDocument()
    {
        return $this->hasOne(WhDocument::className(), ['id' => 'wh_document_id']);
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
    public function getWhPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'wh_pb_id']);
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public static function getLastRecord($data = []){
        if(!empty($data)){
            $lastEntity = self::find()->where(
                [
                    'entity_id' => $data['entity_id'],
                    'entity_type' => $data['entity_type'],
                    'lot' => $data['lot'],
                    'department_id' => $data['department_id'],
                    'dep_area' => $data['dep_area'],
                    'dep_section' => $data['dep_section'],
                    'wh_price' => $data['wh_price'],
                    'wh_pb_id' => $data['wh_pb_id'],
                    'package_type' => $data['package_type'],
                ])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();
            //return $lastEntity;
            if(!empty($lastEntity)){
                return ['inventory' => $lastEntity['inventory'],
                        'package_inventory' => $lastEntity['package_inventory']];
            }else{
                return $return = [
                    'inventory' => 0,
                    'package_inventory' => 0,
                ];
            }
        }
        return 0;
    }

    public function searchEntities($params){

        $q = $params['query'] ?
                " AND ((wi.name LIKE '%{$params['query']}%') ".
                "OR (wic.name LIKE '%{$params['query']}%') ".
                "OR (c.name LIKE '%{$params['query']}%'))"
            : "";


        $sql = "SELECT wib.id,
                    wib.entity_id,
                    wi.name, 
                    wic.name as category, 
                    wit.name as type, u.name as unit, c.name as country, 
                    wib.lot, 
                    wib.inventory,
                    wib.package_inventory,
                    wib.package_type,
                    wib.wh_price, 
                    pb.name as currency
                FROM wh_item_balance wib 
                left join wh_items wi on wib.entity_id = wi.id
                left join wh_item_category wic on wi.category_id = wic.id
                left join wh_item_types wit on wi.type_id = wit.id
                left join wh_item_country c on wi.country_id = c.id
                left join unit u on wi.unit_id = u.id
                left join pul_birligi pb on wib.wh_pb_id = pb.id
                JOIN (SELECT MAX(id) as id, 
                             entity_id 
                        from wh_item_balance 
                        where department_id = %d
                        GROUP BY entity_id, entity_type, lot, dep_area, 
                                 wh_price, wh_pb_id, package_type 
                        ORDER BY id ASC) as wib2 ON wib.id = wib2.id
                WHERE (entity_type=%d) AND (department_id=%d) AND (wib.inventory > 0) %s
                GROUP BY wib.entity_id, wib.entity_type, wib.lot, wib.dep_area, 
                         wib.wh_price, wib.wh_pb_id, wib.package_type LIMIT 50000";

        $sql = sprintf($sql,
            $params['department_id'],
            $params['entity_type'],
            $params['department_id'],
            $q);

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function getDeptList($isAll = false){
        $doc = new WhDocument();
        return $doc->getDepartments($isAll);
    }

    /**
     * @return WhItems[]
     */

    public function getEntities($id = false)
    {
        $model = WhItems::findAll(['status'=>WhItems::STATUS_ACTIVE]);
        $response = [];

        foreach ($model as $item) {
            $response[$item->id] = $item->name . " " .
                $item->type->name . " " .
                $item->category->name. " " .
                $item->country->name. " (" .
                $item->unit->name . ")";
        }
        return $response;
    }
}
