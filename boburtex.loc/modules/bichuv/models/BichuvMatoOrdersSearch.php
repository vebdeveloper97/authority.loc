<?php

namespace app\modules\bichuv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvMatoOrders;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * BichuvMatoOrdersSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvMatoOrders`.
 */
class BichuvMatoOrdersSearch extends BichuvMatoOrders
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
            [['doc_number', 'reg_date', 'add_info', ], 'safe'],
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
     * @throws Exception
     */
    public function search($params)
    {
        $query = BichuvMatoOrders::find()->indexBy('id');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_ASC,'id'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 15,
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
            'reg_date' => $this->reg_date,
            'musteri_id' => $this->musteri_id,
            'model_orders_id' => $this->model_orders_id,
            'model_orders_items_id' => $this->model_orders_items_id,
            'bichuv_doc_id' => $this->bichuv_doc_id,
            'status' => $this->status,
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
        $query->andFilterWhere(['like', 'doc_number', $this->doc_number])
            ->andFilterWhere(['like', 'add_info', $this->add_info]);

        return $dataProvider;
    }
}
