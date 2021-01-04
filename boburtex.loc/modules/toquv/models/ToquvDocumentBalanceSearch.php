<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * ToquvDocumentBalanceSearch represents the model behind the search form of `app\modules\toquv\models\ToquvItemBalance`.
 *
 * @property array $belongToDepartments
 */
class ToquvDocumentBalanceSearch extends ToquvItemBalance
{
    public $cp = [];

    public  $lot_with_comma;

    public $from_date;

    public $to_date;

    public $entity_ids;

    public $mato_id;

    public $group_by_ip;

    public $department_id;

    public $from_model;

    public $musteri_id;
    public $model_musteri_id;

    public $model_id;

    public $orders_id;

    public $moi_id;

    public $sort_id;

    public $ne_id;

    public $thread_id;

    public $color_id;

    public $pus_fine;
    public $date;
    public $thread_length;
    public $finish_en;
    public $finish_gramaj;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['department_id', 'required'],
            [['id', 'from_model', 'musteri_id', 'model_musteri_id', 'model_id', 'orders_id', 'moi_id', 'entity_id', 'mato_id', 'entity_type', 'department_id', 'is_own', 'document_id', 'document_type', 'version', 'created_by', 'status', 'updated_at', 'sort_id', 'thread_id', 'ne_id', 'color_id', 'group_by_ip',], 'integer'],
            [['count', 'inventory', 'price_uzs', 'price_usd', 'sold_price_uzs', 'sold_price_usd', 'sum_uzs', 'sum_usd', 'price_rub', 'sold_price_rub', 'price_eur', 'sold_price_eur'], 'number'],
            [['thread_length', 'finish_en', 'finish_gramaj'],'string'],
            [['reg_date', 'comment','lot_with_comma', 'lot','from_date','to_date','pus_fine','created_at','entity_ids'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'from_date' => Yii::t('app', 'Boshlanish sana'),
            'to_date' => Yii::t('app', 'Tugash Sana'),
            'group_by_ip' => Yii::t('app', "Ip bo'yicha guruhlash"),
            'entity_ids' => Yii::t('app', 'Ip nomlarini tanlash'),
            'lot_with_comma' => Yii::t('app', 'Lot raqamlar'),
            'department_id' => Yii::t('app', 'Department ID'),
            'is_own' => Yii::t('app', "Maxsulot bizniki/Mijozniki"),
            'musteri_id' => Yii::t('app', 'Buyurtmachi'),
            'model_musteri_id' => Yii::t('app', 'Model buyurtmachisi'),
            'model_id' => Yii::t('app', 'Model'),
            'orders_id' => Yii::t('app', 'Buyurtma'),
            'moi_id' => Yii::t('app', 'Buyurtma miqdori'),
            'sort_id' => Yii::t('app', 'Sort'),
            'ne_id' => Yii::t('app', 'Ne ID'),
            'thread_id' => Yii::t('app', 'Thread ID'),
            'color_id' => Yii::t('app', 'Color ID'),
            ];
    }


    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function search($params)
    {
        //$lastMonthBegin = date("Y-m-d", strtotime("first day of previous month"));
        //$lastMonthEnd   = date("Y-n-d", strtotime("last day of previous month"));

        $this->load($params);

        $isOwn = '';
        $lots = '';
        $entityIds = '';
        $ne = '';
        $thread = '';
        $color = '';
       $trimmed = trim($this->lot_with_comma);
        if(!empty($trimmed)){
            $trim = trim($this->lot_with_comma);
            $lots = " AND (t1.lot IN ({$trim}))";
        }
        if(!empty($this->is_own)){
            $isOwn = " AND (is_own = {$this->is_own})";
        }
        if(!empty($this->entity_ids)){
            $entityIds = ' AND (entity_id IN ('.implode(',', $this->entity_ids).'))';
        }
        if(!empty($this->thread_id)){
            $thread = ' AND (thr.id IN ('.implode(',', $this->thread_id).'))';
        }
        if(!empty($this->ne_id)){
            $ne = ' AND (ne.id IN ('.implode(',', $this->ne_id).'))';
        }
        if(!empty($this->color_id)){
            $color = ' AND (cl.id IN ('.implode(',', $this->color_id).'))';
        }

        if($this->group_by_ip == 1){

            $sql = "SELECT t1.id, ip.name  as ip,
                       ne.name  as ne,
                       thr.name as thread,
                       cl.name  as color,
                       t1.price_uzs,
                       t1.lot,
                       t1.price_usd,
                       SUM(t1.inventory) as summa
                    from toquv_item_balance as t1
                         LEFT JOIN toquv_ip as ip ON ip.id = t1.entity_id
                         LEFT JOIN toquv_ne as ne ON ip.ne_id = ne.id
                         LEFT JOIN toquv_thread as thr ON ip.thread_id = thr.id
                         LEFT JOIN toquv_ip_color as cl ON ip.color_id = cl.id
                    WHERE (reg_date BETWEEN '%s' AND '%s')
                    AND (entity_type = %d)
                    AND (department_id = %d)
                    AND (t1.inventory > 0)
                    AND t1.id IN (SELECT MAX(id) as id
                                from toquv_item_balance
                                WHERE department_id = %d
                                  AND (entity_type = %d)
                                %s %s %s %s %s %s 
                                GROUP BY entity_id, lot)
                    group by t1.entity_id
                    ORDER BY ip.name
                    LIMIT 100000";

            $sql = sprintf($sql,
                date('Y-m-d', strtotime($this->from_date)),
                date('Y-m-d', strtotime($this->to_date)),
                $this->entity_type,
                (int)$this->department_id,
                (int)$this->department_id,
                $this->entity_type,
                $isOwn,
                $lots,
                $entityIds,
                $thread,
                $ne,
                $color);

            return Yii::$app->db->createCommand($sql)->queryAll();
        }else{
            $sql = "SELECT t1.id, ip.name, price_uzs, price_usd, ip.name AS ip, cl.name AS color, thr.name AS thread, ne.name AS ne, entity_id, lot, reg_date, t1.inventory AS summa FROM toquv_item_balance t1 
                     LEFT JOIN toquv_ip ip ON t1.entity_id = ip.id
                     LEFT JOIN toquv_ne ne ON ip.ne_id = ne.id 
                     LEFT JOIN toquv_thread thr ON ip.thread_id = thr.id 
                     LEFT JOIN toquv_ip_color cl ON ip.color_id = cl.id 
                     JOIN (SELECT MAX(id) as id from toquv_item_balance WHERE department_id=%d %s GROUP BY entity_id, lot ORDER BY id ASC) as t2 ON t1.id = t2.id
                    WHERE (reg_date BETWEEN '%s' AND '%s') AND (entity_type=%d) AND (department_id=%d) AND (inventory > 0) %s %s %s %s %s %s 
                    GROUP BY t1.entity_id, t1.lot ORDER BY ip.name LIMIT 1000";

            $sql = sprintf($sql,
                (int)$this->department_id,
                $isOwn,
                date('Y-m-d', strtotime($this->from_date)),
                date('Y-m-d', strtotime($this->to_date)),
                $this->entity_type,
                (int)$this->department_id,
                $isOwn,
                $lots,
                $entityIds,
                $thread,
                $ne,
                $color
            );
            return Yii::$app->db->createCommand($sql)->queryAll();
        }
    }

