<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.03.20 10:32
 */

namespace app\modules\bichuv\models;


use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

class NastelSearch extends BichuvGivenRollItems
{
    public $nastel_party;
    public $musteri;
    public $detail;
    public $mato;
    public $model;
    public $color;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['musteri', 'detail', 'status'], 'integer'],
            [['required_count'], 'number'],
            [['nastel_party'],'string','max' => 50],
            [['mato', 'model', 'color'], 'string']
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
     * @param null $is_brak
     * @return SqlDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $sql = "(select bgr.nastel_party,
                       m.name musteri,
                       bdt.name detail,
                       MAX(rm.name) mato,
                       MAX(p.name) as model,
                       SUM(bgri.roll_count) as rulon_count,
                       SUM(bgri.required_count) as required_count,
                       MAX(bgri.party_no) party_no,
                       bgri.id id,
                       bgri.status status,
                       MAX(ct.name) color
                from bichuv_given_roll_items bgri
                         left join bichuv_given_rolls bgr on bgri.bichuv_given_roll_id = bgr.id
                         left join bichuv_detail_types bdt on bgri.bichuv_detail_type_id = bdt.id  
                         left join product p on bgri.model_id = p.id
                         left join bichuv_mato_info bmi on bgri.entity_id = bmi.id
                         left join raw_material rm on bmi.rm_id = rm.id
                         left join ne nename on nename.id = bmi.ne_id
                         left join pus_fine pf on pf.id = bmi.pus_fine_id
                         left join thread thr on thr.id = bmi.thread_id
                         left join color c on bmi.color_id = c.id
                         left join color_tone ct on c.color_tone = ct.id
                         left join musteri m on bgr.musteri_id = m.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr on bgr.id = tkbgr.bichuv_given_rolls_id
                WHERE bgri.entity_type = 1 AND tkbgr.bichuv_given_rolls_id is not null AND bgr.status = 3 %s %s %s %s %s %s %s GROUP BY bgri.id)
                UNION 
                (select bgr2.nastel_party,
                       m2.name musteri,
                       bdt2.name detail,
                       CONCAT_WS(' ',COALESCE(acs.name),COALESCE(acs.sku),bap.name) mato,
                       MAX(p2.name) as model,
                       SUM(bgri2.roll_count) as rulon_count,
                       SUM(bgri2.required_count) as required_count,
                       MAX(bgri2.party_no) party_no,
                       bgri2.id id,
                       bgri2.status status,
                       1 as color
                from bichuv_given_roll_items bgri2
                         left join bichuv_given_rolls bgr2 on bgri2.bichuv_given_roll_id = bgr2.id
                         left join bichuv_detail_types bdt2 on bgri2.bichuv_detail_type_id = bdt2.id  
                         left join product p2 on bgri2.model_id = p2.id
                         left join bichuv_acs acs on bgri2.entity_id = acs.id
                         left join bichuv_acs_property bap on acs.property_id = bap.id
                         left join musteri m2 on bgr2.musteri_id = m2.id
                         left join tikuv_konveyer_bichuv_given_rolls tkbgr2 on bgr2.id = tkbgr2.bichuv_given_rolls_id
                WHERE bgri2.entity_type = 2 AND tkbgr2.bichuv_given_rolls_id is not null AND bgr2.status = 3 %s %s %s %s %s %s %s GROUP BY bgri2.id)";
        $detal = (!empty($this->detail)&&$this->validate('detail'))?" AND bdt.id = {$this->detail}":"";
        $detal2 = (!empty($this->detail)&&$this->validate('detail'))?" AND bdt2.id = {$this->detail}":"";
        $nastel_party = (!empty($this->nastel_party)&&$this->validate('nastel_party'))?" AND bgr.nastel_party LIKE '%{$this->nastel_party}%'":"";
        $nastel_party2 = (!empty($this->nastel_party)&&$this->validate('nastel_party'))?" AND bgr2.nastel_party LIKE '%{$this->nastel_party}%'":"";
        $musteri = (!empty($this->musteri)&&$this->validate('musteri'))?" AND m.id = {$this->musteri}":"";
        $musteri2 = (!empty($this->musteri)&&$this->validate('musteri'))?" AND m2.id = {$this->musteri}":"";
        $mato = (!empty($this->mato)&&$this->validate('mato'))?" AND bsdi.mato LIKE '%{$this->mato}%'":"";
        $mato2 = (!empty($this->mato)&&$this->validate('mato'))?" AND (acs.name LIKE '%{$this->mato}%' OR acs.sku LIKE '%{$this->mato}%' OR bap.name LIKE '%{$this->mato}%')":"";
        $model = (!empty($this->model)&&$this->validate('model'))?" AND p.name LIKE '%{$this->model}%'":"";
        $model2 = (!empty($this->model)&&$this->validate('model'))?" AND p2.name LIKE '%{$this->model}%'":"";
        $required_count = (!empty($this->required_count)&&$this->validate('required_count'))?" AND bgri.required_count LIKE '%{$this->required_count}%'":"";
        $required_count2 = (!empty($this->required_count)&&$this->validate('required_count'))?" AND bgri2.required_count LIKE '%{$this->required_count}%'":"";
        $color = (!empty($this->color)&&$this->validate('color'))?" AND bsdi.ctone LIKE '%{$this->color}%'":"";
        $color2 = (!empty($this->color)&&$this->validate('color'))?" AND bsdi2.ctone LIKE '%{$this->color}%'":"";
        $sql = sprintf($sql,$detal,$nastel_party,$musteri,$mato,$model,$required_count,$color,$detal2,$nastel_party2,$musteri2,$mato2,$model2,$required_count2,$color2);
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        return $dataProvider;
    }
}