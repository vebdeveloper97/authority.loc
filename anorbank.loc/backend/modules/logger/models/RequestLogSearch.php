<?php

namespace backend\modules\logger\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\request_log\models\RequestLog;

/**
 * RequestLogSearch represents the model behind the search form of `common\modules\request_log\models\RequestLog`.
 */
class RequestLogSearch extends RequestLog
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['session_id', 'pair_id', 'service', 'date', 'type', 'body'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
    public function search(array $params): ActiveDataProvider
    {
        $query = RequestLog::find()
            ->where(['type' => self::TYPE_REQUEST])
            ->orderBy(['id' => SORT_DESC]);

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
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['ilike', 'service', $this->service])
            ->andFilterWhere(['ilike', 'body', $this->body]);

        return $dataProvider;
    }
}
