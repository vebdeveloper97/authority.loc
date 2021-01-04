<?php

namespace app\modules\toquv\models;

use app\modules\admin\models\ToquvUserDepartment;
use app\modules\bichuv\models\BichuvDoc;
use Yii;
use app\models\Users;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_documents".
 *
 * @property int $id
 * @property int $document_type
 * @property int $action
 * @property string $doc_number
 * @property string $reg_date
 * @property int $musteri_id
 * @property string $musteri_responsible
 * @property int $from_department
 * @property int $from_employee
 * @property int $to_department
 * @property int $to_employee
 * @property int $from_musteri
 * @property int $to_musteri
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property string $add_info
 * @property string $party
 * @property int $toquv_instructions_id
 * @property int $entity_type
 * @property int $is_tamir
 * @property int $parent_doc_id
 * @property int $order
 *
 * @property ToquvDocumentExpense[] $toquvDocumentExpenses
 * @property ToquvDocumentItems[] $toquvDocumentItems
 * @property ToquvDepartments $fromDepartment
 * @property Users $fromEmployee
 * @property Musteri $musteri
 * @property ToquvDepartments $toDepartment
 * @property Users $toEmployee
 * @property ToquvSaldo[] $toquvSaldos
 * @property BichuvDoc[] $bichuvDocs
 * @property null|array $employees
 * @property array|mixed $slugLabel
 * @property mixed $fromMusteri
 * @property null|array $userDeptId
 * @property null|array $musteries
 * @property mixed $toMusteri
 * @property null $useList
 * @property mixed $matoRemain
 * @property mixed $isOwnLabels
 * @property mixed $parent
 * @property RollMoveInfo[] $rollMoveInfos
 */
class ToquvDocuments extends BaseModel
{

    const DOC_TYPE_INCOMING               = 1;
    const DOC_TYPE_MOVING                 = 2;
    const DOC_TYPE_SELLING                = 3;
    const DOC_TYPE_OUTCOMING              = 5;
    const DOC_TYPE_SERVICE                = 6;
    const DOC_TYPE_VIRTUAL                = 7;
    const DOC_TYPE_WRITE_OFF_GOODS        = 8;
    const DOC_TYPE_INSIDE_MOVING          = 9;
    const DOC_TYPE_INSIDE_KALITE_MOVING   = 10;