    public function searchMato($params,$type=null,$order_type=1)
    {
        $this->load($params);
        $order_is_null = ($order_type==1)?"|| tro.order_type IS NULL":"";
        $entityIds = '';
        $select = '';
        if(!empty($this->entity_ids)){
            $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
        }
        $musteri = '';
        if(!empty($this->musteri_id)){
            $musteri = (!is_array($this->musteri_id))?" AND m.id = {$this->musteri_id}":" AND m.id IN (".implode(',', $this->musteri_id).")";
        }
        $model_musteri = '';
        if(!empty($this->model_musteri_id)){
            $model_musteri = " AND (tor.model_musteri_id = {$this->model_musteri_id})";
        }
        $department = '';
        if(!empty($this->department_id)){
            $department = " AND (department_id = {$this->department_id})";
        }
        $sort = '';
        if(!empty($this->sort_id)){
            $sort = ' AND (lot IN ('.implode(',', $this->sort_id).'))';
        }
        $left_join = '';
        $from_model = "AND (tro.moi_id IS NULL) AND (tro.order_type = {$order_type} {$order_is_null})";
        $filter = '';
        $pus_fine = '';
        $thread = '';
        $finish_en = '';
        $finish_gramaj = '';
        if(!empty($this->pus_fine)){
            $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
        }
        if(!empty($this->thread_length)){
            $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
        }
        if(!empty($this->finish_en)){
            $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
        }
        if(!empty($this->finish_gramaj)){
            $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
        }
        if(!empty($this->from_model)){
            $samo_type = ToquvOrders::ORDER_SAMO;
            $from_model = " AND ((tro.moi_id IS NOT NULL) OR tro.order_type = {$samo_type})";
            $model_id = '';
            $orders_id = '';
            $moi_id = '';
            if(!empty($this->model_id)){
                $model_id = "AND (ml.id = {$this->model_id})";
            }
            if(!empty($this->orders_id)){
                $orders_id = "AND (mo.id = {$this->orders_id})";
            }
            if(!empty($this->moi_id)){
                $moi_id = "AND (moi.id = {$this->moi_id})";
            }
            $filter = $model_id.$orders_id.$moi_id;
            $left_join = 'LEFT JOIN
                            model_orders_items moi ON tro.moi_id = moi.id
                        LEFT JOIN
                            model_orders mo ON moi.model_orders_id = mo.id
                        LEFT JOIN
                            models_list ml ON moi.models_list_id = ml.id
                        LEFT JOIN
                            ( SELECT
                                    moi.id moi_id,
                                    cp.code cp_code,
                                    st.name size_type,
                                    mois2.summa
                                FROM
                                    model_orders_items moi
                                LEFT JOIN
                                    model_orders mo ON moi.model_orders_id = mo.id
                                LEFT JOIN
                                    models_variations mv ON moi.model_var_id = mv.id
                                INNER JOIN
                                    models_variation_colors mvc ON mv.id = mvc.model_var_id
                                LEFT JOIN
                                    color_pantone cp ON mvc.color_pantone_id = cp.id
                                LEFT JOIN
                                    musteri m ON mo.musteri_id = m.id
                                LEFT JOIN
                                    models_list ml ON moi.models_list_id = ml.id
                                LEFT JOIN
                                    model_orders_items_size mois ON moi.id = mois.model_orders_items_id
                                LEFT JOIN
                                    ( SELECT
                                            model_orders_items_id,
                                            SUM(count) summa
                                        FROM
                                            model_orders_items_size mois3
                                        LEFT JOIN
                                            size s2 ON mois3.size_id = s2.id
                                        GROUP BY
                                            mois3.model_orders_items_id
                                        ORDER BY
                                            NULL
                                    ) mois2 ON moi.id = mois2.model_orders_items_id
                                LEFT JOIN
                                    size s ON mois.size_id = s.id
                                LEFT JOIN
                                    size_type st ON s.size_type_id = st.id
                                WHERE
                                    ( mvc.is_main = 1 )
                                    AND (
                                        mois.id = (
                                            SELECT
                                                MAX(mois.id)
                                            FROM
                                                model_orders_items_size mois
                                            WHERE
                                                mois.model_orders_items_id = moi.id
                                        )
                                    )
                            ) AS moi2 ON moi2.moi_id = moi.id
                            LEFT JOIN
                                musteri m2 ON mo.musteri_id = m2.id';
            $select = ",CONCAT(mo.doc_number,
                               ' - ',
                               ml.name,
                               ' - ',
                               moi2.cp_code,
                               ' - ',
                               moi2.size_type,
                               ' - ',
                               moi2.summa) model,
                               m2.name order_musteri";
        }
        $groupBy = (!empty($this->group_by_ip))?"t1.id,tir.id,tro.id,":"";
        $m_select = (!empty($this->group_by_ip))?",tro.id tro_id,tro.moi_id,tro.quantity order_quantity,cp.code c_pantone,cp.name c_name,cp.r,cp.g,cp.b,c.pantone b_pantone,c.color_id,c.name b_name,c.color b_color":",SUM(tro.quantity) order_quantity";
        $m_join = (!empty($this->group_by_ip))?"LEFT JOIN color_pantone cp ON tro.color_pantone_id = cp.id LEFT JOIN color c ON tro.color_id = c.id":"";
        /*$sklad = ToquvDepartments::find()->where(['token'=>'TOQUV_MATO_SKLAD'])->one()['id'];
        $type = ToquvDocuments::DOC_TYPE_MOVING;*/
        $entity_type = (!$type)?ToquvDocuments::ENTITY_TYPE_MATO:$type;
        $sql = "SELECT
                    t1.id item_id,
                    trm.name mato,
                    type.name type,
                    sum(t1.inventory) qoldiq,
                    sum(t1.roll_inventory) roll,
                    sum(t1.quantity_inventory) soni,
                    tpf.name pus_fine,
                    sn.name sort,
                    CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                    IF(m3.name != '', CONCAT(m.name, '(<b>', m3.name, '</b>)'), m.name) musteri %s %s
                FROM
                    toquv_mato_item_balance t1
                INNER JOIN
                    mato_info tir ON t1.entity_id = tir.id
                LEFT JOIN
                    toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN
                    toquv_orders tor ON tro.toquv_orders_id = tor.id
                LEFT JOIN
                    toquv_raw_materials trm ON tir.entity_id = trm.id
                LEFT JOIN
                    raw_material_type as type                    
                        ON trm.raw_material_type_id = type.id
                LEFT JOIN
                    toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                LEFT JOIN sort_name sn ON sn.id = t1.lot
                LEFT JOIN musteri m ON tir.musteri_id = m.id
                LEFT JOIN musteri m3 ON tor.model_musteri_id = m3.id
                %s
                %s
                JOIN
                    ( SELECT
                            MAX(toquv_mato_item_balance.id) AS id,
                            SUM(toquv_mato_item_balance.count) AS total
                        FROM toquv_mato_item_balance
                        LEFT JOIN mato_info tir ON tir.id = toquv_mato_item_balance.entity_id
                        LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                        WHERE toquv_mato_item_balance.entity_type = {$entity_type} %s %s %s
                        GROUP BY toquv_mato_item_balance.entity_id, toquv_mato_item_balance.lot, tir.id
                        ORDER BY id ASC
                    ) AS t2 ON t1.id = t2.id
                WHERE
                    ( t1.reg_date BETWEEN '%s' AND '%s' )
                  AND ( t1.entity_type = {$entity_type} )
                  %s
                  %s
                  AND ( t1.inventory > 0 )
                    %s %s %s %s
                    %s                 
                    %s                 
                    %s                 
                    %s                 
                    %s 
                GROUP BY %s trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj,tir.musteri_id,tir.model_musteri_id
                ORDER BY m.name,m3.name,trm.name LIMIT 1000";

