<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "toquv_item_balance".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $count
 * @property string $inventory
 * @property string $reg_date
 * @property int $department_id
 * @property int $is_own
 * @property string $price_uzs
 * @property string $price_usd
 * @property string $sold_price_uzs
 * @property string $sold_price_usd
 * @property string $sum_uzs
 * @property string $sum_usd
 * @property int $document_id
 * @property int $document_type
 * @property int $version
 * @property string $comment
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $price_rub
 * @property string $sold_price_rub
 * @property string $price_eur
 * @property string $sold_price_eur
 * @property string $lot
 * @property int $to_department
 * @property int $from_department
 * @property int $from_musteri
 * @property int $to_musteri
 * @property int $musteri_id
 * @property string $roll_inventory [decimal(20,3)]
 * @property string $roll_count [decimal(20,3)]
 * @property double $quantity
 * @property double $quantity_inventory
 *
 * @property ToquvDepartments $department
 * @property ToquvDepartments $fromDepartment
 * @property ToquvDepartments $toDepartment
 * @property ToquvMusteri $musteri
 */
class ToquvItemBalance extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_item_balance}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'musteri_id', 'document_type', 'entity_type', 'department_id', 'to_department', 'from_department', 'from_musteri', 'to_musteri', 'is_own', 'document_id', 'version', 'created_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['count', 'inventory','roll_count','roll_inventory','price_uzs', 'price_usd', 'sold_price_uzs', 'sold_price_usd', 'sum_uzs', 'sum_usd', 'price_rub', 'sold_price_rub', 'price_eur', 'sold_price_eur', 'roll_inventory', 'roll_count', 'quantity', 'quantity_inventory'], 'number'],
            [['reg_date'], 'safe'],
            [['comment', 'lot'], 'string'],
            [['lot'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
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
            'count' => Yii::t('app', 'Count'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'roll_inventory' => Yii::t('app', 'Roll Inventory'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'department_id' => Yii::t('app', 'Department ID'),
            'is_own' => Yii::t('app', "Maxsulot bizniki/Mijozniki"),
            'lot' => Yii::t('app', 'Lot'),
            'price_uzs' => Yii::t('app', 'Price Uzs'),
            'price_usd' => Yii::t('app', 'Price Usd'),
            'sold_price_uzs' => Yii::t('app', 'Sold Price Uzs'),
            'sold_price_usd' => Yii::t('app', 'Sold Price Usd'),
            'sum_uzs' => Yii::t('app', 'Sum Uzs'),
            'sum_usd' => Yii::t('app', 'Sum Usd'),
            'document_id' => Yii::t('app', 'Document ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'version' => Yii::t('app', 'Version'),
            'comment' => Yii::t('app', 'Comment'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'price_rub' => Yii::t('app', 'Price Rub'),
            'sold_price_rub' => Yii::t('app', 'Sold Price Rub'),
            'price_eur' => Yii::t('app', 'Price Eur'),
            'musteri_id' => Yii::t('app', 'Kontragent'),
            'sold_price_eur' => Yii::t('app', 'Sold Price Eur'),
            'to_department' => Yii::t('app', 'To Department'),
            'from_department' => Yii::t('app', 'From Department'),
            'from_musteri' => Yii::t('app', 'From Musteri'),
            'to_musteri' => Yii::t('app', 'To Musteri'),
            'quantity' => Yii::t('app', 'Quantity'),
            'quantity_inventory' => Yii::t('app', 'Quantity Inventory'),
        ];
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
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(ToquvMusteri::className(), ['id' => 'musteri_id']);
    }

    public function afterFind()
    {
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));
        parent::afterFind();
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public static function getLastRecord($data = [])
    {
        if (!empty($data)) {
            $lastEntity = self::find()->where(['is_own' => $data['is_own'], 'entity_id' => $data['entity_id'], 'entity_type' => $data['entity_type'], 'lot' => $data['lot'], 'department_id' => $data['department_id']])->orderBy(['id' => SORT_DESC])->one();
            if (!empty($lastEntity)) {
                return $lastEntity['inventory'] + $data['quantity'];
            } else {
                return $data['quantity'];
            }
        }
        return 0;
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public static function getLastRecordMato($data = [])
    {
        if (!empty($data)) {
            $lastEntity = self::find()->where(['entity_id' => $data['entity_id'], 'entity_type' => $data['entity_type'], 'department_id' => $data['department_id']])->orderBy(['id' => SORT_DESC])->one();
            if (!empty($lastEntity)) {
                return $lastEntity['inventory'] + $data['quantity'];
            } else {
                return $data['quantity'];
            }
        }
        return 0;
    }

    public static function getLastRecordWithMusteri($data = [])
    {
        if (!empty($data)) {
            $lastEntity = self::find()->where([
                'entity_id' => $data['entity_id'],
                'entity_type' => $data['entity_type'],
                'lot' => $data['lot'],
                'department_id' => $data['department_id'],
                'is_own' => $data['is_own'],
                'musteri_id' => $data['musteri_id']
            ])->orderBy(['id' => SORT_DESC])->one();
            if (!empty($lastEntity)) {
                return $lastEntity['inventory'] + $data['quantity'];
            } else {
                return $data['quantity'];
            }
        }
        return 0;
    }

    /**
     * @param array $data
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getLastRecordMovingMusteri($data = [])
    {
        if (!empty($data)) {
            $lastEntity = self::find()->where([
                'entity_id' => $data['entity_id'],
                'entity_type' => $data['entity_type'],
                'lot' => $data['lot'],
                'department_id' => $data['department_id'],
                'is_own' => $data['is_own'],
                'musteri_id' => $data['musteri_id']
            ])->orderBy(['id' => SORT_DESC])->one();
            if (!empty($lastEntity)) {
                return $lastEntity;
            }
        }
        return [];
    }

    /**
     * @param array $data
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getLastRecordMoving($data = [])
    {
        if (!empty($data)) {
            $lastEntity = self::find()->where([
                'is_own' => $data['is_own'],
                'entity_id' => $data['entity_id'],
                'entity_type' => $data['entity_type'],
                'lot' => $data['lot'],
                'department_id' => $data['department_id']
            ])->orderBy(['id' => SORT_DESC])->one();
            if (!empty($lastEntity)) {
                return $lastEntity;
            }
        }
        return [];
    }

    public static function getLastRecordMovingMato($data = [], $type = 'from')
    {
        if (!empty($data)) {
            if($type == 'from'){
                $sql = "select  tib.inventory,
                            tib.roll_inventory,
                            tib.quantity_inventory,
                            tib.price_usd, 
                            tib.price_uzs
                    from toquv_item_balance tib
                    left join toquv_instruction_rm tir on tib.entity_id = tir.id
                    left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                    left join toquv_orders tor on tro.toquv_orders_id = tor.id
                    left join musteri m on m.id = tor.musteri_id
                    WHERE tib.department_id = :dept 
                      AND tib.entity_type = :etype 
                      AND tib.entity_id = :eid 
                      AND tib.lot = :lot 
                      AND m.id = :mid ORDER BY tib.id DESC LIMIT 1;";
            }else{
                $sql = "select  tib.inventory,
                            tib.roll_inventory,
                            tib.quantity_inventory,
                            tib.price_usd, 
                            tib.price_uzs
                    from toquv_item_balance tib
                    left join toquv_instruction_rm tir on tib.entity_id = tir.id
                    left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                    left join toquv_orders tor on tro.toquv_orders_id = tor.id
                    left join musteri m on m.id = tor.musteri_id
                    WHERE tib.department_id = :dept 
                      AND tib.entity_type = :etype 
                      AND tib.entity_id = :eid 
                      AND tib.lot = :lot 
                      AND m.id = :mid ORDER BY tib.id DESC LIMIT 1;";
            }
            $result = Yii::$app->db->createCommand($sql)->bindValues([
                'dept' => $data['department_id'],
                'etype' => $data['entity_type'],
                'eid' => $data['entity_id'],
                'lot' => $data['lot'],
                'mid' => $data['musteri_id']
            ])->queryOne();

            if (!empty($result)) {
                return $result;
            }
        }
        return [];
    }

    /**
     * @param $id
     * @return |null
     * @throws \yii\db\Exception
     */
    public static function getMusteriId($id){
        $sql = "select m.id from toquv_instruction_rm tir
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders t on tro.toquv_orders_id = t.id
                left join musteri m on t.musteri_id = m.id
                where tir.id = :id LIMIT 1;";
        $result = Yii::$app->db->createCommand($sql)->bindValue('id', $id)->queryOne();
        if(!empty($result)){
            return $result['id'];
        }
        return null;
    }

    /**
     * @param array $data
     * @param $VIRTUAL_SKLAD
     * @param $to_dept
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getLastRecordVS($data = [], $VIRTUAL_SKLAD, $doc)
    {
        if (!empty($data)) {
            $sql = "SELECT inventory FROM toquv_item_balance WHERE entity_id = %d AND entity_type = %d AND lot = %s AND department_id = %d AND document_id = %d ORDER BY id DESC LIMIT 1";
            $sql = sprintf($sql, $data['entity_id'], $data['entity_type'], $data['lot'], $VIRTUAL_SKLAD, $doc);
            $res = Yii::$app->db->createCommand($sql)->queryOne();
            return $res;
        }
        return [];
    }

    /**
     * @param int $type
     * @param null $entityId
     * @return array|null
     */
    public function getEntities($type = 1, $entityId = null)
    {
        switch ($type) {
            case ToquvDocuments::DOC_TYPE_INCOMING:
                $modelTDI = new ToquvDocumentItems();
                return $modelTDI->getIplar($entityId);
                break;
            case ToquvDocuments::DOC_TYPE_MOVING:
                break;
        }
        return null;
    }

    /**
     * @return string
     */
    public static function getModelName()
    {
        return StringHelper::basename(get_class(new self()));
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getOwnTypes($key = null)
    {
        $res = [
            1 => Yii::t('app', "O'zimizni tovar"),
            2 => Yii::t('app', "O'zimizniki bo'lmagan tovar")
        ];
        if (!empty($key)) {
            return $res[$key];
        }
        return $res;
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    public static function setCloned($item){
        $clone = new ToquvItemBalanceArxiv();
        $clone->attributes = $item->attributes;
        $clone->parent_id = $item->id;
        $clone->save();
    }
}
