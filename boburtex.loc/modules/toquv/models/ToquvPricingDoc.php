<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "toquv_pricing_doc".
 *
 * @property int $id
 * @property string $doc_number
 * @property int $doc_type 
 * @property string $reg_date
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvPricingItem[] $toquvPricingItems
 */
class ToquvPricingDoc extends BaseModel
{
    const DOC_TYPE_IP = 1;
    const DOC_TYPE_MATO = 2;

    const DOC_TYPE_IP_LABEL   = 'narx_ip';
    const DOC_TYPE_MATO_LABEL     = 'narx_mato';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_pricing_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['doc_number','unique'],
            [['reg_date','doc_number'],'required'],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['doc_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['doc_number'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'doc_type' => Yii::t('app', 'Doc Type'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
     /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->reg_date = date('Y-m-d', strtotime($this->reg_date));
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));

    }
    /**
     * @param null $key
     * @return array|mixed
     */
    public function getDocTypes($key = null){
        $result = [
            self::DOC_TYPE_IP => Yii::t('app','Ip'),
            self::DOC_TYPE_MATO => Yii::t('app',"Mato"),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDocTypeBySlug($key = null){
        $result = [
            self::DOC_TYPE_IP_LABEL => Yii::t('app','Ip narxlari'),
            self::DOC_TYPE_MATO_LABEL => Yii::t('app',"Mato narxlari"),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public function getSlugLabel(){
        $slug = Yii::$app->request->get('slug');
        if(!empty($slug)){
            return self::getDocTypeBySlug($slug);
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvPricingItems()
    {
        return $this->hasMany(ToquvPricingItem::className(), ['doc_id' => 'id']);
    }
    public function getEntityAll($type){
        $sql = ($type != 'raw_material_type')?sprintf("SELECT id, name from toquv_{$type}
                WHERE status = 1"):sprintf("SELECT id, name from {$type}");
        $table = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($table)){
            $result = [];
            foreach ($table as $key){
                $result[$key['id']] = $key['name'];
            }
            return $result;
        }
        return null;
    }
    /**public static function getAllMato($type,$name,$id){
        $sql = sprintf("SELECT 
                        m.id,
                        m.name as mname,
                        t.name as tname
                        from toquv_raw_materials  as m
                        LEFT JOIN raw_material_type as t ON m.raw_material_type_id = t.id
                        WHERE m.status = 1");
        if($type !== 0 && is_array($type)){
            $type = implode(',', $type);
            $sql .= " and raw_material_type_id IN ({$type})";
        }
        if($name !== 0){
            $sql .= " and m.name LIKE '%{$name}%' or t.name LIKE '%{$name}%'";
        }
        $mato = Yii::$app->db->createCommand($sql)->queryAll();
        if(count($mato)!=0){
            $row = "";
            $doc = false;
            foreach ($mato as $key) {
                if($id!=0){
                    $doc = ToquvpricingItem::findOne(['doc_id'=>$id,'entity_id'=>$key['id'],'entity_type'=>2]);
                }
                $pul = ($doc)?static::pul($doc['pb_id']):static::pul();
                $row .= ($doc)
                ?
                    "<tr data-index='{$key["id"]}'>
                        <td>
                            <input type='hidden' name='doc[{$key["id"]}][id]' value='{$key["id"]}'> <span class='form-control'>{$key['mname']}-{$key['tname']}</span> 
                        </td> 
                        <td> 
                            <input type='number' name='doc[{$key['id']}][price]' value='{$doc['price']}' class='form-control price'> 
                        </td> 
                        <td> 
                            <select name='doc[{$key['id']}][pb_id]' class='form-control'>{$pul}</select> 
                        </td> 
                        <td> 
                            <span class='btn btn-danger removeTr glyphicon glyphicon-remove' style='padding: 0 5px'></span> 
                        </td> 
                    </tr>"
                :
                    "<tr data-index='{$key["id"]}'>
                        <td>
                            <input type='hidden' name='doc[{$key["id"]}][id]' value='{$key["id"]}'> <span class='form-control'>{$key['mname']}-{$key['tname']}</span>
                        </td> 
                        <td> 
                            <input type='number' name='doc[{$key['id']}][price]' class='form-control price'> 
                        </td> 
                        <td> 
                            <select name='doc[{$key['id']}][pb_id]' class='form-control'>{$pul}</select> 
                        </td> 
                        <td> 
                            <span class='btn btn-danger removeTr glyphicon glyphicon-remove' style='padding: 0 5px'></span> 
                        </td> 
                    </tr>";
            }
            return $row;
        }
        return 0;
    }*/
    public static function getAllIp($ne,$thread,$name,$id,$raw,$type){
        switch ($type) {
            case self::DOC_TYPE_IP_LABEL:
                $sql = sprintf("SELECT 
                            ip.id,
                            ip.name as ipname,
                            ne.name as nename,
                            thr.name as thrname, 
                            cl.name as clname 
                            from toquv_ip  as ip
                            LEFT JOIN toquv_ne as ne ON ip.ne_id = ne.id
                            LEFT JOIN toquv_thread as thr ON ip.thread_id = thr.id
                            LEFT JOIN toquv_ip_color as cl ON ip.color_id = cl.id
                            WHERE ip.status = 1");
                $doctype = 1;
                break;
            
            case self::DOC_TYPE_MATO_LABEL:
                $sql = sprintf("SELECT 
                            m.id,
                            m.name as ipname,
                            tp.name as tpname
                            from toquv_raw_materials as m
                            LEFT JOIN toquv_raw_material_ip as ip ON ip.toquv_raw_material_id = m.id
                            LEFT JOIN toquv_ne as ne ON ip.ne_id = ne.id
                            LEFT JOIN toquv_thread as thr ON ip.thread_id = thr.id
                            LEFT JOIN raw_material_type as tp ON m.raw_material_type_id = tp.id
                            WHERE ip.status = 1");
                $doctype = 2;
                break;
        }
        
        if($ne !== 0 && is_array($ne)){
            $ne = implode(',', $ne);
            $sql .= " and ne_id IN ({$ne})";
        }
        if($thread !== 0 && is_array($thread)){
            $thread = implode(',', $thread);
            $sql .= " and thread_id IN ({$thread})";
        }
        if($raw !== 0 && is_array($raw) && $type==self::DOC_TYPE_MATO_LABEL){
            $raw = implode(',', $raw);
            $sql .= " and raw_material_type_id IN ({$raw})";
        }
        if($name !== 0){
            $sql .= ($type==self::DOC_TYPE_MATO_LABEL)?" and m.name LIKE '%{$name}%' or ne.name LIKE '%{$name}%' or thr.name LIKE '%{$name}%'":" and ip.name LIKE '%{$name}%' or ne.name LIKE '%{$name}%' or thr.name LIKE '%{$name}%' or cl.name LIKE '%{$name}%'";
        }
        $sql .= ($type==self::DOC_TYPE_MATO_LABEL)?" GROUP BY m.id, m.name":"";
        $iplar = Yii::$app->db->createCommand($sql)->queryAll();
        if(count($iplar)!=0){
            $row = "";
            $doc = false;
            foreach ($iplar as $key) {
                if($id!=0){
                    $doc = ToquvpricingItem::findOne(['doc_id'=>$id,'entity_id'=>$key['id'],'entity_type'=>$doctype]);
                }
                $pul = ($doc)?static::pul($doc['pb_id']):static::pul();
                $name = ($type==self::DOC_TYPE_IP_LABEL)?"{$key['ipname']}-{$key['nename']}-{$key['thrname']}-{$key['clname']}":"{$key['ipname']}-{$key['tpname']}";
                $row .= ($doc)
                ?
                    "<tr data-index='{$key["id"]}' data-remove='{$doc['id']}'>
                        <td>
                            <input type='hidden' name='doc[{$key["id"]}][id]' value='{$key["id"]}'> <span class='form-control'>{$name}</span> 
                        </td> 
                        <td> 
                            <input type='number' name='doc[{$key['id']}][price]' value='{$doc['price']}' class='form-control price'> 
                        </td> 
                        <td> 
                            <select name='doc[{$key['id']}][pb_id]' class='form-control'>{$pul}</select> 
                        </td> 
                        <td> 
                            <span class='btn btn-danger removeTr glyphicon glyphicon-remove' style='padding: 0 5px'></span> 
                        </td> 
                    </tr>"
                :
                    "<tr data-index='{$key["id"]}'>
                        <td>
                            <input type='hidden' name='doc[{$key["id"]}][id]' value='{$key["id"]}'> <span class='form-control'>{$name}</span> 
                        </td> 
                        <td> 
                            <input type='number' name='doc[{$key['id']}][price]' class='form-control price'> 
                        </td> 
                        <td> 
                            <select name='doc[{$key['id']}][pb_id]' class='form-control'>{$pul}</select> 
                        </td> 
                        <td> 
                            <span class='btn btn-danger removeTr glyphicon glyphicon-remove' style='padding: 0 5px'></span> 
                        </td> 
                    </tr>";
            }
            return $row;
        }
        return 0;
    }
    public static function getIp($id){
        $sql = sprintf("SELECT 
                    ip.id,
                    ip.name as ipname,
                    ne.name as nename,
                    thr.name as thrname, 
                    cl.name as clname 
                    from toquv_ip  as ip
                    LEFT JOIN toquv_ne as ne ON ip.ne_id = ne.id
                    LEFT JOIN toquv_thread as thr ON ip.thread_id = thr.id
                    LEFT JOIN toquv_ip_color as cl ON ip.color_id = cl.id
                    WHERE ip.status = 1 and ip.id = {$id}");
        $ip = Yii::$app->db->createCommand($sql)->queryAll();
        return $ip[0]['ipname']."-".$ip[0]['nename']."-".$ip[0]['thrname']."-".$ip[0]['clname'];
    }
    public static function getMato($id){
        $sql = sprintf("SELECT name from toquv_raw_materials WHERE status = 1 and id = {$id}");
        $ip = Yii::$app->db->createCommand($sql)->queryAll();
        return $ip[0]['name'];
    }
    public function savePricing($doc){
        foreach ($doc as $key) {
            if(!empty($key['price'])&&$key['price']>0){
                $item = ToquvPricingItem::findOne(['doc_id'=>$this->id,'entity_id'=>$key['id'],'entity_type'=>$this->doc_type]);
                $pricing = ($item) ? $item : new ToquvPricingItem();
                $pricing->setAttributes([
                    'doc_id' => $this->id,
                    'entity_id' => $key['id'],
                    'entity_type' => $this->doc_type,
                    'price' => $key['price'],
                    'pb_id' => $key['pb_id'],
                ]);
                $pricing->save();
            }
        }
    }
    public function removePricing($remove){
        foreach ($remove as $key) {
            $item = ToquvPricingItem::findOne($key);
            if ($item) {
                $item->delete();
            }
        }
    }
    public static function pul($select=0){
        $pul = PulBirligi::find()->where(["status"=>PulBirligi::STATUS_ACTIVE])->all();
        $option = "";
        foreach ($pul as $key) {
            if($select!==0){
                $option .= ($select==$key['id'])?"<option value='{$key["id"]}' selected>{$key["name"]}</option>":"<option value='{$key["id"]}'>{$key["name"]}</option>";
            }else{
                $option .= "<option value='{$key["id"]}'>{$key["name"]}</option>";
            }
        }
        return $option;
    }
    public static function getListPul(){
        $pul = PulBirligi::find()->where(["status"=>PulBirligi::STATUS_ACTIVE])->asArray()->all();
        return ArrayHelper::map($pul,'id','name');
    }
}
