<?php

namespace app\modules\toquv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\toquv\models\ToquvSaldo;

/**
 * ToquvSaldoSearch represents the model behind the search form of `app\modules\toquv\models\ToquvSaldo`.
 */
class ToquvSaldoSearch extends ToquvSaldo
{
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'musteri_id', 'department_id', 'status', 'created_by', 'created_at', 'updated_at', 'pb_id', 'td_id'], 'integer'],
            [['credit1', 'credit2', 'debit1', 'debit2'], 'number'],
            [['operation', 'comment', 'reg_date','from_date','to_date'], 'safe'],
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
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function search($params)
    {
        $this->load($params);
        $this->from_date = date('Y-m-d H:i:s', strtotime($this->from_date));
        $this->to_date = date('Y-m-d H:i:s', strtotime($this->to_date));
        $result = ToquvSaldo::find();
        $result->select(['toquv_saldo.*','musteri.name as musteri_name','pul_birligi.name as currency']);

        $result->leftJoin('musteri','musteri.id=toquv_saldo.musteri_id');
        $result->leftJoin('pul_birligi','pul_birligi.id=toquv_saldo.pb_id');

        if ($this->musteri_id) $result->where(['toquv_saldo.musteri_id' => $this->musteri_id]);

        $result->andWhere(['between', 'toquv_saldo.reg_date', $this->from_date, $this->to_date]);
        return $result->orderBy(['toquv_saldo.reg_date'=>SORT_DESC])->asArray()->all();
    }
}