    const DOC_TYPE_INCOMING_LABEL         = 'kirim_ip';
    const DOC_TYPE_MOVING_LABEL           = 'kochirish_ip';
    const DOC_TYPE_SELLING_LABEL          = 'sotish_ip';
    const DOC_TYPE_VIRTUAL_LABEL          = 'qabul_ip';
    const DOC_TYPE_OUTCOMING_LABEL        = 'chiqim_ip';
    const DOC_TYPE_SERVICE_LABEL          = 'xizmat_ip';
    const DOC_TYPE_WRITE_OFF_GOODS_LABEL  = 'hisobdan_chiqarish';
    const DOC_TYPE_MOVING_MATO_LABEL      = 'kochirish_mato';
    const DOC_TYPE_OUTCOMING_MATO_LABEL   = 'chiqim_mato';
    const DOC_TYPE_INCOMING_MATO_LABEL    = 'kirim_mato';
    const DOC_TYPE_MOVING_ACS_LABEL      = 'kochirish_aksessuar';
    const DOC_TYPE_OUTCOMING_ACS_LABEL   = 'chiqim_aksessuar';
    const DOC_TYPE_INCOMING_ACS_LABEL    = 'kirim_aksessuar';
    const DOC_TYPE_WRITE_OFF_GOODS_MATO_LABEL  = 'hisobdan_chiqarish_mato';
    const DOC_TYPE_WRITE_OFF_GOODS_ACS_LABEL  = 'hisobdan_chiqarish_aksessuar';
    const DOC_TYPE_INSIDE_MOVING_MATO_LABEL  = 'ichki_kochirish_mato';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type','reg_date','doc_number'],'required'],
            [['from_department', 'from_employee',  'to_employee','to_department'],'required','when' => function($model){
                return $model->document_type == 2;
            } ],
            ['doc_number','unique'],
            [['party'],'required','when' => function($model){
                $slug = Yii::$app->request->get('slug');
                return $model->document_type == 2 && $slug == 'kochirish_mato';
            } ],
            [['party'],'unique','when' => function($model){
                $slug = Yii::$app->request->get('slug');
                return $model->document_type == 2 && $slug == 'kochirish_mato';
            } ],
            [['document_type','to_department', 'action', 'musteri_id', 'from_department', 'from_employee',  'to_employee', 'from_musteri', 'to_musteri', 'status', 'created_at', 'updated_at', 'created_by', 'toquv_instructions_id', 'entity_type', 'is_tamir', 'parent_doc_id', 'order'], 'integer'],
            [['to_department','musteri_id'],'required','when' => function($model){
                $slug = Yii::$app->request->get('slug');
                return $model->document_type == 1 && $slug == 'kirim_ip';
            } ],
            [['from_department', 'from_employee',  'to_employee', 'from_musteri', 'to_musteri'],'required','when' => function($model){
                return $model->document_type == 9;
            } ],
            [['add_info'],'required','when'=> function($model){
                return $model->document_type === 8;
            }],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['doc_number'], 'string', 'max' => 25],
            [['musteri_responsible'], 'string', 'max' => 255],
            [['party'], 'string', 'max' => 100],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['from_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['from_employee' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['to_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['to_employee' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'action' => Yii::t('app', 'Action'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'musteri_responsible' => Yii::t('app', 'Musteri Responsible'),
            'from_department' => Yii::t('app', 'From Department'),
            'from_employee' => Yii::t('app', 'From Employee'),
            'to_department' => Yii::t('app', 'To Department'),
            'to_employee' => Yii::t('app', 'To Employee'),
            'from_musteri' => Yii::t('app', 'Qaysi mijozdan'),
            'to_musteri' => Yii::t('app', 'Qaysi mijozga'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'add_info' => Yii::t('app', 'Add Info'),
            'party' => Yii::t('app', 'Party'),
            'toquv_instructions_id' => Yii::t('app', 'Toquv Instructions ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'is_tamir' => Yii::t('app', 'Is Tamir'),
            'parent_doc_id' => Yii::t('app', 'Parent Doc ID'),
            'order' => Yii::t('app', 'Order'),
        ];
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $date =  date('Y-m-d', strtotime($this->reg_date));
            $currentTime = date('H:i:s');
            $this->reg_date = date('Y-m-d H:i:s', strtotime($date.' '.$currentTime));
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y H:i', strtotime($this->reg_date));

    }
    public function getParent()
    {
        return $this->hasOne(ToquvDocuments::className(), ['id' => 'parent_doc_id']);
    }
    public function getBichuvDocs()
    {
        return $this->hasMany(BichuvDoc::className(), ['toquv_doc_id' => 'id']);
    }
    public function getRollMoveInfos()
    {
        return $this->hasMany(RollMoveInfo::className(), ['toquv_documents_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentItems()
    {
        return $this->hasMany(ToquvDocumentItems::className(), ['toquv_document_id' => 'id']);
    }
    public function getTdi($type=null,$group=null)
    {
        $result = ToquvDocumentItems::find();
        if($group){
            $result = $result->select('id,SUM(quantity) quantity,tib_id,lot,entity_id');
        }
        $result = $result->where(['toquv_document_id' => $this->id]);
        if($type){
            $result = $result->andWhere(['entity_type'=>$type]);
        }
        if($group){
            $result = $result->groupBy(['entity_id','lot','entity_type']);
        }
        return $result->all();
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }
    public function getFromMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'from_musteri']);
    }
    public function getToMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'to_musteri']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'from_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'to_employee']);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getDocTypes($key = null){
        $result = [
            self::DOC_TYPE_INCOMING         => Yii::t('app','Qabul qilish'),
            self::DOC_TYPE_MOVING           => Yii::t('app',"O'tkazish"),
            self::DOC_TYPE_SELLING          => Yii::t('app',"Sotish"),
            self::DOC_TYPE_OUTCOMING         => Yii::t('app',"Chiqim qilish"),
            self::DOC_TYPE_VIRTUAL          => Yii::t('app',"Qabul qilish"),
            self::DOC_TYPE_SERVICE          => Yii::t('app',"Xizmat uchun yuborish"),
            self::DOC_TYPE_WRITE_OFF_GOODS  => Yii::t('app',"Hisobdan chiqarish"),
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
            self::DOC_TYPE_INCOMING_LABEL         => Yii::t('app','Kirim ip'),
            self::DOC_TYPE_MOVING_LABEL           => Yii::t('app',"Ip ko'chirish"),
            self::DOC_TYPE_SELLING_LABEL          => Yii::t('app',"Sotish ip"),
            self::DOC_TYPE_VIRTUAL_LABEL          => Yii::t('app',"Ip Qabul Qilish"),
            self::DOC_TYPE_OUTCOMING_LABEL        => Yii::t('app',"Ip Chiqim Qilish"),
            self::DOC_TYPE_SERVICE_LABEL          => Yii::t('app',"Xizmat uchun ip yuborish"),
            self::DOC_TYPE_WRITE_OFF_GOODS_LABEL  => Yii::t('app',"Hisobdan chiqarish"),
            self::DOC_TYPE_MOVING_MATO_LABEL      => Yii::t('app',"Mato ko'chirish"),
            self::DOC_TYPE_OUTCOMING_MATO_LABEL   => Yii::t('app',"Mato Chiqim Qilish"),
            self::DOC_TYPE_INCOMING_MATO_LABEL    => Yii::t('app',"Mato Kirim Qilish"),
            self::DOC_TYPE_MOVING_ACS_LABEL       => Yii::t('app',"Aksessuar ko'chirish"),
            self::DOC_TYPE_OUTCOMING_ACS_LABEL    => Yii::t('app',"Aksessuar Chiqim Qilish"),
            self::DOC_TYPE_INCOMING_ACS_LABEL     => Yii::t('app',"Aksessuar Kirim Qilish"),
            self::DOC_TYPE_WRITE_OFF_GOODS_MATO_LABEL  => Yii::t('app',"Hisobdan chiqarish (Mato)"),
            self::DOC_TYPE_WRITE_OFF_GOODS_ACS_LABEL   => Yii::t('app',"Hisobdan chiqarish (Aksessuar)"),
            self::DOC_TYPE_INSIDE_MOVING_MATO_LABEL    => Yii::t('app',"Ichki ko'chirish (Mato)"),
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }


    /**
     * @param int $t
     * @return mixed
     */
    public function getIsOwnLabel($t = 1){
        $res = [
            1 => Yii::t('app','Bizniki'),
            2 => Yii::t('app','Mijozniki')
        ];
        return $res[$t];
    }

    /**
     * @param int $t
     * @return mixed
     */
    public function getIsOwnLabels(){
        $res = [
            1 => Yii::t('app','Bizniki'),
            2 => Yii::t('app','Mijozniki')
        ];
        return $res;
    }

    public function getSlugLabel(){
        $slug = Yii::$app->request->get('slug');
        if(!empty($slug)){
            return self::getDocTypeBySlug($slug);
        }
    }
    /**
     * @return array|null
     */
    public function getEmployees(){
        $user = Users::find()->select(['id','user_fio'])->where(['id' => Yii::$app->user->id])->asArray()->all();
        if(!empty($user)){
            return ArrayHelper::map($user,'id','user_fio');
        }
        return null;
    }
    /*public function getFromDeptEmployees(){
        $user = Users::find()->select(['id','user_fio'])->where(['id' => Yii::$app->user->id])->asArray()->all();
        if(!empty($user)){
            return ArrayHelper::map($user,'id','user_fio');
        }
        return null;
    }*/
    public function getUseList(){
        $user = Users::find()->select(['id','user_fio','lavozimi','add_info'])->asArray()->all();
        if(!empty($user)){
            return ArrayHelper::map($user,'id', function($model){
                return $model['user_fio']." ".$model['lavozimi']. " ". $model['add_info'];
            });
        }
        return null;
    }
    /**
     * @return array|null
     */
    public function getMusteries(){
        $results = ToquvMusteri::find()->select(['id','name'])->where(['status' => self::STATUS_ACTIVE])->asArray()->orderBy(['name' => SORT_ASC])->all();
        if(!empty($results)){
            return ArrayHelper::map($results,'id','name');
        }
        return null;
    }

    /**
     * @param bool $isGetAll
     * @return array|null
     */
    public function getDepartments($isGetAll = false, $dept = false){
        if(!$isGetAll){
            $availIds = ToquvUserDepartment::find()->where(['status' => self::STATUS_ACTIVE, 'user_id' => Yii::$app->user->id]);
            if($dept){
                $availIds = $availIds->andWhere(['in','department_id',$dept]);
            }
            $availIds = $availIds->select(['department_id'])->asArray()->all();
            if (!empty($availIds)) {
                $ids = ArrayHelper::getColumn($availIds,'department_id');
                $result = ToquvDepartments::find()->select(['id','name'])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['in','id', $ids])->asArray()->all();
            } else {
                return [];
            }
            if(!empty($result)){
                return ArrayHelper::map($result,'id','name');
            }
        }else{
            $depts = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE]);
            if($dept){
                $depts = $depts->andWhere(['in','id',$dept]);
            }
            $depts= $depts->asArray()->all();
            return ArrayHelper::map($depts,'id','name');
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getUserDeptId(){
        $availIds = ToquvUserDepartment::find()->select(['department_id'])
            ->where(['status' => self::STATUS_ACTIVE, 'user_id' => Yii::$app->user->id])
            ->asArray()->all();
        if (!empty($availIds)) {
            $ids = ArrayHelper::getColumn($availIds,'department_id');
            $result = ToquvDepartments::find()->select(['id'])
                ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                ->andFilterWhere(['in','id', $ids])->asArray()->all();
            return ArrayHelper::getColumn($result,'id');
        } else {
            return null;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvSaldos()
    {
        return $this->hasMany(ToquvSaldo::className(), ['td_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocumentExpenses()
    {
        return $this->hasMany(ToquvDocumentExpense::className(), ['document_id' => 'id']);
    }

    /**
     * @param $params
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function searchEntities($params){
        $q ='';
        if(!empty($params['query'])){
            $q = " AND ((ip.name LIKE '%{$params['query']}%') OR (ne.name LIKE '%{$params['query']}%') OR (thr.name LIKE '%{$params['query']}%') OR (cl.name LIKE '%{$params['query']}%') OR (t1.lot LIKE '%{$params['query']}%'))";
        }
        $isOwn = 1;
        if(isset($params['is_own']) && !empty($params['is_own'])){
            $isOwn = $params['is_own'];
        }
        $tib = "";
        if(isset($params['tib']) && !empty($params['tib'])){
            $tib = "AND t1.id IN ({$params['tib']})";
        }
        if(!empty($params['musteri'])){
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname 
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id = %d AND is_own = %d GROUP BY entity_id, lot, musteri_id ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=%d) AND (department_id=%d) AND (t1.is_own = %d) AND (t1.inventory > 0) AND (t1.musteri_id = %d) %s %s
                    GROUP BY t1.entity_id, t1.lot LIMIT 500;";
            $sql = sprintf($sql,
                $params['department_id'],
                $isOwn,
                $params['entity_type'],
                $params['department_id'],
                $isOwn,
                $params['musteri'],
                $q,
                $tib);
        }else{
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname 
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id = %d AND is_own = %d GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=%d) AND (department_id=%d) AND (t1.is_own = %d) AND (t1.inventory > 0) %s %s
                    GROUP BY t1.entity_id, t1.lot LIMIT 500;";
            $sql = sprintf($sql,
                $params['department_id'],
                $isOwn,
                $params['entity_type'],
                $params['department_id'],
                $isOwn,
                $q, $tib);
        }
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function searchEntitiesOne($params=[]){
        $isOwn = 1;
        if(isset($params['is_own']) && !empty($params['is_own'])){
            $isOwn = $params['is_own'];
        }
        $tib = "";
        if(isset($params['tib']) && !empty($params['tib'])){
            $tib = "AND t1.id IN ({$params['tib']})";
        }
        $lot = '';
        if(isset($params['lot']) && !empty($params['lot'])){
            $tib = "AND t1.lot = ({$params['lot']})";
        }
        if(!empty($params['musteri'])){
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname, t1.is_own is_own
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id = %d AND is_own = %d GROUP BY entity_id, lot, musteri_id ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=%d) AND (department_id=%d) AND (t1.is_own = %d) AND (t1.inventory > 0) AND (t1.musteri_id = %d)  %s %s
                    GROUP BY t1.entity_id, t1.lot LIMIT 500;";
            $sql = sprintf($sql,
                $params['department_id'],
                $isOwn,
                $params['entity_type'],
                $params['department_id'],
                $isOwn,
                $params['musteri'],
                $tib,
                $lot);
        }else{
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname, t1.is_own is_own
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id = %d AND is_own = %d GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=%d) AND (department_id=%d) AND (t1.is_own = %d) AND (t1.inventory > 0) %s %s
                    GROUP BY t1.entity_id, t1.lot LIMIT 500;";
            $sql = sprintf($sql,
                $params['department_id'],
                $isOwn,
                $params['entity_type'],
                $params['department_id'],
                $isOwn,
                $tib,
                $lot);
        }
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function getInstructions($dept,$arr=null)
    {
        $sql = "SELECT ti.id,
                       tor.document_number doc,
                       m.name musteri,
                       tor.done_date date,
                       mo.doc_number model_doc,
                       m2.name model_musteri
                FROM toquv_instructions ti
                LEFT JOIN toquv_orders tor ON tor.id = ti.toquv_order_id
                LEFT JOIN musteri m on tor.musteri_id = m.id
                LEFT JOIN model_orders mo ON tor.model_orders_id = mo.id
                LEFT JOIN musteri m2 ON mo.musteri_id = m2.id
                WHERE (ti.is_closed = 1) AND ti.status = 3 AND ti.to_department = %d ORDER BY tor.id desc";
        $sql = sprintf($sql,$dept);
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if(!$arr) {
            return $result;
        }
        return ArrayHelper::map($result,'id',function ($m){
            $order = ($m['model_doc']) ? " ({$m['model_doc']} - {$m['model_musteri']})" : "";
            $name = "{$m['doc']} - {$m['musteri']} - {$m['date']}{$order}";
            return $name;
        });
    }
    public function searchMato($params){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (t.document_number LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $tib = "";
        if(isset($params['tib']) && !empty($params['tib'])){
            $tib = "AND tir.id IN ({$params['tib']})";
        }
        $sql = "SELECT tir.id,
                    tpf.name pus_fine,
                    trm.id mato_id,
                    trm.name mato,
                    t.document_number doc_number,
                    t.id toquv_orders_id,
                    tro.id toquv_rm_order_id,
                    m.name musteri,
                    SUM(tk.quantity) summa,
                    tro.quantity quantity
                FROM toquv_kalite tk 
                LEFT JOIN toquv_instruction_rm tir on tk.toquv_instruction_rm_id = tir.id
                LEFT JOIN toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_orders t ON tro.toquv_orders_id = t.id
                LEFT JOIN musteri m ON t.musteri_id = m.id
                LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                WHERE (tk.sort_name_id = 1) %s %s
                GROUP BY tir.id, trm.id, tpf.id, tir.thread_length, tir.finish_en, tir.finish_gramaj LIMIT 500;";
        $sql = sprintf($sql,$q,$tib);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function getMatoList($id=null){
        $tib = "";
        if($id){
            $tib = "AND (td.id = {$id})";
        }
        $sql = "SELECT
                    tir.id as id,
                   trm.name as mato,
                   trmc.name as mato_color,
                   tir.thread_length as length,
                   tir.finish_gramaj as gramaj,
                   tir.finish_en as en,
                   tpf.name as pus_fine,
                   m.name as mushteri,
                   m3.name as model_musteri,
                   m2.name as musteri,
                   ti.reg_date,
                   ti.toquv_order_id as order_id,
                   tir.toquv_rm_order_id as order_item_id,
                   tro.quantity,
                   tro.id toquv_rm_order_id,
                   tor.id toquv_orders_id,
                   mt.name type_weaving
                FROM toquv_documents td
                LEFT JOIN toquv_document_items tdi on td.id = tdi.toquv_document_id
                LEFT JOIN mato_info tir on tdi.entity_id = tir.id
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join toquv_pus_fine tpf on tir.pus_fine_id = tpf.id
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id   
                left join musteri m on tir.musteri_id = m.id 
                left join musteri m3 on tor.model_musteri_id = m3.id 
                left join toquv_raw_materials trm on tir.entity_id = trm.id
                left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                left join material_type mt on tir.type_weaving = mt.id
                left join musteri m2 on ti.musteri_id = m2.id
                WHERE (tir.status > 0) %s 
                GROUP BY tir.id, trm.id, tpf.id, tir.thread_length, tir.finish_en, tir.finish_gramaj ORDER BY tir.id ASC LIMIT 500;";
        $sql = sprintf($sql,$tib);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $item){
            $pf = Yii::t('app','Pus Fine');
            $lth = Yii::t('app','Ip uz-i');
            $gr = Yii::t('app','F.Gr-j');
            $en = Yii::t('app','F.En');
            $order = Yii::t('app','Buyurtma');
            $dt = Yii::t('app',"Ko'rsatma");
            $reg_date = date('d.m.Y H:i', strtotime($item['reg_date']));
            $qty = number_format($item['quantity'],0, '.', '');
            $musteri = ($servis==2)?$item['musteri']:'';
            $name = "{$item['mato']} | {$item['pus_fine']} | {$lth} : {$item['length']} | {$gr} : {$item['gramaj']} | {$en} : {$item['en']}) ({$item['mushteri']} - {$qty} kg) ({$item['type_weaving']}) {$musteri}";
            $model_mushteri = (!empty($item['model_mushteri']))?" (<span style='color:red'>{$item['model_mushteri']}</span>)":'';
            $name = "<b>{$item['mato_color']} <span style='color:lightblue;background-color: black;padding: 0 5px;'>{$item['mato']}</span></b> (<b>{$item['mushteri']}{$model_mushteri}</b>) (<b>{$item['pus_fine']}</b>) (<b>{$item['length']}</b> | <b>{$item['en']}</b> | <b>{$item['gramaj']}</b>)  <b>";
            $dataEntities['list'][$item['id']] = $name;
            $dataEntities['options'][$item['id']] = [
                'data-sum' => $item['summa'],
                'data-order_item_id' => $item['toquv_rm_order_id'],
                'data-order_id' => $item['toquv_orders_id'],
            ];
            $dataEntities['attr'][$item['id']] = $item['mato'];
        }
        return $dataEntities;
    }
    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchMatoMoving($params,$type=ToquvDocuments::ENTITY_TYPE_MATO,$sort=1){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $tib = "";
        if(isset($params['tib']) && !empty($params['tib'])){
            $tib = "AND tib.id IN ({$params['tib']})";
        }
        $lot = (!empty($sort))?" AND tib.lot = {$sort}":"";
        $dept = $params['dept'];
        $sql = "select t2.id,
                       tir.id as rmid,
                       (select tib2.quantity_inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.entity_type = %d AND tib2.department_id = %d AND tib2.lot = tib.lot ORDER BY tib2.id DESC LIMIT 1) as count,
                       (select tib2.roll_inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.entity_type = %d AND tib2.department_id = %d AND tib2.lot = tib.lot ORDER BY tib2.id DESC LIMIT 1) as roll,
                       (select tib2.inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.entity_type = %d AND tib2.department_id = %d AND tib2.lot = tib.lot ORDER BY tib2.id DESC LIMIT 1) as remain,
                       trm.name as mato,
                       trmc.name as mato_color,
                       tir.thread_length as length,
                       tir.finish_gramaj as gramaj,
                       tir.finish_en as en,
                       tpf.name as pus_fine,
                       m.name as mushteri,
                       m2.name as model_mushteri,
                       ti.reg_date,
                       tor.id as order_id,
                       tro.id as order_item_id,
                       tib.lot,
                        cp.code c_pantone,cp.name c_name,cp.r,cp.g,cp.b,c.pantone b_pantone,c.color_id,c.name b_name,c.color b_color
                from toquv_mato_item_balance tib
                left join mato_info tir on tir.id = tib.entity_id
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join toquv_pus_fine tpf on tir.pus_fine_id = tpf.id
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id   
                LEFT JOIN color c on tro.color_id = c.id
                LEFT JOIN color_pantone cp on tro.color_pantone_id = cp.id
                left join musteri m on tir.musteri_id = m.id 
                left join musteri m2 on tor.model_musteri_id = m2.id
                left join toquv_raw_materials trm on tir.entity_id = trm.id
                left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                JOIN (SELECT MAX(id) as id, SUM(count) as total from toquv_mato_item_balance tib WHERE entity_type = %d AND tib.department_id = %d %s %s GROUP BY tib.entity_id) as t2 ON tib.id = t2.id
                where tib.entity_type = %d AND tib.department_id = %d %s %s %s GROUP BY tib.entity_id,t2.id ORDER BY t2.id DESC;";
        $sql = sprintf($sql, $type, $dept, $type, $dept, $type, $dept, $type, $dept, $tib, $lot, $type, $dept, $q, $tib, $lot);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchMatoInsideMoving($params,$type=ToquvDocuments::ENTITY_TYPE_MATO,$sort=1){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $tib = "";
        if(isset($params['tib']) && !empty($params['tib'])){
            $tib = "AND tib.id IN ({$params['tib']})";
        }
        $dept = $params['dept'];
        $musteri = $params['musteri'];
        $sql = "select t2.id,
                       tir.id as rmid,
                       (select tib2.quantity_inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.entity_type = %d AND tib2.department_id = %d AND tib2.lot = tib.lot ORDER BY tib2.id DESC LIMIT 1) as count,
                       (select tib2.roll_inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.entity_type = %d AND tib2.department_id = %d AND tib2.lot = tib.lot ORDER BY tib2.id DESC LIMIT 1) as roll,
                       (select tib2.inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.entity_type = %d AND tib2.department_id = %d AND tib2.lot = tib.lot ORDER BY tib2.id DESC LIMIT 1) as remain,
                       trm.name as mato,
                       tir.thread_length as length,
                       tir.finish_gramaj as gramaj,
                       tir.finish_en as en,
                       tpf.name as pus_fine,
                       m.name as mushteri,
                       m2.name as model_mushteri,
                       ti.reg_date,
                       tor.id as order_id,
                       tro.id as order_item_id,
                       tib.lot
                from toquv_mato_item_balance tib
                left join mato_info tir on tir.id = tib.entity_id
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join toquv_pus_fine tpf on tir.pus_fine_id = tpf.id
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id   
                left join musteri m on tir.musteri_id = m.id 
                left join musteri m2 on tor.model_musteri_id = m2.id
                left join toquv_raw_materials trm on tir.entity_id = trm.id
                JOIN (SELECT MAX(id) as id, SUM(count) as total from toquv_mato_item_balance tib WHERE entity_type = %d AND tib.department_id = %d AND tib.lot = %d %s GROUP BY tib.entity_id) as t2 ON tib.id = t2.id
                where tib.entity_type = %d AND tib.department_id = %d AND tir.musteri_id = %d AND tib.lot = %d %s %s GROUP BY tib.entity_id,t2.id ORDER BY m.name, m2.name, trm.name, tpf.id ASC;";
        $sql = sprintf($sql, $type, $dept, $type, $dept, $type, $dept, $type, $dept, $sort, $tib, $type, $dept, $musteri, $sort, $q, $tib);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchChiqimMato($params,$type=ToquvDocuments::ENTITY_TYPE_MATO){
        $q ='';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $dept = $params['dept'];
        $sql = "SELECT 
                        t1.id,
                        tir.id tir_id,
                        trm.id mato_id,
                        CONCAT(trm.name,' - ',tpf.name) mato,
                        t1.inventory summa,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                        m.name musteri,
                        t1.lot
                FROM toquv_mato_item_balance t1
                LEFT JOIN mato_info tir ON t1.entity_id = tir.id
                LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                LEFT JOIN toquv_raw_materials trm ON tir.entity_id = trm.id
                LEFT JOIN musteri m ON t1.musteri_id = m.id
                LEFT JOIN toquv_documents td ON td.id = t1.document_id
                JOIN (SELECT MAX(id) as id, SUM(count) as total from toquv_mato_item_balance tib WHERE tib.entity_type = %d AND tib.department_id = %d GROUP BY tib.entity_id) as t2 ON t1.id = t2.id
                WHERE t1.entity_type = %d AND t1.department_id = %d %s
                ORDER BY t1.id DESC";
        $sql = sprintf($sql, $type, $dept, $type, $dept, $q);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchAcsMoving($params){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $tib = "";
        if(isset($params['tib']) && !empty($params['tib'])){
            $tib = "AND tib.id IN ({$params['tib']})";
        }
        $dept = "";
        if(!empty($params['dept'])){
            $dept = $params['dept'];
        }
        $sql = "select tib.id,
                       tir.id as rmid,
                       (select inventory from toquv_mato_item_balance tib2 where tib2.entity_id = tib.entity_id AND tib2.department_id = %d ORDER BY tib2.id DESC LIMIT 1) as remain,
                       trm.name as mato,
                       tir.thread_length as length,
                       tir.finish_gramaj as gramaj,
                       tir.finish_en as en,
                       tpf.name as pus_fine,
                       m.name as mushteri,
                       ti.reg_date,
                       ti.toquv_order_id as order_id,
                       tir.toquv_rm_order_id as order_item_id 
                from toquv_mato_item_balance tib
                left join mato_info tir on tir.id = tib.entity_id
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join toquv_pus_fine tpf on tir.pus_fine_id = tpf.id
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id   
                left join musteri m on tir.musteri_id = m.id 
                left join toquv_raw_materials trm on tir.entity_id = trm.id
                JOIN (SELECT MAX(id) as id, SUM(count) as total from toquv_mato_item_balance tib WHERE entity_type = 3 AND to_department = %s GROUP BY entity_id ORDER BY id ASC) as t2 ON tib.id = t2.id
                where tib.entity_type = 3 %s %s GROUP BY tib.entity_id, tib.id ORDER BY tib.id DESC;";
        $sql = sprintf($sql, $dept, $dept, $q, $tib);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchChiqimAcs($params){
        $acs_seh = ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SEH'])['id'];
        $acs_ombor = ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SKLAD'])['id'];
        $q ='';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $dept =" AND t1.to_department = {$acs_ombor}";
        if(!empty($params['dept'])){
            $dept = " AND (t1.to_department = {$params['dept']})";
        }
        $sql = "SELECT 
                        t1.id,
                        tir.id tir_id,
                        trm.id mato_id,
                        CONCAT(trm.name,' - ',tpf.name) mato,
                       t1.inventory summa,
                        tir.thread_length,
                        tir.finish_en,
                        tir.finish_gramaj,
                       m.name musteri
                FROM toquv_mato_item_balance t1
                LEFT JOIN mato_info tir ON t1.entity_id = tir.id
                LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                LEFT JOIN toquv_raw_materials trm ON tir.entity_id = trm.id
                LEFT JOIN musteri m ON t1.musteri_id = m.id
                LEFT JOIN toquv_documents td ON td.id = t1.document_id
                JOIN (SELECT MAX(id) as id, SUM(count) as total from toquv_mato_item_balance tib WHERE entity_type = 3 AND department_id = {$acs_seh}  AND to_department = {$acs_ombor} GROUP BY entity_id ORDER BY id ASC) as t2 ON t1.id = t2.id
                WHERE t1.entity_type = 3 AND t1.department_id = {$acs_seh} %s %s
                ORDER BY t1.id DESC";
        $sql = sprintf($sql,$q,$dept);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function getRemain($id)
    {
        $type = ToquvDocuments::DOC_TYPE_MOVING;
        $sql = "SELECT
                       SUM(tdi.quantity) remain
                FROM toquv_document_items tdi
                LEFT JOIN toquv_doc_items_rel_order tdiro on tdi.id = tdiro.toquv_document_items_id
                LEFT JOIN toquv_rm_order tro on tdiro.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_documents td on tdi.toquv_document_id = td.id
                WHERE (tdi.entity_type = 2) AND (tro.id = %d) AND (td.document_type = %d) group by tro.id";
        $sql = sprintf($sql,$id,$type);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchMatoIncoming($params,$type=ToquvRawMaterials::MATO,$servis=1){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $sql = "select
                       tir.id as id,
                       trm.name as mato,
                       tir.thread_length as length,
                       tir.finish_gramaj as gramaj,
                       tir.finish_en as en,
                       tpf.name as pus_fine,
                       m.name as mushteri,
                       m2.name as musteri,
                       ti.reg_date,
                       ti.toquv_order_id as order_id,
                       tir.toquv_rm_order_id as order_item_id,
                       tro.quantity,
                       tir.quantity tir_qty, 
                       mt.name type_weaving
                from toquv_instruction_rm tir
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join toquv_pus_fine tpf on tir.toquv_pus_fine_id = tpf.id
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id   
                left join musteri m on tor.musteri_id = m.id 
                left join toquv_raw_materials trm on tro.toquv_raw_materials_id = trm.id
                left join material_type mt on tir.type_weaving = mt.id
                left join musteri m2 on ti.musteri_id = m2.id
                where ti.is_closed = 1 AND ti.is_service = %d AND ti.type = %d %s GROUP BY tir.id ORDER BY trm.name ASC;";
        $sql = sprintf($sql, $servis, $type, $q);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function searchMatoIncomingInventarizatsiya($params,$type=ToquvRawMaterials::ENTITY_TYPE_MATO,$servis=1){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((trm.name LIKE '%{$params['query']}%') OR (m.name LIKE '%{$params['query']}%'))";
        }
        $sql = "select
                       tir.id as id,
                       trm.name as mato,
                       trmc.name as mato_color,
                       tir.thread_length as length,
                       tir.finish_gramaj as gramaj,
                       tir.finish_en as en,
                       tpf.name as pus_fine,
                       m.name as mushteri,
                       m2.name as musteri,
                       m3.name as model_musteri,
                       ti.reg_date,
                       ti.toquv_order_id as order_id,
                       tir.toquv_rm_order_id as order_item_id,
                       tro.quantity,
                       mt.name type_weaving,
                        trm.code,
                        type.name as tname,
                        ne_id,
                        cp.code pantone,
                        c.color_id color,
                        tn.name ne,
                        tt.name thread 
                from mato_info tir
                left join toquv_instructions ti on tir.toquv_instruction_id = ti.id
                left join toquv_pus_fine tpf on tir.pus_fine_id = tpf.id
                left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                left join toquv_orders tor on tro.toquv_orders_id = tor.id   
                left join musteri m on tir.musteri_id = m.id 
                left join toquv_raw_materials trm on tir.entity_id = trm.id
                left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                left join material_type mt on tir.type_weaving = mt.id
                left join musteri m2 on ti.musteri_id = m2.id
                left join musteri m3 on tor.model_musteri_id = m3.id
                left join color_pantone cp ON cp.id = tro.color_pantone_id
                left join color c ON c.id = tro.color_id
                LEFT JOIN
                    raw_material_type as type                    
                        ON trm.raw_material_type_id = type.id     
                LEFT JOIN
                    toquv_raw_material_ip trmi 
                        on trm.id = trmi.toquv_raw_material_id     
                LEFT JOIN
                    toquv_ne tn 
                        on trmi.ne_id = tn.id     
                LEFT JOIN
                    toquv_thread tt 
                        on trmi.thread_id = tt.id
                where tir.entity_type = %d %s ORDER BY m.name, m3.name, trm.name, tpf.id ASC;";
        $sql = sprintf($sql, $type, $q);
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $ip = [];
        $dataEntities = [];
        $dataEntities['list'] = [];
        $dataEntities['options'] = [];
        if(!empty($res)){
            foreach ($res as $key => $item){
                $pf = Yii::t('app','Pus Fine');
                if($type===ToquvRawMaterials::ENTITY_TYPE_ACS){
                    $lth = Yii::t('app', 'Uz-i');
                    $gr = Yii::t('app', "Eni");
                    $en = Yii::t('app', 'Qavati');
                    $type_weaving = "";
                }else {
                    $lth = Yii::t('app', 'Ip uz-i');
                    $gr = Yii::t('app', 'F.Gr-j');
                    $en = Yii::t('app', 'F.En');
                    $type_weaving = "({$item['type_weaving']})";
                }
                $order = Yii::t('app','Buyurtma');
                $qty = number_format($item['quantity'],0, '.', '');
                $tir_qty = number_format($item['tir_qty'],0, '.', '');
                $musteri = ($servis==2)?$item['musteri']:'';
                $model_musteri = (!empty($item['model_musteri']))?" ({$item['model_musteri']})":'';

                $ip[$item['id']]['ip'] .= " (".$item['ne']."-".$item['thread'] . ")";
                $name = "{$item['code']} - {$item['mato_color']} <b> {$item['mato']}</b> | <b>{$item['pus_fine']}</b> | {$lth} : <b>{$item['length']}</b> | {$en} : <b>{$item['en']}</b> | {$gr} : <b>{$item['gramaj']}</b>) (<b><span style='color:red'>{$item['mushteri']}</span>{$model_musteri}</b>) - <b>{$item['pantone']}</b> - {$item['color']} {$type_weaving} {$musteri} {$ip[$item['id']]['ip']}";
                $dataEntities['list'][$item['tname']][$item['id']] = $name;
                $dataEntities['options'][$item['id']] = [
                    'data-sum' => $item['summa'],
                    'data-order_item_id' => $item['toquv_rm_order_id'],
                    'data-order_id' => $item['toquv_orders_id'],
                ];
                $dataEntities['attr'][$item['id']] = $item['mato'];
            }
        }
        return $dataEntities;
    }
    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchEntityInstruction($params){
        $q = '';
        if(!empty($params['query'])){
            $q = " AND ((ip.name LIKE '%{$params['query']}%') OR (ne.name LIKE '%{$params['query']}%') OR (thr.name LIKE '%{$params['query']}%') OR (cl.name LIKE '%{$params['query']}%') OR (t1.lot LIKE '%{$params['query']}%'))";
        }
        $isOwn = 1;
        if(isset($params['is_own']) && !empty($params['is_own'])){
            $isOwn = $params['is_own'];
        }
        if($isOwn == 2){
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname 
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD') AND is_own = %d GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=1) AND (thr.id = %d) AND (department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD')) AND (t1.is_own = %d) AND (t1.inventory > 0) AND (t1.musteri_id = %d) %s 
                    GROUP BY t1.entity_id, t1.lot LIMIT 20;";
            $sql = sprintf($sql,
                $isOwn,
                $params['thr'],
                //$params['ne'],
                $isOwn,
                $params['musteri'],
                $q);
        }else{
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname 
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD') AND is_own = %d GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=1) AND (thr.id = %d) AND (department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD')) AND (t1.is_own = %d) AND (t1.inventory > 0) %s 
                    GROUP BY t1.entity_id, t1.lot LIMIT 20;";
            $sql = sprintf($sql,
                $isOwn,
                $params['thr'],
                //$params['ne'],
                $isOwn,
                $q);
        }
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function searchEntityInstructionStatic($ne, $thr, $isOwn, $mid){
        if($isOwn == 2){
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname, t1.musteri_id musteri_id
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD') AND is_own = %d GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=1) AND (thr.id = %d) AND (ne.id = %d) AND (department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD')) AND (t1.is_own = %d) AND (t1.musteri_id = %d)
                    GROUP BY t1.entity_id, t1.lot LIMIT 1000;";
            $sql = sprintf($sql,
                $isOwn,
                $thr,
                $ne,
                $isOwn,
                $mid);
        }else{
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname, t1.musteri_id musteri_id
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD') AND is_own = 1 GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (entity_type=1) AND (thr.id = %d) AND (ne.id = %d) AND (department_id in (select id from toquv_departments where token = 'TOQUV_IP_SKLAD')) AND (t1.is_own = 1)
                    GROUP BY t1.entity_id, t1.lot LIMIT 1000;";
            $sql = sprintf($sql,
                $thr,
                $ne);
        }
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $response = [];
        $response['results'] = [];
        $response['options'] = [];
        $result = [];
        if (!empty($res)) {
            foreach ($res as $item) {
                $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
                array_push($response['results'], [
                    'id' => $item['entity_id'],
                    'text' => $name,
                ]);
                $result['options'][$item['entity_id']] = [
                    'lot' => $item['lot'],
                    'musteri_id' => $item['musteri_id'],
                ];
            }
        } else {
            $response['results'] = [
                'id' => '',
                'text' => '',
            ];
        }
        $result['list'] = ArrayHelper::map($response['results'],'id','text');
        return $result;
    }
    public static function searchEntityInstructionStaticAll($token){
        $q = '';
            $sql = "SELECT t1.id, t1.entity_id, t1.inventory AS summa, t1.lot, ip.name as ipname, ne.name as nename, thr.name as thrname, cl.name as clname, t1.musteri_id musteri_id
                    FROM toquv_item_balance t1
                    LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                    LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                    LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                    LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id  
                    JOIN (SELECT MAX(id) as id, entity_id from toquv_item_balance where department_id in (select id from toquv_departments where token = '{$token}') GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (t1.entity_type=1) AND (t1.is_own = 1)
                    GROUP BY t1.entity_id, t1.lot LIMIT 1000;";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $response = [];
        $response['results'] = [];
        $response['options'] = [];
        $result = [];
        if (!empty($res)) {
            foreach ($res as $item) {
                $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
                array_push($response['results'], [
                    'id' => $item['entity_id'],
                    'text' => $name,
                ]);
                $result['options'][$item['entity_id']] = [
                    'lot' => $item['lot'],
                    'musteri_id' => $item['musteri_id'],
                ];
            }
        } else {
            $response['results'] = [
                'id' => '',
                'text' => '',
            ];
        }
        $result['list'] = ArrayHelper::map($response['results'],'id','text');
        return $result;
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchMoving(){

        $sql = "select * from toquv_document_items as tdi
                left join toquv_documents as td ON td.id = tdi.toquv_document_id
                where td.document_type = 2";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function getAcceptedItems($id, $doc_tpe, $to_dept){

        $sql = "select ip.name as ipname,
                       tn.name as nename,
                       tt.name as thrname,
                       tic.name as clname,
                       tdi.entity_id,
                       tdi.entity_type,
                       tdi.quantity,
                       tdi.price_sum,
                       tdi.price_usd,
                       tdi.is_own,
                       tdi.package_type,
                       tdi.package_qty,
                       tdi.lot,
                       tdi.id,
                       tdi.unit_id,
                       tdi.document_qty,
                       (select tib.inventory 
                       from toquv_item_balance tib
                       left join toquv_departments tdept ON tib.department_id = tdept.id
                       where tdept.token = 'VIRTUAL_SKLAD' 
                       and tib.document_id = td.id
                       and tib.entity_id = tdi.entity_id 
                       and tib.entity_type = tdi.entity_type 
                       and tib.lot = tdi.lot order by tib.id desc limit 1) as qoldiq
            from toquv_documents as td
                     left join toquv_document_items as tdi ON td.id = tdi.toquv_document_id
                     left join toquv_ip as ip ON tdi.entity_id = ip.id
                     left join toquv_ne tn on ip.ne_id = tn.id
                     left join toquv_thread tt on ip.thread_id = tt.id
                     left join toquv_ip_color tic on ip.color_id = tic.id
                     left join toquv_departments t on td.to_department = t.id
                     left join users u on td.to_employee = u.id
            WHERE td.id = %d and td.document_type = %d AND td.to_department = %d;";

        $sql = sprintf($sql, $id, $doc_tpe, $to_dept);

        return Yii::$app->db->createCommand($sql)->queryAll();

    }

    /**
     * @param $docId
     * @param $fromDepartment
     * @return array
     * @throws \yii\db\Exception
     */
    public function getIplarFromItemBalanceTable($docId, $fromDepartment){
        $isOwn = Yii::$app->request->get('t', 1);
        $sql = "SELECT ti.id,
                       ti.lot,
                       ip.name   as ipname,
                       ne.name   as nename,
                       thr.name  as thrname,
                       cl.name   as clname,
                       ti.quantity,
                       (SELECT inventory
                        FROM toquv_item_balance tib
                        WHERE tib.entity_id = ti.entity_id
                          AND ti.entity_type = 1
                          AND tib.department_id = %d
                          AND tib.lot = ti.lot
                          AND tib.is_own = %d
                        ORDER BY id DESC
                        LIMIT 1) as inventory,
                       (SELECT inventory
                        FROM toquv_item_balance tibq
                        left join toquv_departments td on tibq.department_id = td.id
                        where tibq.entity_id = ti.entity_id
                          AND tibq.entity_type = 1
                          AND tibq.document_id = %d
                          AND td.token = 'VIRTUAL_SKLAD'
                          AND tibq.lot = ti.lot
                        ORDER BY tibq.id DESC
                        LIMIT 1) as diff
                FROM toquv_document_items ti
                         LEFT JOIN toquv_ip ip ON ti.entity_id = ip.id
                         LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id
                         LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id
                         LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id
                WHERE ti.toquv_document_id = %d
                  AND ti.entity_type = 1
                LIMIT 10000";
        $sql = sprintf($sql,
            (int)$fromDepartment,
            $isOwn,
            (int)$docId,
            (int)$docId
        );
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function getIplarFromItemBalanceOne($docId, $itemId, $fromDepartment){
        $isOwn = Yii::$app->request->get('t', 1);
        $sql = "SELECT ti.id,
                       ti.lot,
                       ip.name   as ipname,
                       ne.name   as nename,
                       thr.name  as thrname,
                       cl.name   as clname,
                       ti.quantity,
                       (SELECT inventory
                        FROM toquv_item_balance tib
                        WHERE tib.entity_id = ti.entity_id
                          AND ti.entity_type = 1
                          AND tib.department_id = %d
                          AND tib.lot = ti.lot
                          AND tib.is_own = %d
                        ORDER BY id DESC
                        LIMIT 1) as inventory,
                       (SELECT inventory
                        FROM toquv_item_balance tibq
                        left join toquv_departments td on tibq.department_id = td.id
                        where tibq.entity_id = ti.entity_id
                          AND tibq.entity_type = 1
                          AND tibq.document_id = %d
                          AND td.token = 'VIRTUAL_SKLAD'
                          AND tibq.lot = ti.lot
                        ORDER BY tibq.id DESC
                        LIMIT 1) as diff
                FROM toquv_document_items ti
                         LEFT JOIN toquv_ip ip ON ti.entity_id = ip.id
                         LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id
                         LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id
                         LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id
                WHERE ti.id = %d
                  AND ti.entity_type = 1
                LIMIT 10000";
        $sql = sprintf($sql,
            (int)$fromDepartment,
            $isOwn,
            (int)$docId,
            (int)$itemId
        );
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public function getMatoRemain()
    {
        $sql = "SELECT 
                    tdi.id, 
                    CONCAT(trm.name,' - ',tpf.name) mato,
                    tor.document_number doc_number,
                    tdi.quantity qty, 
                    tro.id tro_id,
                    summa, 
                    (summa - tdi.quantity) qoldiq
                FROM toquv_document_items tdi
                LEFT JOIN toquv_documents td ON tdi.toquv_document_id = td.id
                LEFT JOIN toquv_doc_items_rel_order tdiro ON tdi.id = tdiro.toquv_document_items_id
                ##LEFT JOIN toquv_rm_order tro ON tdiro.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_instruction_rm tir ON tdi.entity_id = tir.id
                LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                LEFT JOIN toquv_pus_fine tpf ON tir.toquv_pus_fine_id = tpf.id
                LEFT JOIN toquv_orders tor ON tdiro.toquv_orders_id = tor.id
                LEFT JOIN (SELECT sum(tk.quantity) summa, tk.toquv_rm_order_id 
                FROM toquv_kalite tk LEFT JOIN toquv_rm_order t ON tk.toquv_rm_order_id = t.id GROUP BY tk.toquv_rm_order_id) 
                    tk ON tro.id = tk.toquv_rm_order_id
                WHERE (tdi.entity_type = %d) AND td.id = %d";
        $mato = ToquvDocuments::ENTITY_TYPE_MATO;
        $sql = sprintf($sql,$mato,$this->id);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * @param null $doc_id
     * @param int $dept
     * @param int $entity_type
     * @return array
     * @throws \yii\db\Exception
     */
    public function getMatoInfo($doc_id = null, $dept, $entity_type = 2){

        $sql = "select trm.name          as mato,
                        trmc.name as mato_color,
                       tpf.name          as pus_fine,
                       tir.finish_en     as en,
                       tir.finish_gramaj as gr,
                       tir.thread_length as lth,
                        sn.name sort,
                       CONCAT(tir.thread_length,'-',tir.finish_en,'-',tir.finish_gramaj) info,
                       (select inventory
                        from toquv_mato_item_balance tib2
                        where tib2.entity_id = tir.id
                          AND tib2.lot = tdi.lot
                          AND tib2.entity_type = :etype
                          AND tib2.department_id = :dept
                        ORDER BY tib2.id DESC
                        limit 1)         as remain,
                        (select roll_inventory
                        from toquv_mato_item_balance tib2
                        where tib2.entity_id = tir.id
                          AND tib2.lot = tdi.lot
                          AND tib2.entity_type = :etype
                          AND tib2.department_id = :dept
                        ORDER BY tib2.id DESC
                        limit 1)         as remain_roll,
                        (select quantity_inventory
                        from toquv_mato_item_balance tib2
                        where tib2.entity_id = tir.id
                          AND tib2.lot = tdi.lot
                          AND tib2.entity_type = :etype
                          AND tib2.department_id = :dept
                        ORDER BY tib2.id DESC
                        limit 1)         as remain_count,
                        IF(m2.name != '', CONCAT(m.name, '(', m2.name, ')'), m.name) ka,
                       td.reg_date,
                       tdi.quantity,
                        tdi.count,
                        tdi.roll_count,
                        tdi.add_info,
                        tdi.price_sum,
                        tdi.price_usd,
                        tor.order_type order_type,
                        cp.code c_pantone,cp.name c_name,cp.r,cp.g,cp.b,c.pantone b_pantone,c.color_id,c.name b_name,c.color b_color
                from toquv_documents td
                         left join toquv_document_items tdi on td.id = tdi.toquv_document_id
                         left join mato_info tir on tir.id = tdi.entity_id
                         left join toquv_rm_order tro on tir.toquv_rm_order_id = tro.id
                         left join toquv_orders tor on tro.toquv_orders_id = tor.id
                         LEFT JOIN color c on tro.color_id = c.id
                         LEFT JOIN color_pantone cp on tro.color_pantone_id = cp.id
                         left join musteri m on tir.musteri_id = m.id  
                         left join musteri m2 on tor.model_musteri_id = m2.id  
                         left join toquv_raw_materials trm on tir.entity_id = trm.id
                         left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                         left join toquv_pus_fine tpf on tir.pus_fine_id = tpf.id
                         left join sort_name sn ON sn.id = tdi.lot
                where td.id = :id AND tdi.entity_type = :etype;";
        $res = Yii::$app->db->createCommand($sql)->bindValues(['etype' => $entity_type,'dept' => $dept, 'id' => $doc_id])->queryAll();
        return $res;
    }
}