        $sql = sprintf($sql,
            $select,
            $m_select,
            $m_join,
            $left_join,
            $department,
            $from_model,
            $sort,
            $this->from_date,
            $this->to_date,
            $department,
            $sort,
            $entityIds,
            $from_model,
            $musteri,
            $model_musteri,
            $pus_fine,
            $thread,
            $finish_en,
            $finish_gramaj,
            $filter,
            $groupBy);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchAksessuar($params,$type=null)
    {
        $this->load($params);
        $select = '';
        $entityIds = '';
        if(!empty($this->entity_ids)){
            $entityIds = ' AND (trm.id IN ('.implode(',', $this->entity_ids).'))';
        }
        $musteri = '';
        if(!empty($this->musteri_id)){
            $musteri = (!is_array($this->musteri_id))?" AND m.id = {$this->musteri_id}":" AND m.id IN (".implode(',', $this->musteri_id).")";
        }
        $model_musteri = '';
        if(!empty($this->model_musteri_id)){
            $model_musteri = " AND (tor.model_musteri_id = {$this->model_musteri_id})";
        }
        $department = '';
        if(!empty($this->department_id)){
            $department = " AND (department_id = {$this->department_id})";
        }
        $sort = '';
        if(!empty($this->sort_id)){
            $sort = ' AND (lot IN ('.implode(',', $this->sort_id).'))';
        }
        $left_join = '';
        $from_model = "AND (tro.moi_id IS NULL)";
        $filter = '';
        $pus_fine = '';
        $thread = '';
        $finish_en = '';
        $finish_gramaj = '';
        if(!empty($this->pus_fine)){
            $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
        }
        if(!empty($this->thread_length)){
            $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
        }
        if(!empty($this->finish_en)){
            $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
        }
        if(!empty($this->finish_gramaj)){
            $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
        }
        if(!empty($this->from_model)){
            $from_model = " AND (tro.moi_id IS NOT NULL)";
            $model_id = '';
            $orders_id = '';
            $moi_id = '';
            if(!empty($this->model_id)){
                $model_id = "AND (ml.id = {$this->model_id})";
            }
            if(!empty($this->orders_id)){
                $orders_id = "AND (mo.id = {$this->orders_id})";
            }
            if(!empty($this->moi_id)){
                $moi_id = "AND (moi.id = {$this->moi_id})";
            }
            $filter = $model_id.$orders_id.$moi_id;
            $left_join = 'LEFT JOIN
                            model_orders_items moi ON tro.moi_id = moi.id
                        LEFT JOIN
                            model_orders mo ON moi.model_orders_id = mo.id
                        LEFT JOIN
                            models_list ml ON moi.models_list_id = ml.id
                        LEFT JOIN
                            ( SELECT
                                    moi.id moi_id,
                                    cp.code cp_code,
                                    st.name size_type,
                                    mois2.summa
                                FROM
                                    model_orders_items moi
                                LEFT JOIN
                                    model_orders mo ON moi.model_orders_id = mo.id
                                LEFT JOIN
                                    models_variations mv ON moi.model_var_id = mv.id
                                INNER JOIN
                                    models_variation_colors mvc ON mv.id = mvc.model_var_id
                                LEFT JOIN
                                    color_pantone cp ON mvc.color_pantone_id = cp.id
                                LEFT JOIN
                                    musteri m ON mo.musteri_id = m.id
                                LEFT JOIN
                                    models_list ml ON moi.models_list_id = ml.id
                                LEFT JOIN
                                    model_orders_items_size mois ON moi.id = mois.model_orders_items_id
                                LEFT JOIN
                                    ( SELECT
                                            model_orders_items_id,
                                            SUM(count) summa
                                        FROM
                                            model_orders_items_size mois3
                                        LEFT JOIN
                                            size s2 ON mois3.size_id = s2.id
                                        GROUP BY
                                            mois3.model_orders_items_id
                                        ORDER BY
                                            NULL
                                    ) mois2 ON moi.id = mois2.model_orders_items_id
                                LEFT JOIN
                                    size s ON mois.size_id = s.id
                                LEFT JOIN
                                    size_type st ON s.size_type_id = st.id
                                WHERE
                                    ( mvc.is_main = 1 )
                                    AND (
                                        mois.id = (
                                            SELECT
                                                MAX(mois.id)
                                            FROM
                                                model_orders_items_size mois
                                            WHERE
                                                mois.model_orders_items_id = moi.id
                                        )
                                    )
                            ) AS moi2 ON moi2.moi_id = moi.id
                            LEFT JOIN
                                musteri m2 ON mo.musteri_id = m2.id';
            $select = ",CONCAT(mo.doc_number,
                               ' - ',
                               ml.name,
                               ' - ',
                               moi2.cp_code,
                               ' - ',
                               moi2.size_type,
                               ' - ',
                               moi2.summa) model,
                               m2.name order_musteri";
        }
        $groupBy = (!empty($this->group_by_ip))?"t1.id,tir.id,tro.id,":"";
        $m_select = (!empty($this->group_by_ip))?",tro.quantity order_quantity,cp.code c_pantone,cp.name c_name,cp.r,cp.g,cp.b,c.pantone b_pantone,c.color_id,c.name b_name,c.color b_color":",SUM(tro.quantity) order_quantity";
        $m_join = (!empty($this->group_by_ip))?"LEFT JOIN color_pantone cp ON tro.color_pantone_id = cp.id LEFT JOIN color c ON tro.color_id = c.id":"";
        /*$sklad = ToquvDepartments::find()->where(['token'=>'TOQUV_MATO_SKLAD'])->one()['id'];
        $type = ToquvDocuments::DOC_TYPE_MOVING;*/
        $entity_type = (!$type)?ToquvDocuments::ENTITY_TYPE_MATO:$type;
        $sql = "SELECT
                    t1.id item_id,
                    trm.name mato,
                    trmc.name as mato_color,
                    type.name type,
                    sum(t1.inventory) qoldiq,
                    sum(t1.roll_inventory) roll,
                    sum(t1.quantity_inventory) soni,
                    tpf.name pus_fine,
                    sn.name sort,
                    CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                    IF(m3.name != '', CONCAT(m.name, '(<b>', m3.name, '</b>)'), m.name) musteri %s %s
                FROM
                    toquv_mato_item_balance t1
                INNER JOIN
                    mato_info tir ON t1.entity_id = tir.id
                LEFT JOIN
                    toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN
                    toquv_orders tor ON tro.toquv_orders_id = tor.id
                LEFT JOIN
                    toquv_raw_materials trm ON tir.entity_id = trm.id
                left join toquv_raw_material_color trmc ON trm.color_id = trmc.id
                LEFT JOIN
                    raw_material_type as type                    
                        ON trm.raw_material_type_id = type.id
                LEFT JOIN
                    toquv_pus_fine tpf ON tir.pus_fine_id = tpf.id
                LEFT JOIN sort_name sn ON sn.id = t1.lot
                LEFT JOIN musteri m ON tir.musteri_id = m.id
                LEFT JOIN musteri m3 ON tor.model_musteri_id = m3.id
                %s
                %s
                JOIN
                    ( SELECT
                            MAX(toquv_mato_item_balance.id) AS id,
                            SUM(toquv_mato_item_balance.count) AS total
                        FROM toquv_mato_item_balance
                        LEFT JOIN mato_info tir ON tir.id = toquv_mato_item_balance.entity_id
                        LEFT JOIN toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                        WHERE toquv_mato_item_balance.entity_type = {$entity_type} %s %s %s
                        GROUP BY toquv_mato_item_balance.entity_id, toquv_mato_item_balance.lot, tir.id
                        ORDER BY id ASC
                    ) AS t2 ON t1.id = t2.id
                WHERE
                    ( t1.reg_date BETWEEN '%s' AND '%s' )
                  AND ( t1.entity_type = {$entity_type} )
                  %s
                  %s
                  AND ( t1.inventory > 0 )
                    %s %s %s %s
                    %s                 
                    %s                 
                    %s                 
                    %s                 
                    %s 
                GROUP BY %s trm.id,m.id,sn.id,tpf.id,tir.thread_length,tir.finish_en,tir.finish_gramaj
                ORDER BY m.name,m3.name,trm.name LIMIT 1000";

