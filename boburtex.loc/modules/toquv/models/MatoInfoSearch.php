<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\MatoInfo;

/**
 * MatoInfoSearch represents the model behind the search form of `app\modules\toquv\models\MatoInfo`.
 */
class MatoInfoSearch extends MatoInfo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'entity_type', 'pus_fine_id', 'type_weaving', 'toquv_rm_order_id', 'toquv_instruction_rm_id', 'toquv_instruction_id', 'musteri_id', 'model_musteri_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['thread_length', 'finish_en', 'finish_gramaj'], 'safe'],
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
    public function search($params,$type=ToquvDocuments::ENTITY_TYPE_MATO)
    {
        $query = MatoInfo::find()->where(['entity_type'=>$type]);

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
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'pus_fine_id' => $this->pus_fine_id,
            'type_weaving' => $this->type_weaving,
            'toquv_rm_order_id' => $this->toquv_rm_order_id,
            'toquv_instruction_rm_id' => $this->toquv_instruction_rm_id,
            'toquv_instruction_id' => $this->toquv_instruction_id,
            'musteri_id' => $this->musteri_id,
            'model_musteri_id' => $this->model_musteri_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'thread_length', $this->thread_length])
            ->andFilterWhere(['like', 'finish_en', $this->finish_en])
            ->andFilterWhere(['like', 'finish_gramaj', $this->finish_gramaj]);

        return $dataProvider;
    }
}
