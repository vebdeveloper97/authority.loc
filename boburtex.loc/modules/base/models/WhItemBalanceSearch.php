<?php

namespace app\modules\base\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\WhItemBalance;

/**
 * WhItemBalanceSearch represents the model behind the search form of `app\modules\base\models\WhItemBalance`.
 */
class WhItemBalanceSearch extends WhItemBalance
{
    public $date;
    public $entity_ids;
    public $type_id;
    public $category_id;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'entity_type', 'department_id', 'dep_section', 'dep_area', 'wh_document_id', 'incoming_pb_id', 'wh_pb_id', 'package_type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'sell_pb_id'], 'integer'],
            [['lot', 'reg_date', 'add_info'], 'safe'],
            [['quantity', 'inventory', 'incoming_price', 'wh_price', 'package_qty', 'package_inventory', 'sell_price'], 'number'],
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
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function search($params)
    {
        $entityIds = '';
        $type = '';
        $category = '';
        //print_r($params); exit();

        if ( !empty($params['entity_ids']) ) {
            $entityIds = ' AND (wib.entity_id IN ('.implode(',', $params['entity_ids']).'))';
        } else {
            $type = $params['type_id'] ? ' AND wi.type_id = ' . $params['type_id'] : '';
            $category = $params['category_id'] ? ' AND wi.category_id = ' . $params['category_id'] : '';
        }

        $reg_date = $params['date'] ? " AND wib.reg_date < '" . date('Y-m-d', strtotime('+1 day', strtotime($params['date']))). "'" : '';


        $sql = "SELECT wib.entity_id,
                    wi.name, 
                    wib.lot, 
                    wib.wh_price, 
                    wib.wh_pb_id, 
                    wib.package_type,
                    wic.name as category, 
                    wit.name as type, u.name as unit, c.name as country, 
                    wib.inventory,
                    wib.package_inventory,
                    pb.name as currency,
                    wib.id
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
                WHERE (entity_type=%d) AND (department_id=%d) AND (wib.inventory > 0) %s %s %s %s
                GROUP BY wib.entity_id, wib.entity_type, wib.lot, wib.dep_area, 
                         wib.wh_price, wib.wh_pb_id, wib.package_type LIMIT 50000";
        $sql = sprintf($sql,
            $params['department_id'],
            $params['entity_type'],
            $params['department_id'],
            $entityIds,
            $type,
            $category,
            $reg_date);

        //print_r($sql);

        return Yii::$app->db->createCommand($sql)->queryAll();
    }
}
