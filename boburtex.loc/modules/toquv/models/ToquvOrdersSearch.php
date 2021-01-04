<?php

namespace app\modules\toquv\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ToquvOrdersSearch represents the model behind the search form of `app\modules\toquv\models\ToquvOrders`.
 */
class ToquvOrdersSearch extends ToquvOrders
{
    public $instructionStatus;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'musteri_id', 'instructionStatus', 'status', 'created_by', 'created_at', 'updated_at', 'entity_type', 'order_type'], 'integer'],
            [['document_number', 'reg_date', 'responsible_persons', 'comment'], 'safe'],
            [['sum_uzs', 'sum_usd', 'sum_rub', 'sum_eur'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'responsible_persons' => Yii::t('app', 'Responsible Persons'),
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
     * @param int $type
     * @param string $fromWhere
     *
     * @return ActiveDataProvider
     */
    public function search($params, $fromWhere = 'order', $type = 1)
    {
        $query = ToquvOrders::find()
            ->alias('tor')
            ->where(['tor.type'=>$type])
            ->joinWith('toquvInstructions')->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tor.reg_date' => $this->reg_date,
            'sum_uzs' => $this->sum_uzs,
            'sum_usd' => $this->sum_usd,
            'sum_rub' => $this->sum_rub,
            'sum_eur' => $this->sum_eur,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'entity_type' => $this->entity_type,
        ]);
        if($type==ToquvRawMaterials::MATO&&$fromWhere!=='kirim_mato'){
            $query->andFilterWhere(['!=','tor.status', $this::STATUS_KIRIM_MATO])
                ->andFilterWhere(['!=','tor.status', $this::STATUS_INACTIVE]);
        }
        switch($fromWhere){
            case 'order':
                $query->andFilterWhere(['tor.status' => $this->status])
                    ->andFilterWhere(['is', 'tor.model_orders_id', new \yii\db\Expression('null')])
                    ->andFilterWhere(['tor.musteri_id' => $this->musteri_id]);
                break;
            case 'instruction':
//                $query->andFilterWhere(['toquv_instructions.status' => 3]);
                $query->andFilterWhere(['tor.status' => self::STATUS_SAVED])
                    ->andFilterWhere(['is', 'tor.model_orders_id', new \yii\db\Expression('null')])
                    ->andFilterWhere(['tor.musteri_id' => $this->musteri_id]);
                break;
            case 'model_orders':
                $query->andFilterWhere(['tor.status' => $this->status])
                    ->andFilterWhere(['is not', 'tor.model_orders_id', new \yii\db\Expression('null')]);
                if($this->musteri_id) {
                    $query->joinWith(['modelOrders' => function ($q) {
                        $q->joinWith(['musteri' => function ($q) {
                            $q->where('musteri.id = "' . $this->musteri_id . '"');
                        }]);
                    }]);
                }
                break;
            case 'instruction-model':
                $query->joinWith('toquvRmOrders')
                    ->leftJoin('model_orders_items moi', 'toquv_rm_order.moi_id = moi.id')
                    ->andFilterWhere(['tor.status' => self::STATUS_SAVED])
                    ->andFilterWhere(['is not', 'tor.model_orders_id', new \yii\db\Expression('null')])
                    /*->andFilterWhere(['<', 'moi.status', 2])*/;
                break;
            case 'kirim_mato':
                $query->andFilterWhere(['in','tor.status', [$this::STATUS_INACTIVE,$this::STATUS_KIRIM_MATO]])
                    ->andFilterWhere(['tor.musteri_id' => $this->musteri_id]);
                break;
        }

        if(!empty($this->instructionStatus)){
            $query->andFilterWhere(['toquv_instructions.status' => $this->instructionStatus]);
        }
        if(!empty($this->order_type)){
            $query->andFilterWhere(['tor.order_type' => $this->order_type]);
        }
        if($this->responsible_persons) {
            $query->joinWith(['toquvOrdersResponsibles' => function ($q) {
                $q->joinWith(['users' => function ($q) {
                    $q->where('users.username LIKE "%' . $this->responsible_persons . '%"');
                }]);
            }]);
        }
        $query->andFilterWhere(['like', 'document_number', $this->document_number])
            ->andFilterWhere(['like', 'comment', $this->comment]);
        $query->orderBy(['id' => SORT_DESC]);
        return $dataProvider;
    }

    public function getInstructionStatusList(){
        $res = [
            1 => Yii::t('app','Saqlanmagan'),
            3 => Yii::t('app','Saqlangan')
        ];
        return $res;
    }
}
