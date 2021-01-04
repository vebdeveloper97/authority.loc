<?php

namespace app\modules\bichuv\models;

use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvMatoOrders;
use yii\helpers\ArrayHelper;

/**
 * BichuvMatoSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvMatoOrders`.
 */
class BichuvMatoSearch extends BichuvMatoOrders
{
    public $info;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['info', 'string'],
            [['id', 'musteri_id', 'model_orders_id', 'model_orders_items_id', 'bichuv_doc_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['doc_number', 'reg_date', 'add_info'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BichuvMatoOrders::find()->alias('bmo')->where(['>','bmo.status',self::STATUS_INACTIVE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC,'id'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'model_orders_id' => $this->model_orders_id,
            'model_orders_items_id' => $this->model_orders_items_id,
            'bichuv_doc_id' => $this->bichuv_doc_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if($this->info){
            /*$query->joinWith(['moi moi' => function ($q) {
                $q->where(['moi.id'=>$this->info]);
            }]);*/

            $sql = "
            SELECT *
            FROM (
                     SELECT
                         bmo.id AS info_id,
                         CAST(CONCAT(mo.doc_number, ' - ', ml.article, ' - ', ml.name, '( ', cp.code, ' ) (', st.name, ' - ', SUM(mois.count) , ')')as CHAR CHARACTER SET utf8mb4) AS info
                     FROM bichuv_mato_orders bmo
                          LEFT JOIN model_orders mo on bmo.model_orders_id = mo.id
                          LEFT JOIN model_orders_items moi on moi.id = bmo.model_orders_items_id
                          LEFT JOIN model_orders_items_size mois ON moi.id = mois.model_orders_items_id
                          LEFT JOIN size s ON mois.size_id = s.id
                          LEFT JOIN size_type st ON s.size_type_id = st.id
                          LEFT JOIN models_variations mv on  moi.model_var_id = mv.id
                          LEFT JOIN color_pantone cp on mv.color_pantone_id = cp.id
                          LEFT JOIN models_list ml on moi.models_list_id = ml.id
                     GROUP BY bmo.id, mois.model_orders_items_id
                 ) AS model_order
            WHERE model_order.info LIKE :info
            ";

            $infoNewValue = "%{$this->info}%";
            $infoFilterQuery = Yii::$app->db->createCommand($sql, [
                ':info' => $infoNewValue
            ]);
            $result = $infoFilterQuery->queryAll();
            if ($result) {
                $inValues = ArrayHelper::getColumn($result, 'info_id');
            }else{
                $inValues = 0;
            }
            $query->andFilterWhere([
                'in', 'id', $inValues
            ]);
        }
        $query->andFilterWhere(['>','bmo.status',$this::STATUS_INACTIVE]);
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }

    public static function getRmInfo()
    {
        $mato_ombor = ToquvDepartments::findOne(['token'=>'BICHUV_MATO_OMBOR']);
        $dept_id = null;
        if($mato_ombor!==null){
            $dept_id = $mato_ombor['id'];
        }
        $sql = "select brib.entity_id,
                   bdi.bss_id,
                   brib.id brib_id,
                   m.name                as mname,
                   bdi.is_accessory,
                   rm.name               as mato,
                   nename.name           as ne,
                   thr.name              as ip,
                   pf.name               as pus_fine,
                   c.color_id,
                   ct.name               as ctone,
                   c.pantone,
                   p.name                as model,
                   brib.party_no         as partiya_no,
                   brib.musteri_party_no as mijoz_part,
                   bmi.en                as mato_en,
                   bmi.gramaj,
                   brib.roll_inventory   as rulon_count,
                   brib.inventory        as rulon_kg,
                   brib.party_no,
                   brib.from_musteri, 
                   brib.musteri_party_no,
                   p.id                  as model_id
            from bichuv_rm_item_balance brib
                     left join bichuv_mato_info bmi on brib.entity_id = bmi.id
                     inner join bichuv_doc_items bdi on brib.entity_id = bdi.entity_id
                     left join raw_material rm on bmi.rm_id = rm.id
                     left join ne nename on bmi.ne_id = nename.id
                     left join pus_fine pf on bmi.pus_fine_id = pf.id
                     left join thread thr on bmi.thread_id = thr.id
                     left join color c on bmi.color_id = c.id
                     left join color_tone ct on c.color_tone = ct.id
                     left join musteri m on brib.from_musteri = m.id
                     left join product p on brib.model_id = p.id
            WHERE brib.id in (
                select MAX(brib2.id)
                from bichuv_rm_item_balance brib2
                where brib2.department_id = %d
                GROUP BY brib2.entity_id,brib2.party_no,brib2.musteri_party_no, brib2.from_musteri)
              AND brib.inventory > 0
            GROUP BY brib.entity_id,brib.party_no,brib.musteri_party_no, brib.from_musteri
            ORDER BY brib.id ASC LIMIT 10000;";
        $sql = sprintf($sql,$dept_id);
        $list = [];
        if($dept_id) {
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            $list['list'] = ArrayHelper::map($res, 'brib_id', function ($m) {
                if ( $m['is_accessory'] != 2 ) {
                    $en_gr = number_format($m['mato_en'], 0, '', '') . ' sm / ' . number_format($m['gramaj'], 0, '', '') . ' gr/m2';
                    $name = "{$m['mato']}-{$m['ne']}-{$m['ip']}|{$m['pus_fine']}-({$m['ctone']} {$m['color_id']} {$m['pantone']})-({$m['mname']})-({$m['model']})-({$en_gr}) <span>{$m['party_no']}/{$m['musteri_party_no']}</span>";
                } else {
                    $name = "{$m['mato']}-{$m['ip']}-({$m['mname']})-({$m['model']}) <span>{$m['party_no']}/{$m['musteri_party_no']} </span>";
                }
                return $name;
            });
            $list['options'] = ArrayHelper::map($res, 'brib_id', function ($m) {
                return [
                    'data-list' => [
                        'mato_en' => $m['mato_en'],
                        'gramaj' => $m['gramaj'],
                        'rulon_kg' => $m['rulon_kg'],
                        'rulon_count' => $m['rulon_count'],
                        'party_no' => $m['party_no'],
                        'musteri_party_no' => $m['musteri_party_no'],
                        'model_id' => $m['model_id'],
                        'is_accessory' => $m['is_accessory'],
                        'bss_id' => $m['bss_id'],
                        'from_musteri' => $m['from_musteri'],
                        'entity_id' => $m['entity_id'],
                        'en-gramaj' => ($m['is_accessory'] != 2) ? number_format($m['mato_en'], 0, '', '') . ' sm / ' . number_format($m['gramaj'], 0, '', '') . ' gr/m2' : Yii::t('app', 'Aksessuar'),
                    ]
                ];
            });
        }
        return $list;
    }
}
