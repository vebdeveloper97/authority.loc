<?php

namespace app\modules\tikuv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\ActiveRecord;

/**
 * Class TOPPSearch
 * @package app\modules\tikuv\models
 */
class TOPPSearch extends ActiveRecord
{
    public $doc_number;
    public $model_no;
    public $color_code;
    public $size_name;
    public $barcode;
    public $quantity;
    public $accepted;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_number','model_no','color_code','size_name','barcode','quantity','accepted'], 'safe'],
        ];
    }
    public function reportDataProvider($params){
        $sql = "select mo.doc_number, 
                       s.name as size_name, 
                       top.quantity,
                       top.barcode,
                       top.model_no,
                       top.color_code,
                       NULLIF(SUM(tta.accepted),0) as accepted
                from tikuv_outcome_products top
                left join tikuv_outcome_products_pack topp on top.pack_id = topp.id
                left join unit u on top.unit_id = u.id
                left join toquv_departments td on topp.department_id = td.id
                left join musteri m on topp.musteri_id = m.id
                left join sort_name sn on top.sort_type_id = sn.id
                left join size s on top.size_id = s.id
                left join model_orders_items moi on topp.order_item_id = moi.id
                left join tikuv_top_accepted tta on top.id = tta.top_id
                left join model_orders mo on moi.model_orders_id = mo.id GROUP BY top.id ORDER BY top.id DESC ;";

        $sqlCount = "select count(top.id)
                from tikuv_outcome_products top
                left join tikuv_outcome_products_pack topp on top.pack_id = topp.id
                left join unit u on top.unit_id = u.id
                left join toquv_departments td on topp.department_id = td.id
                left join musteri m on topp.musteri_id = m.id
                left join sort_name sn on top.sort_type_id = sn.id
                left join size s on top.size_id = s.id
                left join model_orders_items moi on topp.order_item_id = moi.id
                left join model_orders mo on moi.model_orders_id = mo.id";

        $count = Yii::$app->db->createCommand($sqlCount)->queryScalar();

        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'doc_number',
                ],
        ]]);
        return $provider;
    }
}