        $sql = sprintf($sql,
            $select,
            $m_select,
            $m_join,
            $left_join,
            $department,
            $from_model,
            $sort,
            $this->from_date,
            $this->to_date,
            $department,
            $sort,
            $entityIds,
            $from_model,
            $musteri,
            $model_musteri,
            $pus_fine,
            $thread,
            $finish_en,
            $finish_gramaj,
            $filter,
            $groupBy);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public function searchMatoIp($params,$type=null)
    {
        $this->load($params);
        $entityIds = '';
        if(!empty($this->entity_ids)){
            $entityIds = ' AND (tii.entity_id IN ('.implode(',', $this->entity_ids).'))';
        }
        $mato = '';
        if(!empty($this->mato_id)){
            $mato = ' AND (trm.id IN ('.implode(',', $this->mato_id).'))';
        }
        $musteri = '';
        if(!empty($this->musteri_id)){
            $musteri = (!is_array($this->musteri_id))?" AND m.id = {$this->musteri_id}":" AND m.id IN (".implode(',', $this->musteri_id).")";
        }
        $model_musteri = '';
        if(!empty($this->model_musteri_id)){
            $model_musteri = " AND (tor.model_musteri_id = {$this->model_musteri_id})";
        }
        $pus_fine = '';
        $thread = '';
        $finish_en = '';
        $finish_gramaj = '';
        if(!empty($this->pus_fine)){
            $pus_fine = ' AND (tpf.id IN ('.implode(',', $this->pus_fine).'))';
        }
        if(!empty($this->thread_length)){
            $thread = ' AND (tir.thread_length = '.$this->thread_length.')';
        }
        if(!empty($this->finish_en)){
            $finish_en = ' AND (tir.finish_en = '.$this->finish_en.')';
        }
        if(!empty($this->finish_gramaj)){
            $finish_gramaj = ' AND (tir.finish_gramaj = '.$this->finish_gramaj.')';
        }
        $from = "";
        if(!empty($this->from_date)){
            $from_date = strtotime($this->from_date);
            $from = " AND ( tir.created_at > {$from_date})";
        }
        $to = "";
        if(!empty($this->to_date)){
            $to_date = strtotime($this->to_date);
            $to = " AND ( tir.created_at < {$to_date})";
        }
        $entity_type = (!$type)?ToquvRawMaterials::MATO:$type;
        $sql = "SELECT
                    CONCAT(ip.name, ' - ',tn.name, ' - ',tt.name, ' - ',tic.name) ip,
                    trm.name mato,
                    type.name type,
                    tpf.name pus_fine,
                    CONCAT(tir.thread_length,' | ',tir.finish_en,' | ',tir.finish_gramaj) info,
                    IF(m3.name != '', CONCAT(m.name, '(<b>', m3.name, '</b>)'), m.name) musteri,
                    tro.quantity order_quantity,
                    tii.quantity tir_ip_quantity,
                    IF(troi.own_quantity > 0, troi.own_quantity, troi.their_quantity) order_ip_quantity,
                    SUM(tk.quantity) kalite_quantity
                FROM
                    toquv_instruction_items tii
                LEFT JOIN toquv_rm_order_items troi ON tii.rm_item_id = troi.id
                LEFT JOIN toquv_instruction_rm tir ON tii.toquv_instruction_rm_id = tir.id
                LEFT JOIN
                    toquv_rm_order tro ON tir.toquv_rm_order_id = tro.id
                LEFT JOIN
                    toquv_orders tor ON tro.toquv_orders_id = tor.id
                LEFT JOIN
                    toquv_raw_materials trm ON tro.toquv_raw_materials_id = trm.id
                LEFT JOIN
                    raw_material_type as type                    
                        ON trm.raw_material_type_id = type.id
                LEFT JOIN
                    toquv_pus_fine tpf ON tir.toquv_pus_fine_id = tpf.id
                LEFT JOIN musteri m ON tor.musteri_id = m.id
                LEFT JOIN musteri m3 ON tor.model_musteri_id = m3.id
                LEFT JOIN toquv_ip ip ON tii.entity_id = ip.id
                LEFT JOIN toquv_thread tt on ip.thread_id = tt.id
                LEFT JOIN toquv_ne tn on ip.ne_id = tn.id
                LEFT JOIN toquv_ip_color tic on ip.color_id = tic.id
                LEFT JOIN toquv_kalite tk ON tir.id = tk.toquv_instruction_rm_id
                WHERE
                      trm.type = %d
                  %s %s %s %s %s %s %s %s %s %s
                GROUP BY tir.id, tii.id
                ORDER BY m.name,m3.name,trm.name LIMIT 200";

        $sql = sprintf($sql,
            $entity_type,
            $from,
            $to,
            $entityIds,
            $mato,
            $musteri,
            $model_musteri,
            $pus_fine,
            $thread,
            $finish_en,
            $finish_gramaj);
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    /**
     * @return array
     * @throws \yii\db\Exception
     */ 
    public function getBelongToDepartments(){
        $sql = "select td.id,
                       td.name
                from toquv_departments td where td.id 
                IN (select tud.department_id from toquv_user_department tud where tud.user_id = %d);";
        $sql = sprintf($sql, Yii::$app->user->id);
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($result,'id','name');
    }
}
