<?php

namespace app\modules\tikuv\models;

use app\models\Size;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * TikuvOutcomeProductsSearch represents the model behind the search form of `app\modules\tikuv\models\TikuvOutcomeProducts`.
 */
class TikuvOutcomeProductsSearch extends TikuvOutcomeProducts
{
    public $barcode;
    public $sort1;
    public $sort2;
    public $sort3;
    public $total;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'size_type_id', 'size_id', 'accepted_quantity', 'status', 'created_by', 'created_at', 'updated_at', 'pack_id', 'sort_type_id', 'unit_id','amount'], 'integer'],
            ['quantity', 'number'],
            [['model_no', 'color_code','total','pechat','brand', 'barcode', 'is_main_barcode','reg_date','sort1','sort2','sort3'], 'safe'],
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
     * @param null $id
     * @return ActiveDataProvider
     */
    public function search($params,$id = null)
    {
        $query = TikuvOutcomeProducts::find();
        if($id){
            $query->where(['pack_id'=>$id]);
        }
        $query->leftJoin('size','size.id = tikuv_outcome_products.size_id');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' =>['nastel_no','brand','model_no','barcode','is_main_barcode','color_code','quantity','size_id','unit_id','sort_type_id','reg_date','size_type_id']],
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->size_type_id){
            $size_list = Size::find()->select('id')->where(['size_type_id'=>$this->size_type_id])->asArray()->all();
            $size_list = ArrayHelper::index($size_list,'id');
            if (!array_key_exists($this->size_id,$size_list)) {
                $this->size_id = null;
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tikuv_outcome_products.id' => $this->id,
            'size_type_id' => $this->size_type_id,
            'size_id' => $this->size_id,
            'accepted_quantity' => $this->accepted_quantity,
            'tikuv_outcome_products.status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pack_id' => $this->pack_id,
            'sort_type_id' => $this->sort_type_id,
            'unit_id' => $this->unit_id,
        ]);

        $query->andFilterWhere(['like', 'model_no', $this->model_no])
            ->andFilterWhere(['like', 'color_code', $this->color_code])
            ->andFilterWhere(['like', 'pechat', $this->pechat])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'is_main_barcode', $this->is_main_barcode])
            ->andFilterWhere(['like', 'reg_date', $this->reg_date])
            ->andFilterWhere(['like', 'quantity', $this->quantity])
            ->andFilterWhere(['like', 'amount', $this->amount]);
        $query->orderBy(['nastel_no'=>SORT_ASC,'size.order' => SORT_ASC]);
        return $dataProvider;
    }
    /**
     * @return array
     */
    public static function getSizeList()
    {
        $size_id = \Yii::$app->request->get('TikuvOutcomeProductsSearch')['size_type_id'];
        if($size_id) {
            $sizeTypes = Size::find()->select(['id', 'name'])->where(['size_type_id' => $size_id])->asArray()->all();
            return ArrayHelper::map($sizeTypes, 'id', 'name');
        }else{
            return [];
        }
    }

}
