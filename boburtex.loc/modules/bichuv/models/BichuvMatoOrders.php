<?php

namespace app\modules\bichuv\models;

use app\components\OurCustomBehavior;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\Musteri;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvRawMaterials;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%bichuv_mato_orders}}".
 *
 * @property int $id
 * @property string $doc_number
 * @property string $reg_date
 * @property int $musteri_id
 * @property int $model_orders_id
 * @property int $model_orders_items_id
 * @property int $bichuv_doc_id
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvMatoOrderItems[] $bichuvMatoOrderItems
 * @property ModelOrders $modelOrders
 * @property ModelOrdersItems $moi
 * @property ActiveQuery $musteri
 * @property mixed $moiList
 * @property mixed $musteriList
 * @property BichuvDoc $bichuvDoc
 */
class BichuvMatoOrders extends BaseModel
{
    public $aksessuar;
    const STATUS_ACCEPTED = 4;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bichuv_mato_orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_number'], 'unique'],
            [['doc_number', 'musteri_id', 'model_orders_id', 'model_orders_items_id'], 'required'],
            [['reg_date'], 'safe'],
            [['musteri_id', 'model_orders_id', 'model_orders_items_id', 'bichuv_doc_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['doc_number'], 'string', 'max' => 25],
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
            'reg_date' => Yii::t('app', 'Sana'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'model_orders_id' => Yii::t('app', 'Model buyurtma'),
            'model_orders_items_id' => Yii::t('app', 'Model'),
            'bichuv_doc_id' => Yii::t('app', 'Bichuv Doc ID'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->reg_date = date('Y-m-d', strtotime($this->reg_date));
            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y', strtotime($this->reg_date));

    }
    /**
     * @return ActiveQuery
     */
    public function getBichuvDoc()
    {
        return $this->hasMany(BichuvDoc::className(), ['bichuv_mato_orders_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getBichuvMatoOrderItems()
    {
        return $this->hasMany(BichuvMatoOrderItems::className(), ['bichuv_mato_orders_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasOne(ModelOrders::className(), ['id' => 'model_orders_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getMoi()
    {
        return $this->hasOne(ModelOrdersItems::className(), ['id' => 'model_orders_items_id']);
    }
    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public static function getOrdersList($id,$map=false, $status = null,$operator = '=')
    {
        $sql = "SELECT
                    mo.id,
                    mo.doc_number,
                    m.name musteri,
                    mo.sum_item_qty sum,
                    reg_date
                FROM model_orders mo
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                WHERE ( m.id = :musteri_id )
            ";
        if(!empty($status)){
            $sql .= " AND mo.status {$operator} {$status}";
        }
        $res = Yii::$app->db->createCommand($sql)->bindValue(':musteri_id', $id)->queryAll();
        if($map){
            return ArrayHelper::map($res,'id',function($m){
                return $m['doc_number'].' - '.number_format($m['sum'],0,'','').' ('.date('d.m.Y H:i',strtotime($m['reg_date'])).')';
            });
        }
        return $res;
    }
    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public static function getOrderItemsList($id,$map=false)
    {
        $sql = "SELECT
                    moi.id,
                    mo.doc_number,
                    cp.code,
                    m.name musteri,
                    ml.name model,
                    ml.article artikul,
                    size_id,
                    st.name size_type,
                    load_date,
                    moi.season,
                    summa
                FROM model_orders_items moi 
                LEFT JOIN model_orders mo on moi.model_orders_id = mo.id 
                LEFT JOIN models_variations mv on moi.model_var_id = mv.id 
                LEFT JOIN models_variation_colors mvc on mv.id = mvc.model_var_id 
                LEFT JOIN color_pantone cp on mvc.color_pantone_id = cp.id 
                LEFT JOIN musteri m on mo.musteri_id = m.id 
                LEFT JOIN models_list ml on moi.models_list_id = ml.id 
                LEFT JOIN model_orders_items_size mois on moi.id = mois.model_orders_items_id 
                LEFT JOIN ( SELECT model_orders_items_id, SUM(count) summa FROM model_orders_items_size mois3 
                            LEFT JOIN size s2 on mois3.size_id = s2.id GROUP BY mois3.model_orders_items_id ) 
                    mois2 on moi.id = mois2.model_orders_items_id 
                LEFT JOIN size s on mois.size_id = s.id LEFT JOIN size_type st on s.size_type_id = st.id 
                WHERE 
                    ( mo.id = :order_id ) AND 
                    ( mvc.is_main = 1 ) AND 
                    ( mois.id = ( SELECT MAX(id) FROM model_orders_items_size mois WHERE mois.model_orders_items_id = moi.id )
            )";
        $res = Yii::$app->db->createCommand($sql)->bindValue(':order_id', $id)->queryAll();
        if($map){
            return ArrayHelper::map($res,'id',function($m){
                return "SM-{$m['id']} - ({$m['artikul']} {$m['model']}) - ({$m['code']}} - (".number_format($m['summa'],0,"","").") - (".date('d.m.Y H:i',strtotime($m['load_date'])).")";
            });
        }
        return $res;
    }
    public static function getOrderToquvList($id)
    {
        $sql = "SELECT
                    moi.id moi_id,
                    max(mop.id) mop_id,
                    trm.id,
                    mop.toquv_raw_materials_id,
                    max(mop.thread_length) thread_length,
                    max(mop.finish_en) finish_en,
                    max(mop.finish_gramaj) finish_gramaj,
                    sum(mop.finished_fabric) quantity,
                    max(mop.color_pantone_id) color_pantone_id,
                    max(mop.color_id) color_id,
                    sum(mop.count) count,
                    trm.name mato,
                    trm.type type,
                    CONCAT(cl.name,' ',cl.code) color
                FROM model_orders_planning mop
                LEFT JOIN model_orders_items moi ON mop.model_orders_items_id = moi.id
                LEFT JOIN toquv_raw_materials trm on mop.toquv_raw_materials_id = trm.id
                LEFT JOIN color_pantone cl ON cl.id = mop.color_pantone_id
                WHERE 
                    ( moi.id = :order_id )
                GROUP BY trm.id,mop.thread_length,mop.finish_en,mop.finish_gramaj,mop.color_pantone_id,mop.color_id
            ";
        return Yii::$app->db->createCommand($sql)->bindValue(':order_id', $id)->queryAll();
    }
    public static function getOrderToquvAcsList($id)
    {
        $sql = "SELECT
                    moi.id moi_id,
                    mop.id mop_id,
                    trm.id,
                    mop.thread_length,
                    mop.finish_en,
                    mop.finish_gramaj,
                    mop.finished_fabric quantity,
                    mop.color_pantone_id,
                    mop.color_id,
                    trm.name mato,
                    mop.count,
                    trm.type type
                FROM model_orders_items moi
                         LEFT JOIN models_list ml ON moi.models_list_id = ml.id
                         LEFT JOIN models_raw_materials mrm ON ml.id = mrm.model_list_id
                         LEFT JOIN toquv_raw_materials trm ON mrm.rm_id = trm.id
                         LEFT JOIN model_orders_planning mop ON mop.toquv_raw_materials_id = trm.id
                WHERE 
                    ( moi.id = :order_id )
                    AND mop.id IS NULL
                GROUP BY trm.id
            ";
        return Yii::$app->db->createCommand($sql)->bindValue(':order_id', $id)->queryAll();
    }
    public static function getOrderAcsList($id)
    {
        $sql = "SELECT
                    moi.id moi_id,                    
                    ba.id,
                    (moia.qty * summa) count,
                    moia.unit_id unit_id,
                    CONCAT(bap.name,' ',ba.name) acs
                FROM model_orders_items moi
                LEFT JOIN model_orders_items_acs moia on moi.id = moia.model_orders_items_id
                LEFT JOIN bichuv_acs ba on moia.bichuv_acs_id = ba.id
                LEFT JOIN bichuv_acs_property bap on ba.property_id = bap.id 
                LEFT JOIN model_orders_items_size mois on moi.id = mois.model_orders_items_id 
                LEFT JOIN ( SELECT model_orders_items_id, SUM(count) summa FROM model_orders_items_size mois3 
                            LEFT JOIN size s2 on mois3.size_id = s2.id GROUP BY mois3.model_orders_items_id ) 
                    mois2 on moi.id = mois2.model_orders_items_id
                WHERE 
                    ( moi.id = :order_id ) AND 
                    ( mois.id = ( SELECT MAX(id) FROM model_orders_items_size mois WHERE mois.model_orders_items_id = moi.id )) AND moia.id is not null
            ";
        return Yii::$app->db->createCommand($sql)->bindValue(':order_id', $id)->queryAll();
    }

    public function getCountDoc($status=BichuvDoc::STATUS_SAVED,$option='<')
    {
        $mato_ombor = ToquvDepartments::findOne(['token'=>'BICHUV_MATO_OMBOR']);
        $bichuv = ToquvDepartments::findOne(['token'=>'BICHUV_DEP']);
        $count = BichuvDoc::find()->where(['bichuv_mato_orders_id'=>$this->id,'document_type'=>BichuvDoc::DOC_TYPE_MOVING,'from_department'=>$mato_ombor['id'],'to_department'=>$bichuv['id']])->andWhere([$option,'status',$status])->count();
        return $count;
    }

    public function checkAks()
    {
        $count = BichuvMatoOrderItems::find()->where(['bichuv_mato_orders_id'=>$this->id,'entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS])->andWhere(['<','status',BichuvMatoOrderItems::STATUS_SAVED])->count();
        $aks = BichuvDocResponsible::find()->where(['type'=>2,'bichuv_mato_orders_id'=>$this->id])->one();
        if($aks||$count==0){
            return true;
        }
        return false;
    }

    public function getMusteriList()
    {
        $musteri = BichuvMatoOrders::find()->joinWith('musteri')->select('musteri.id,musteri.name')->where(['is not','musteri.id',new Expression('NULL')])->groupBy('musteri.id')->asArray()->all();
        return ArrayHelper::map($musteri,'id','name');
    }

    public function getMoiList()
    {
        $moi = BichuvMatoOrders::find()->alias('bmo')->joinWith('moi moi')->select('bmo.model_orders_items_id')->where(['is not','moi.id',new Expression('NULL')])->groupBy('bmo.model_orders_items_id')->all();
        return ArrayHelper::map($moi,'model_orders_items_id',function($model){
            return $model->moi->info;
        });
    }

    public function getMatoList($type=ToquvRawMaterials::ENTITY_TYPE_MATO,$kg=false)
    {
        $select = (!$kg)?"GROUP_CONCAT(DISTINCT CONCAT('<code>',trm.name,' - ',bmoi.quantity,' </code>') SEPARATOR ',<br>')":"SUM(bmoi.quantity)";
        $sql = "SELECT GROUP_CONCAT(DISTINCT CONCAT('<code>',trm.name,' - ',bmoi.quantity,' </code>') SEPARATOR ',<br>') mato,SUM(bmoi.quantity) qty
                FROM bichuv_mato_orders bmo
                LEFT JOIN bichuv_mato_order_items bmoi on bmo.id = bmoi.bichuv_mato_orders_id
                LEFT JOIN toquv_raw_materials trm ON bmoi.entity_id = trm.id
                WHERE bmoi.entity_type = %d AND bmoi.bichuv_mato_orders_id = %d
        ";
        $sql = sprintf($sql,$type,$this->id);
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public function getSizeCustomListPercentage($class='',$attribute='',$checked=false,$checked_list=null,$array=null){
        if($array){
            $res = $array;
        }else{
            $sql = "SELECT moi.id,moi.percentage,mois.id mois_id, s.id size_id, s.name size,mois.count count FROM size s
                LEFT JOIN model_orders_items_size mois ON mois.size_id = s.id
                LEFT JOIN model_orders_items moi on mois.model_orders_items_id = moi.id
                WHERE moi.id = %d
                GROUP BY moi.id,mois.id
            ";
            $sql = sprintf($sql,$this->model_orders_items_id);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
        }
        $list = [];
        $list['list'] = '';
        $list['all_count'] = 0;
        if(!empty($res)){
            if($checked){
                foreach ($res as $item) {
                    $num = $item['percentage']/100;
                    $disabled = '';
                    if($checked_list&&is_array($checked_list)&&in_array($item['size_id'], $checked_list)){
                        $disabled = 'disabled="disabled"';
                    }
                    $count = $num * $item['count'] + $item['count'];
                    $list['list'] = $list['list']."<span class='{$class}' {$attribute} >" . $item['size'] . " - <span id='size_count_{$item['mois_id']}' class='size_percentage_all_{$this->id}'>" . ceil($count) . "</span><input type='checkbox' class='size_checkbox size_checkbox_{$item['size_id']}' checked value='{$count}' data-name='{$item['size']}' data-id='{$item['size_id']}' $disabled></span>";
                    if(is_int($count)){
                        $list['all_count'] = (isset($list['all_count']))?$list['all_count']+$count:$count;
                    }
                }
            }else {
                foreach ($res as $item) {
                    $num = $item['percentage']/100;
                    $count = $num * $item['count'] + $item['count'];
                    $list['list'] = $list['list']."<span class='{$class}' {$attribute} >" . $item['size'] . " - <span id='size_count_{$item['mois_id']}' class='size_percentage_all_{$this->id}'>" . ceil($count) . "</span></span>";
                    if(is_int($count)){
                        $list['all_count'] = (isset($list['all_count']))?$list['all_count']+$count:$count;
                    }
                }
            }
        }
        return $list;
    }
}
