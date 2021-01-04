<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvIp;

/**
 * ToquvIpSearch represents the model behind the search form of `app\modules\toquv\models\ToquvIp`.
 */
class ToquvIpSearch extends ToquvIp
{
    public $neName;
    public $colorName;
    public $threadName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name', 'ne_id', 'thread_id', 'color_id', 'barcode'], 'safe'],
            [['neName','threadName','colorName'], 'safe']
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
        $query = ToquvIp::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'ne_id',
                'color_id',
                'thread_id',
                'status'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['ne']);
            $query->joinWith(['color']);
            $query->joinWith(['thread']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'toquv_ip.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'toquv_ip.name', $this->name])
            ->andFilterWhere(['like', 'barcode', $this->barcode]);

        if($this->ne_id) {
            $query->joinWith(['ne' => function ($q) {
                $q->where('toquv_ne.name LIKE "%' . $this->ne_id . '%"');
            }]);
        }
        if($this->color_id) {
            $query->joinWith(['color' => function ($q) {
                $q->where('toquv_ip_color.name LIKE "%' . $this->color_id . '%"');
            }]);
        }
        if($this->thread_id) {
            $query->joinWith(['thread' => function ($q) {
                $q->where('toquv_thread.name LIKE "%' . $this->thread_id . '%"');
            }]);
        }


        return $dataProvider;
    }
}
