<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvAcs;
use yii\data\SqlDataProvider;
use yii\helpers\VarDumper;

/**
 * BichuvAcsSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvAcs`.
 */
class BichuvAcsSearch extends BichuvAcs
{

    public $propertyName;
    public $unitName;
    public $value;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'property_id', 'unit_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['sku', 'value', 'name', 'barcode', 'add_info','propertyName','unitName'], 'safe'],
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
    public function search($params, $data=null)
    {
        if(!empty($data)){
            $query = BichuvAcs::find();
            $SpareItem = $data['BichuvAcs'];
            $SpareItemProperty = $data['BichuvAcsProperties']['value'];
            $name = $SpareItem['name'];
            $sku = $SpareItem['sku'];
            $barcode = $SpareItem['barcode'];
            $spareItems = '';
            $id = [];
            if(!empty($SpareItemProperty)){
                $spareItems = BichuvAcsProperties::find()
                    ->select('bichuv_acs_id')
                    ->where(['like', 'value', $SpareItemProperty])
                    ->asArray()
                    ->all();
                foreach ($spareItems as $item){
                    $id[] = $item['bichuv_acs_id'];
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
        $name = $params['BichuvAcsSearch']['name'];
        $array = [];

        $query = BichuvAcs::find();

        if(!empty($name)){
            $array = explode(' ', $name);
            $name_query = BichuvAcs::find()
                ->leftJoin('bichuv_acs_properties', 'bichuv_acs.id = bichuv_acs_properties.bichuv_acs_id')
                ->andWhere(['in', 'bichuv_acs.name', $array])
                ->orWhere(['in', 'bichuv_acs_properties.value', $array]);

            $nameProvider = new ActiveDataProvider([
                'query' => $name_query,
            ]);
            $nameProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 100;
            return $nameProvider;
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'id',
                'sku',
                'name',
                'unit_id',
                'barcode',
                'add_info',
                'status',
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['unit']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'bichuv_acs.name', $this->name])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['like', 'unit.id', $this->unit_id]);

        $query->joinWith(['unit' => function ($q) {
            $q->where('unit.name LIKE "%' . $this->unitName . '%"');
        }]);


        return $dataProvider;
    }
}
