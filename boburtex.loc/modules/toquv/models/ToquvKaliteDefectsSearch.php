<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvKaliteDefects;

/**
 * ToquvKaliteDefectsSearch represents the model behind the search form of `app\modules\toquv\models\ToquvKaliteDefects`.
 */
class ToquvKaliteDefectsSearch extends ToquvKaliteDefects
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'toquv_kalite_id', 'toquv_rm_defects_id'], 'integer'],
            [['quantity'], 'number'],
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
    public function search($id)
    {
        $query = ToquvKaliteDefects::find()->orderBy(['toquv_rm_defects_id'=>SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);
        // grid filtering conditions
        $query->andFilterWhere([
            'toquv_kalite_id' => $id
        ]);

        return $dataProvider;
    }
}
