<?php

namespace common\modules\rabbitmq\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RabbitMQConnectionSearch represents the model behind the search form of `common\modules\rabbitmq\models\RabbitMQConnection`.
 */
class RabbitMQConnectionSearch extends RabbitMQConnection
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'heartbeat'], 'integer'],
            [['host', 'port', 'user', 'password', 'vhost', 'login_method', 'login_response', 'locale', 'context', 'ssl_protocol'], 'safe'],
            [['insist', 'keepalive'], 'boolean'],
            [['connection_timeout', 'read_write_timeout', 'channel_rpc_timeout'], 'number'],
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
        $query = RabbitMQConnection::find();

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
            'id'                  => $this->id,
            'insist'              => $this->insist,
            'connection_timeout'  => $this->connection_timeout,
            'read_write_timeout'  => $this->read_write_timeout,
            'keepalive'           => $this->keepalive,
            'heartbeat'           => $this->heartbeat,
            'channel_rpc_timeout' => $this->channel_rpc_timeout,
        ]);

        $query->andFilterWhere(['ilike', 'host', $this->host])
            ->andFilterWhere(['ilike', 'port', $this->port])
            ->andFilterWhere(['ilike', 'user', $this->user])
            ->andFilterWhere(['ilike', 'password', $this->password])
            ->andFilterWhere(['ilike', 'vhost', $this->vhost])
            ->andFilterWhere(['ilike', 'login_method', $this->login_method])
            ->andFilterWhere(['ilike', 'login_response', $this->login_response])
            ->andFilterWhere(['ilike', 'locale', $this->locale])
            ->andFilterWhere(['ilike', 'context', $this->context])
            ->andFilterWhere(['ilike', 'ssl_protocol', $this->ssl_protocol]);

        return $dataProvider;
    }
}
