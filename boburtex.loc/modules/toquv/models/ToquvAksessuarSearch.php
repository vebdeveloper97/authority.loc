<?php

namespace app\modules\toquv\models;

use app\modules\toquv\models\ToquvRawMaterials;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ToquvAksessuarSearch represents the model behind the search form of `app\modules\toquv\models\ToquvRawMaterials`.
 */
class ToquvAksessuarSearch extends ToquvRawMaterials
{
    public $rawMaterialName;
    public $userName;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'raw_material_type_id', 'color_id', 'created_by', 'status', 'created_at', 'updated_at', 'type'], 'integer'],
            [['name', 'name_ru', 'code','rawMaterialName','userName'], 'safe'],
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
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ToquvRawMaterials::find()->alias('trm')->where(['trm.type'=>static::ACS]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'type',
                'rawMaterialName',
                'color_id',
                'userName',
                'code',
                'status'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['rawMaterialType']);
            $query->joinWith(['rawMaterialColor']);
            $query->joinWith(['users']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'raw_material_type_id' => $this->raw_material_type_id,
            'color_id' => $this->color_id,
            'created_by' => $this->created_by,
            'trm.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'trm.name', $this->name])
            ->andFilterWhere(['like', 'trm.name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'trm.code', $this->code]);

        $query->joinWith(['rawMaterialType' => function ($q) {
            $q->where('raw_material_type.name LIKE "%' . $this->rawMaterialName . '%"');
        }]);
        $query->joinWith(['createdUser' => function ($q) {
            $q->where('users.username LIKE "%' . $this->userName . '%"');
        }]);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 50;
        return $dataProvider;
    }
}