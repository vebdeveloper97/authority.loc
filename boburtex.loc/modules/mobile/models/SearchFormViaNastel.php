<?php

namespace app\modules\mobile\models;

use app\modules\tikuv\models\TikuvDoc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Class SearchViaNastel
 * @package app\modules\mobile\models
 */
class SearchFormViaNastel extends Model
{
    public $nastel_no;
    /**
     * @var mixed
     */
    private $currentUserId;

    public function init()
    {
        parent::init();
        $this->currentUserId = Yii::$app->user->identity->id;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nastel_no'], 'safe'],
        ];
    }
    public function attributeLabels() {
        return [
            'nastel_no' => Yii::t('app','Nastel №'),
        ];
    }

    public function search($params, int $docType = null, string $processToken = null)
    {
        $query = TikuvDoc::find()
            ->alias('td')
            ->leftJoin('tikuv_doc_items','tikuv_doc_items.tikuv_doc_id = td.id');

        $processId = MobileProcess::getProcessIdByToken($processToken);

        // user id ga tegishli doc larni olish
        $query->innerJoinWith(['mobileProcess mp' => function ($query) {
            $query->joinWith(['mobileTables mt' => function ($query) {
                $query->joinWith(['mobileTablesRelHrEmployees mtrhe' => function ($query){
                    $query->joinWith(['hrEmployee' => function ($query) {
                        $query->joinWith(['hrEmployeeUser heu' => function ($query) {
                            $query->andWhere(['heu.users_id' => $this->currentUserId]);
                        }]);
                    }]);
                    $query->andOnCondition(['mtrhe.status' => MobileTablesRelHrEmployee::STATUS_ACTIVE]);
                }]);
            }]);
        }]);

        $query->andWhere(['mt.id' => new Expression('td.mobile_table_id')]);

        // faqat hozirgi jarayonlarga tegishli doc larni olish
        $query->andWhere([
            'td.mobile_process_id' => $processId
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['td.document_type' => $docType]);
        $query->andFilterWhere(['like', 'tikuv_doc_items.nastel_party_no', trim($this->nastel_no)]);
        $query->groupBy(['td.id']);
        $query->addOrderBy(['td.id' => SORT_DESC]);
        return $dataProvider;
    }
}
