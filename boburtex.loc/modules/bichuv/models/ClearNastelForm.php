<?php

namespace app\modules\bichuv\models;

use app\modules\tikuv\models\ModelRelDoc;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use app\modules\usluga\models\UslugaDoc;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class ClearNastelForm
 * @package app\modules\bichuv\models
 */
class ClearNastelForm extends BichuvGivenRolls
{
    public $nastel_no;
     public $is_coming;
    public $is_moving;

    public static function tableName()
    {
        return '{{%bichuv_given_rolls}}';
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['customer_id','updated_by', 'size_collection_id'],'integer'],
            [['nastel_no','nastel_party'], 'safe'],
            [['is_coming','is_moving'], 'boolean'],
        ];
    }
    public function attributeLabels() {
        return [
            'nastel_no' => Yii::t('app','Nastel №'),
            'is_coming' => Yii::t('app',"Qabul qilish"),
            'is_moving' => Yii::t('app',"Ko'chirish"),
        ];
    }
    public function checkTikuv(){
        $tikuv = TikuvDoc::find()->alias('td')->joinWith('tikuvDocItems tdi')->where(['td.status'=>3,'nastel_party_no'=>$this->nastel_party])->groupBy('td.id')->count();
        $usluga = UslugaDoc::find()->alias('ud')->joinWith('uslugaDocItems udi')->where(['ud.status'=>3,'udi.nastel_party'=>$this->nastel_party])->groupBy('ud.id')->count();
        return $tikuv+$usluga;
    }
    public function checkOut(){
        $tikuv = BichuvDoc::find()->alias('bd')->joinWith('bichuvSliceItems bsi')->where(['bd.status'=>3,'bsi.nastel_party'=>$this->nastel_party,'bd.document_type'=>BichuvDoc::DOC_TYPE_MOVING])->groupBy('bd.id')->count();
        return $tikuv;
    }
    public function checkIn(){
        $tikuv = BichuvDoc::find()->alias('bd')->joinWith('bichuvSliceItems bsi')->where(['bd.status'=>3,'bsi.nastel_party'=>$this->nastel_party,'bd.document_type'=>BichuvDoc::DOC_TYPE_INSIDE])->groupBy('bd.id')->count();
        return $tikuv;
    }
    public function checkModel(){
        $model_count = ModelRelDoc::find()->where(['nastel_no'=>$this->nastel_party])->count();
        return $model_count;
    }

    public function checkMoving()
    {
        $nastel_no = $this->nastel_party;
        $ud_doc_type = UslugaDoc::DOC_TYPE_OUTCOMING_MUSTERI_SLICE;
        $sql = "SELECT COUNT(ud.id) FROM usluga_doc ud
                left join usluga_doc_items udi on ud.id = udi.usluga_doc_id
                WHERE ud.document_type = {$ud_doc_type} AND (ud.nastel_no = '{$nastel_no}' OR udi.nastel_party = '{$nastel_no}')
                GROUP BY udi.nastel_party
        ";

    }
}
