<?php

namespace app\modules\bichuv\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvSaldo;

/**
 * BichuvSaldoSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvSaldo`.
 */
class BichuvSaldoSearch extends BichuvSaldo
{
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'musteri_id', 'department_id', 'pb_id', 'bd_id', 'status', 'payment_method', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['credit1', 'credit2', 'debit1', 'debit2'], 'number'],
            [['operation', 'comment', 'reg_date', 'from_date', 'to_date'], 'safe'],
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
    public function search($params)
    {
        $this->load($params);
        $result = BichuvSaldo::find();
        $this->from_date = date('Y-m-d H:i:s', strtotime($this->from_date));
        $this->to_date = date('Y-m-d H:i:s', strtotime($this->to_date));

        $result->select(['bichuv_saldo.*','musteri.name as musteri_name','pul_birligi.name as currency']);

        $result->leftJoin('musteri','musteri.id=bichuv_saldo.musteri_id');
        $result->leftJoin('pul_birligi','pul_birligi.id=bichuv_saldo.pb_id');

        if ($this->musteri_id) $result->where(['bichuv_saldo.musteri_id' => $this->musteri_id]);
        $result->andWhere(['between', 'bichuv_saldo.reg_date', $this->from_date, $this->to_date]);
        return $result->orderBy(['bichuv_saldo.reg_date'=>SORT_DESC])->asArray()->all();
    }
}
