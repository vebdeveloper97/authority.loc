<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvMakineProcesses;
use yii\data\Sort;

/**
 * ToquvMakineProcessesSearch represents the model behind the search form of `app\modules\toquv\models\ToquvMakineProcesses`.
 */
class ToquvMakineProcessesSearch extends ToquvMakineProcesses
{
    public $pus_fine;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'created_by', 'pus_fine', 'status'], 'integer'],
            [['started_at', 'ended_at'], 'safe'],
            [['user_id','toquv_order_item_id', 'machine_id', 'toquv_order_id'], 'string']
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @param bool $isOrder
     * @return ActiveDataProvider
     */
    public function search($params, $isOrder = false)
    {
        if(!$isOrder) {
            $query = ToquvMakineProcesses::find()->orderBy(['id' => SORT_DESC]);


            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);
            if (!$this->validate()) {

                return $dataProvider;
            }
            $query->andFilterWhere([
                'id' => $this->id,
                'created_by' => $this->created_by,
            ]);
            if($this->status) {
                $query->andFilterWhere(['is','ended_at',new \yii\db\Expression('NULL')]);
            }
            if($this->toquv_order_item_id) {
                $query->joinWith(['toquvOrderItem' => function ($q) {
                    $q->joinWith(['toquvRawMaterials' => function($a){
                        $a->where('toquv_raw_materials.name LIKE "%' . $this->toquv_order_item_id . '%"');
                    }]);
                }]);
            }
            if($this->toquv_order_id) {
                $query->joinWith(['toquvOrder' => function ($q) {
                    $q->joinWith(['musteri' => function($a){
                        $a->where('musteri.name LIKE "%' . $this->toquv_order_id . '%"');
                    }]);
                }]);
            }
            if($this->machine_id) {
                $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where('toquv_makine.name LIKE "%' . $this->machine_id . '%"');
                }]);
            }
            if($this->pus_fine) {
                $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where(['toquv_makine.pus_fine_id' => $this->pus_fine]);
                }]);
            }
            if($this->user_id) {
                $query->joinWith(['user' => function ($q) {
                    $q->where('users.username LIKE "%' . $this->user_id . '%"');
                }]);
            }
            $query->andFilterWhere(['like', 'started_at', $this->started_at])->andFilterWhere(['like', 'ended_at', $this->ended_at]);
        }else{
            $query = ToquvMakineProcesses::find();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            $this->load($params);
            if (!$this->validate()) {

                return $dataProvider;
            }
            $query->andFilterWhere([
                'id' => $this->id,
                'created_by' => $this->created_by,
            ]);
            if($this->status) {
                $query->andFilterWhere(['is','ended_at',new \yii\db\Expression('NULL')]);
            }
            if($this->toquv_order_item_id) {
                $query->joinWith(['toquvOrderItem' => function ($q) {
                    $q->joinWith(['toquvRawMaterials' => function($a){
                        $a->where('toquv_raw_materials.name LIKE "%' . $this->toquv_order_item_id . '%"');
                    }]);
                }]);
            }
            if($this->toquv_order_id) {
                $query->joinWith(['toquvOrder' => function ($q) {
                    $q->joinWith(['musteri' => function($a){
                    $a->where('musteri.name LIKE "%' . $this->toquv_order_id . '%"');
                    }]);
                }]);
            }
            if($this->machine_id) {
                    $query->joinWith(['toquvMakine' => function ($q) {
                    $q->where('toquv_makine.name LIKE "%' . $this->machine_id . '%"');
                }]);
            }
//            $query->joinWith(['toquvOrder' => function ($q) {
//                $q->joinWith('musteri')->where('musteri.name LIKE "%' . $this->toquv_order_id . '%"');
//            }]);
            if($this->user_id) {
                $query->joinWith(['user' => function ($q) {
                    $q->where('users.username LIKE "%' . $this->user_id . '%"');
                }]);
            }
            $query->andFilterWhere(['like', 'started_at', $this->started_at])->andFilterWhere(['like', 'ended_at', $this->ended_at]);
            $query->andWhere('toquv_makine_processes.id IN (select max(id) FROM toquv_makine_processes tmp group by tmp.machine_id)');
            $query->orderBy(['machine_id' => SORT_ASC]);
        }

        return $dataProvider;
    }
}
