<?php

namespace common\modules\rabbitmq\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RabbitMQConsumeSearch represents the model behind the search form of `common\modules\rabbitmq\models\RabbitMQConsume`.
 */
class RabbitMQConsumeSearch extends RabbitMQConsume
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'connection_id', 'queue_id', 'exchange_id', 'ticket'], 'integer'],
            [['tag', 'queue_declare', 'exchange_declare', 'callback', 'arguments'], 'safe'],
            [['no_local', 'no_ack', 'exclusive', 'nowait'], 'boolean'],
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
        $query = RabbitMQConsume::find();

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
            'id'            => $this->id,
            'connection_id' => $this->connection_id,
            'queue_id'      => $this->queue_id,
            'exchange_id'   => $this->exchange_id,
            'no_local'      => $this->no_local,
            'no_ack'        => $this->no_ack,
            'exclusive'     => $this->exclusive,
            'nowait'        => $this->nowait,
            'ticket'        => $this->ticket,
        ]);

        $query->andFilterWhere(['ilike', 'tag', $this->tag])
            ->andFilterWhere(['ilike', 'queue_declare', $this->queue_declare])
            ->andFilterWhere(['ilike', 'exchange_declare', $this->exchange_declare])
            ->andFilterWhere(['ilike', 'callback', $this->callback])
            ->andFilterWhere(['ilike', 'arguments', $this->arguments]);

        return $dataProvider;
    }
}
