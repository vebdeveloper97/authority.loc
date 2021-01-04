<?php


namespace app\modules\bichuv\models;


use app\modules\toquv\models\ToquvDocumentItems;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class BichuvDocItemsSearch
 * @package app\modules\bichuv\models
 */

class BichuvDocItemsSearch extends BichuvDocItems
{

    public $number_and_date;

    public $model_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bichuv_doc_id', 'model_id', 'entity_id', 'entity_type', 'is_own', 'package_type', 'package_qty', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['quantity', 'price_sum', 'price_usd', 'current_usd'], 'number'],
            [['add_info'], 'string'],
            [['lot', 'number_and_date'], 'safe'],
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
     * @param int $id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        $query = BichuvDocItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
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
            'bichuv_doc_id' => $id,
            'entity_id' => $this->entity_id,
            'model_id' => $this->model_id,
            'entity_type' => $this->entity_type,
            'quantity' => $this->quantity,
            'current_usd' => $this->current_usd,
            'is_own' => $this->is_own,
            'package_type' => $this->package_type,
            'package_qty' => $this->package_qty,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);
        $query->andFilterWhere(['like', 'lot', $this->lot]);
        $query->andFilterWhere(['like', 'add_info', $this->add_info]);
        $query->andFilterWhere(['or', ['price_sum' => $this->price_sum], ['price_usd' => $this->price_usd]]);

        return $dataProvider;
    }
}