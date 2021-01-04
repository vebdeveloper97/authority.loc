<?php

namespace app\modules\bichuv\models;

use app\models\UserRoles;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * ToquvUsersSearch represents the model behind the search form of `app\models\Users`.
 */
class BichuvUsersSearch extends Users
{
    public $tabel;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'user_role', 'created_user', 'status'], 'integer'],
            [['username', 'password', 'user_fio', 'lavozimi', 'add_info', 'session_id', 'session_time', 'created_time', 'code', 'tabel'], 'safe'],
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
    public function search($params,$token=['NASTELCHI'])
    {
        $user_role = UserRoles::find()->select(['id'])->where(['in','code',$token]);
        $query = Users::find()->joinWith('usersInfo')->where(['user_role'=>$user_role]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'code',
                'username',
                'user_fio',
                'lavozimi',
                'user_role',
                'tabel' => [
                    'asc' => ['users_info.tabel' => SORT_ASC],
                    'desc' => ['users_info.tabel' => SORT_DESC],
                    'label' => 'Your Label'
                ],
                'status',
                'add_info',
            ]
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
            'uid' => $this->uid,
            'user_role' => $this->user_role,
            'session_time' => $this->session_time,
            'created_user' => $this->created_user,
            'created_time' => $this->created_time,
        ]);
        if($this->status){
            $query->andFilterWhere([
                'users.status' => $this->status,
            ]);
        }else{
            $query->andFilterWhere([
                'users.status' => Users::ACTIVE,
            ]);
        }
        if($this->tabel) {
            $query->joinWith(['usersInfo' => function ($q) {
                $q->where('users_info.tabel LIKE "%' . $this->tabel . '%"');
            }]);
        }
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'user_fio', $this->user_fio])
            ->andFilterWhere(['like', 'lavozimi', $this->lavozimi])
            ->andFilterWhere(['like', 'add_info', $this->add_info])
            ->andFilterWhere(['like', 'session_id', $this->session_id])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
