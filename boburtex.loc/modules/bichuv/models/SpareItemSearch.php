<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\SpareItem;
use yii\helpers\VarDumper;

/**
 * SpareItemSearch represents the model behind the search form of `app\modules\bichuv\models\SpareItem`.
 */
class SpareItemSearch extends SpareItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'unit_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by','type'], 'integer'],
            [['name', 'sku', 'barcode'], 'safe'],
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
    public function search($params,$data=null)
    {
        if(!empty($data)){
            $query = SpareItem::find();
            $SpareItem = $data['SpareItem'];
            $SpareItemProperty = $data['SpareItemProperty']['value'];
            $name = $SpareItem['name'];
            $sku = $SpareItem['sku'];
            $barcode = $SpareItem['barcode'];
            $spareItems = '';
            $id = [];
            if(!empty($SpareItemProperty)){
                $spareItems = SpareItemProperty::find()
                    ->select('spare_item_id')
                    ->where(['like', 'value', $SpareItemProperty])
                    ->asArray()
                    ->all();
                foreach ($spareItems as $item){
                    $id[] = $item['spare_item_id'];
                }
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $query->andFilterWhere(['like', 'name', $name])
                ->andFilterWhere(['like', 'sku', $sku])
                ->andFilterWhere(['like', 'barcode', $barcode])
                ->andFilterWhere(['in', 'id', $id]);

            return $dataProvider;
        }

        $query = SpareItem::find();

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
            'id' => $this->id,
            'unit_id' => $this->unit_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'barcode', $this->barcode]);

        return $dataProvider;
    }
}
