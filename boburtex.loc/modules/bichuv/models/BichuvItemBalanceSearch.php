<?php

namespace app\modules\bichuv\models;

use app\modules\base\models\ModelOrders;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * BichuvItemBalanceSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvItemBalance`.
 */
class BichuvItemBalanceSearch extends BichuvItemBalance
{
    public $from_date;
    public $to_date;
    public $entity_ids;
    public $accs_properties;
    public $department_id;
    public $to_department;
    public $sort;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'entity_type', 'to_department','department_id', 'is_own', 'document_id', 'document_type', 'version', 'status', 'created_by', 'created_at', 'updated_at','sort'], 'integer'],
            [['lot', 'reg_date', 'comment', 'from_date', 'to_date', 'entity_ids', 'accs_properties'], 'safe'],
            [['count', 'inventory', 'price_uzs', 'price_usd', 'price_rub', 'price_eur', 'sold_price_uzs', 'sold_price_usd', 'sold_price_rub', 'sold_price_eur', 'sum_uzs', 'sum_usd', 'sum_rub', 'sum_eur'], 'number'],
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
     * @return array
     */
    public function attributeLabels()
    {
        return [
                'department_id' => Yii::t('app', 'Qaysi bo\'limdan'),
                'to_department' => Yii::t('app', 'Qaysi bo\'limga'),
            ];
    }

    /**
     * @param $params
     * @param $docType
     * @return array
     * @throws \yii\db\Exception
     */
    public function search($params, $docType)
    {
        $this->load($params);

        $entityIds = '';
        $accsProperties = '';

        if(!empty($this->entity_ids)){
            $entityIds = ' AND (entity_id IN ('.implode(',', $this->entity_ids).'))';
        }

        if (!empty($this->accs_properties)) {
            $accsProperties = ' AND (accs.property_id IN ('.implode(',', $this->accs_properties).'))';
        }
        switch ($this->sort){
            case 1:
                $sort = "limit_count ASC,u.name ASC";
                break;
            case 2:
                $sort = "accs.name ASC";
                break;
            case 3:
                $sort = "p.name ASC";
                break;
            default:
                $sort = "limit_count ASC,u.name ASC";
                break;
        }
        switch ($docType){
             case BichuvDoc::DOC_TYPE_INCOMING:
                 $sql = "SELECT t1.id,accs.stock_limit_min min_limit, price_uzs, price_usd, accs.name AS accs,accs.sku, reg_date,
                            t1.inventory AS summa, p.name as property, u.name as unit,
                            (t1.inventory - accs.stock_limit_min) limit_count
                        
                        FROM bichuv_item_balance t1 
                             LEFT JOIN bichuv_acs accs ON t1.entity_id = accs.id
                             LEFT JOIN bichuv_acs_property p ON accs.property_id = p.id
                             LEFT JOIN unit u ON accs.unit_id = u.id
                             JOIN (SELECT MAX(id) as id from bichuv_item_balance WHERE department_id=%d GROUP BY entity_id) as t2 ON t1.id = t2.id
                            WHERE (reg_date BETWEEN '%s' AND '%s') AND (entity_type=%d) AND (department_id=%d) AND (inventory > 0) %s %s 
                            GROUP BY t1.entity_id  ORDER BY {$sort} LIMIT 100000";

                 $sql = sprintf($sql,
                     $this->department_id,
                     date('Y-m-d', strtotime($this->from_date)),
                     date('Y-m-d', strtotime($this->to_date)),
                     $this->entity_type,
                     $this->department_id,
                     $entityIds,
                     $accsProperties);
                 break;

            case BichuvDoc::DOC_TYPE_MOVING:
                $to_dept = "";
                if(!empty($this->to_department)){
                    $to_dept = " AND (bd.to_hr_department = {$this->to_department})";
                }
                $sql = "select p.value as property,
                           u.name as unit,
                           bapl.name as b_name,
                           ipt.name as accs,  
                           tdi.price_usd,
                           tdi.quantity,
                           td.reg_date,
                           sum(tdi.quantity) as summa,
                           fdt.name as fdep,
                           tdt.name as tdep,
                           ipt.sku 
                        from bichuv_doc td
                            left join bichuv_doc_items tdi on td.id = tdi.bichuv_doc_id 
                            LEFT JOIN bichuv_acs ipt ON tdi.entity_id = ipt.id 
                            LEFT JOIN unit u ON ipt.unit_id = u.id 
                            LEFT JOIN bichuv_acs_properties p ON ipt.id = p.bichuv_acs_id
                            LEFT JOIN bichuv_acs_property_list bapl ON p.bichuv_acs_property_list_id = bapl.id  
                            LEFT JOIN hr_departments fdt ON td.from_hr_department = fdt.id 
                            LEFT JOIN hr_departments tdt ON td.to_hr_department = tdt.id 
                        where td.document_type = 2
                          AND td.from_hr_department = %d
                          AND (reg_date BETWEEN '%s' AND '%s')
                          %s
                          AND td.status = 3
                        GROUP BY tdi.entity_id 
                        ORDER BY p.value ASC
                        LIMIT 100000;";
                $sql = sprintf($sql,
                     $this->department_id,
                     date('Y-m-d', strtotime($this->from_date)),
                     date('Y-m-d', strtotime($this->to_date)),
                     $to_dept
                );
                break;
        }
        return Yii::$app->db->createCommand($sql)->queryAll();

    }

    /**
     * @param bool $isAll
     * @return array|null
     * @throws \yii\db\Exception
     */
    public function getDeptList($isAll = false){
        $bichDoc = new BichuvDoc();
        if($isAll){
            //return $bichDoc->getDepartments(true);
            return $bichDoc->getHrDepartments();
        }
        return $bichDoc->getDepartmentsBelongTo();
    }

    public function searchBichuvAcsModelAccept($params)
    {
        $this->load($params);
        $planned = ModelOrders::STATUS_PLANNED;
        $str = '';
        if(!empty($params['BichuvItemBalanceSearch']['entity_id'])){
            $str = '(';
            foreach ($params['BichuvItemBalanceSearch']['entity_id'] as $param) {
                $str .= $param.',';
            }
            $str = rtrim($str, ',').')';
            
            $sql = "
                SELECT ba.id as baid, u.name as uname, mo.doc_number, ba.name as baname, ba.sku, bap.value, mo.responsible, moi.load_date, moia.qty
                FROM
                model_orders mo
                LEFT JOIN model_orders_items moi ON mo.id = moi.model_orders_id
                LEFT JOIN model_orders_items_acs moia ON moia.model_orders_items_id = moi.id
                LEFT JOIN bichuv_acs ba ON ba.id = moia.bichuv_acs_id
                LEFT JOIN bichuv_acs_properties bap ON bap.bichuv_acs_id = ba.id
                LEFT JOIN unit u ON u.id = ba.unit_id
                WHERE mo.status = {$planned} AND mo.orders_status = {$planned}
                AND mo.id in {$str}
            ";
            $query = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($query)){
                $array = [];
                foreach ($query as $item) {
                    $array[$item['baid']]['value'][] = $item['value'];
                    $array[$item['baid']]['name'] = $item['baname'];
                    $array[$item['baid']]['qty'] = $item['qty'];
                    $array[$item['baid']]['load_date'] = $item['load_date'];
                    $array[$item['baid']]['doc_number'] = $item['doc_number'];
                    $array[$item['baid']]['uname'] = $item['uname'];
                }
                
                $data = [];
                foreach ($array as $item) {
                    $data[] = $item;
                }
            }
            return $data;
        }
        return false;
    }
    
}
