<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvPricingItem;

/**
 * ToquvPricingItemSearch represents the model behind the search form of `app\modules\toquv\models\ToquvPricingItem`.
 */
class ToquvPricingItemSearch extends ToquvPricingItem
{
    public $thread_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'doc_id', 'entity_id','entity_type', 'pb_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            ['thread_name','string']
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
    public function search($params, $id = null)
    {
        $query = ToquvPricingItem::find();
        if (Yii::$app->request->get('slug')==ToquvPricingDoc::DOC_TYPE_IP_LABEL) {
            $query->leftJoin('toquv_ip','toquv_ip.id=toquv_pricing_item.entity_id');
            $thread = 'toquv_ip.name';
        }
        if (Yii::$app->request->get('slug')==ToquvPricingDoc::DOC_TYPE_MATO_LABEL) {
            $query->leftJoin('toquv_raw_material_ip','toquv_raw_material_ip.id=toquv_pricing_item.entity_id')->leftJoin('toquv_raw_materials','toquv_raw_materials.id=toquv_raw_material_ip.toquv_raw_material_id');
            $thread = 'toquv_raw_materials.name';
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            // 'id' => $this->id,
            'doc_id' => $id,
            // 'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            // 'price' => $this->price,
            'pb_id' => $this->pb_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        $query->andFilterWhere(['like', 'price', $this->price]);

        if(!empty($this->thread_name)){
            $query->andFilterWhere(['like',$thread, $this->thread_name]);
        }
        return $dataProvider;
    }
}
