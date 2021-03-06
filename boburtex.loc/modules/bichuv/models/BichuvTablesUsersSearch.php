<?php

namespace app\modules\bichuv\models;

use app\models\UserRoles;
use app\models\Users;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bichuv\models\BichuvTablesUsers;

/**
 * BichuvTablesUsersSearch represents the model behind the search form of `app\modules\bichuv\models\BichuvTablesUsers`.
 */
class BichuvTablesUsersSearch extends BichuvTablesUsers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bichuv_tables_id', 'users_id', 'hr_employee_id','type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
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
    public function search($params, $dept = 'bichuv')
    {
        /*$subQuery = UserRoles::find()->select(['id'])->where(['department' => $dept]);*/
        $query = BichuvTablesUsers::find()/*->where(['user_role' => $subQuery])*/;

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
            'bichuv_tables_id' => $this->bichuv_tables_id,
            'users_id' => $this->users_id,
            'type' => $this->type,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
