<?php

namespace app\modules\base\models;

use app\modules\toquv\models\MatoInfo;
use app\modules\wms\models\WmsMatoInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\ModelsList;
use yii\db\Expression;

/**
 * ModelsListSearch represents the model behind the search form of `app\modules\base\models\ModelsList`.
 */
class ModelsListSearch extends ModelsList
{
    public $date_from;
    public $date_to;
    public $qolip_true;
    public $qolip_false;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'view_id', 'type_id', 'type_child_id', 'type_2x_id', 'model_season', 'users_id', 'status', 'created_by', 'created_at', 'updated_at', 'updated_by', 'brend_id', 'baski', 'prints', 'stone', 'qolip_true','qolip_false'], 'integer'],
            [['name', 'long_name', 'article', 'add_info', 'washing_notes', 'finishing_notes', 'packaging_notes', 'default_comment', 'product_details'], 'safe'],
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
        $query = ModelsList::find()->orderBy(['id'=>SORT_DESC]);

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
            'view_id' => $this->view_id,
            'type_id' => $this->type_id,
            'type_child_id' => $this->type_child_id,
            'type_2x_id' => $this->type_2x_id,
            'model_season' => $this->model_season,
            'users_id' => $this->users_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'brend_id' => $this->brend_id,
            'baski' => $this->baski,
            'prints' => $this->prints,
            'stone' => $this->stone,
        ]);

        $GO = ModelOrders::GENERAL_ORDER_TOKEN;
        $query->andWhere("not models_list.token <=> '{$GO}'");

        if($this->status){
            $query->andFilterWhere(['status' => $this->status]);
        }else{
            $query->andFilterWhere(['<>','status', self::STATUS_INACTIVE]);
        }
        $qolip = \Yii::$app->request->get('qolip');
        if($qolip){
            if($qolip==1){
                $query->andFilterWhere(['IS NOT','base_pattern_id', new Expression('NULL')]);
                $this->qolip_true = 'checked';
            }else{
                $query->andFilterWhere(['IS','base_pattern_id', new Expression('NULL')]);
                $this->qolip_false = 'checked';
            }
        }
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'long_name', $this->long_name])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['like', 'washing_notes', $this->washing_notes])
            ->andFilterWhere(['like', 'finishing_notes', $this->finishing_notes])
            ->andFilterWhere(['like', 'packaging_notes', $this->packaging_notes])
            ->andFilterWhere(['like', 'default_comment', $this->default_comment])
            ->andFilterWhere(['like', 'product_details', $this->product_details]);
        $dataProvider->pagination->pageSize = \Yii::$app->request->get('per-page') ?? 20;
        return $dataProvider;
    }
}
