<?php

namespace app\modules\toquv\models;

use Yii;

/**
 * This is the model class for table "{{%toquv_mato_item_balance}}".
 *
 * @property int $id
 * @property int $tir_id
 * @property int $trm_id
 * @property int $entity_id
 * @property int $entity_type
 * @property string $count
 * @property string $inventory
 * @property string $roll_count
 * @property string $roll_inventory
 * @property string $quantity_count
 * @property string $quantity_inventory
 * @property string $reg_date
 * @property int $department_id
 * @property int $to_department
 * @property int $from_department
 * @property string $lot
 * @property int $musteri_id
 * @property int $to_musteri
 * @property int $from_musteri
 * @property int $is_own
 * @property string $price_uzs
 * @property string $price_usd
 * @property string $sold_price_uzs
 * @property string $sold_price_usd
 * @property string $sum_uzs
 * @property string $sum_usd
 * @property string $price_rub
 * @property string $sold_price_rub
 * @property string $price_eur
 * @property string $sold_price_eur
 * @property int $document_id
 * @property int $document_type
 * @property int $version
 * @property string $comment
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $parent_id
 * @property MatoInfo $tir
 */
class ToquvMatoItemBalance extends \app\modules\toquv\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%toquv_mato_item_balance}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tir_id', 'trm_id', 'entity_id', 'entity_type', 'department_id', 'to_department', 'from_department', 'musteri_id', 'to_musteri', 'from_musteri', 'is_own', 'document_id', 'document_type', 'version', 'created_by', 'status', 'created_at', 'updated_at', 'parent_id'], 'integer'],
            [['count', 'inventory'], 'required'],
            [['count', 'inventory', 'roll_count', 'roll_inventory', 'quantity_count', 'quantity_inventory', 'price_uzs', 'price_usd', 'sold_price_uzs', 'sold_price_usd', 'sum_uzs', 'sum_usd', 'price_rub', 'sold_price_rub', 'price_eur', 'sold_price_eur'], 'number'],
            [['reg_date'], 'safe'],
            [['comment'], 'string'],
            [['lot'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tir_id' => Yii::t('app', 'Tir ID'),
            'trm_id' => Yii::t('app', 'Trm ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'count' => Yii::t('app', 'Count'),
            'inventory' => Yii::t('app', 'Inventory'),
            'roll_count' => Yii::t('app', 'Roll Count'),
            'roll_inventory' => Yii::t('app', 'Roll Inventory'),
            'quantity_count' => Yii::t('app', 'Quantity Count'),
            'quantity_inventory' => Yii::t('app', 'Quantity Inventory'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'department_id' => Yii::t('app', 'Department ID'),
            'to_department' => Yii::t('app', 'To Department'),
            'from_department' => Yii::t('app', 'From Department'),
            'lot' => Yii::t('app', 'Lot'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'to_musteri' => Yii::t('app', 'To Musteri'),
            'from_musteri' => Yii::t('app', 'From Musteri'),
            'is_own' => Yii::t('app', 'Is Own'),
            'price_uzs' => Yii::t('app', 'Price Uzs'),
            'price_usd' => Yii::t('app', 'Price Usd'),
            'sold_price_uzs' => Yii::t('app', 'Sold Price Uzs'),
            'sold_price_usd' => Yii::t('app', 'Sold Price Usd'),
            'sum_uzs' => Yii::t('app', 'Sum Uzs'),
            'sum_usd' => Yii::t('app', 'Sum Usd'),
            'price_rub' => Yii::t('app', 'Price Rub'),
            'sold_price_rub' => Yii::t('app', 'Sold Price Rub'),
            'price_eur' => Yii::t('app', 'Price Eur'),
            'sold_price_eur' => Yii::t('app', 'Sold Price Eur'),
            'document_id' => Yii::t('app', 'Document ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'version' => Yii::t('app', 'Version'),
            'comment' => Yii::t('app', 'Comment'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'parent_id' => Yii::t('app', 'Parent ID'),
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
                    from toquv_mato_item_balance tib
                    left join mato_info tir on tib.entity_id = tir.id
                    left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                    left join toquv_orders tor on tro.toquv_orders_id = tor.id
                    left join musteri m on m.id = tor.musteri_id
                    WHERE tib.department_id = :dept 
                      AND tib.entity_type = :etype 
                      AND tib.entity_id = :eid 
                      AND tib.lot = :lot 
                      AND tir.musteri_id = :mid ORDER BY tib.id DESC LIMIT 1;";
            }else{
                $sql = "select  tib.inventory,
                            tib.roll_inventory,
                            tib.quantity_inventory,
                            tib.price_usd, 
                            tib.price_uzs
                    from toquv_mato_item_balance tib
                    left join mato_info tir on tib.entity_id = tir.id
                    left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                    left join toquv_orders tor on tro.toquv_orders_id = tor.id
                    left join musteri m on m.id = tor.musteri_id
                    WHERE tib.department_id = :dept 
                      AND tib.entity_type = :etype 
                      AND tib.entity_id = :eid 
                      AND tib.lot = :lot 
                      AND tir.musteri_id = :mid ORDER BY tib.id DESC LIMIT 1;";
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
            $sql = "SELECT inventory FROM toquv_mato_item_balance WHERE entity_id = %d AND entity_type = %d AND lot = %s AND department_id = %d AND document_id = %d ORDER BY id DESC LIMIT 1";
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

    public function getTir(){
        return $this->hasOne(MatoInfo::className(), ['id' => 'entity_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMato()
    {
        return $this->tir->entity;
    }
    public function getMatoColor()
    {
        $sql = "SELECT cp.code c_pantone,cp.name c_name,cp.r,cp.g,cp.b,c.pantone b_pantone,c.color_id,c.name b_name,c.color b_color
                FROM mato_info mi
                LEFT JOIN toquv_rm_order tro on mi.toquv_rm_order_id = tro.id
                LEFT JOIN color c on tro.color_id = c.id
                LEFT JOIN color_pantone cp on tro.color_pantone_id = cp.id
                WHERE mi.id = %d
        ";
        $sql = sprintf($sql,$this->entity_id);
        $item = Yii::$app->db->createCommand($sql)->queryOne();
        $res = [];
        $res['color'] = "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> ".$item['c_pantone'];
        $res['b_color'] = " <span style='background:{$item['b_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> {$item['color_id']}";
        return $res;
    }
    public function getMatoInfo()
    {
        $model_musteri = (!empty($this->tir->modelMusteri))?" (<span style='color:red'>{$this->tir->modelMusteri->name}</span>)":'';
        $roll = "(<b>".number_format($this->roll_inventory, 0)."</b> ".Yii::t('app', 'rulon');
        if($this->entity_type == ToquvMatoItemBalance::ENTITY_TYPE_MATO) {
            return "<b><span style='color:lime;background-color: maroon;padding: 0 5px;;'>{$this->mato->name}</span></b> (<b>{$this->tir->musteri->name}</b>{$model_musteri}) (<b>{$this->tir->pusFine->name}</b>) (<b>{$this->tir['thread_length']}</b> | <b>{$this->tir['finish_en']}</b> | <b>{$this->tir['finish_gramaj']})</b> {$roll} - <b>{$this->inventory}</b> kg)";
        }else{
            $roll = "(<b>".number_format($this->quantity_inventory, 0)."</b> ".Yii::t('app', 'dona');
            return  "<b>{$this->mato->color->name} <span style='color:lime;background-color: maroon;padding: 0 5px;;'>{$this->mato->name}</span></b> (<b>{$this->tir->musteri->name}</b>{$model_musteri}) (<b>{$this->tir->pusFine->name}</b>) (<b>{$this->tir['thread_length']}</b> | <b>{$this->tir['finish_en']}</b> | <b>{$this->tir['finish_gramaj']})</b> {$roll} - <b>{$this->inventory}</b> kg)";
        }
    }
}
