<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_given_roll_items".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $bichuv_given_roll_id
 * @property string $quantity
 * @property int $type
 * @property int $created_by
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $otxod
 * @property string $remain
 *
 * @property null $parties
 * @property BichuvGivenRolls $bichuvGivenRoll
 * @property Product $productModel
 * @property BichuvGivenRollItemsSub[] $bichuvGivenRollItemsSubs
 * @property string $party_no [varchar(50)]
 * @property string $musteri_party_no [varchar(50)]
 * @property string $roll_count [decimal(20,2)]
 * @property int $model_id [smallint(6)]
 * @property BichuvDetailTypes $bichuvDetailType
 * @property int $bichuv_detail_type_id [int(11)]
 * @property int $ [int(11)]
 * @property string $required_count [decimal(20,3)]
 * @property BichuvNastelDetails[] $bichuvNastelDetails
 * @property BichuvNastelDetailItems[] $bichuvNastelDetailItems
 * @property int $entity_type [smallint(2)]
 * @property BichuvNastelProcesses[] $bichuvNastelProcesses
 */
class BichuvGivenRollItemsAcs extends BaseModel
{
    const STATUS_BEGIN   = 2;
    const STATUS_STOPPED = 4;
    const STATUS_END     = 3;
    const STATUS_REJECT  = 5;
    public $new_model_id;
    public $token;
    public $entity_name;
    public $model_name;
    public $roll_remain;
    public $remain;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_given_roll_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'new_model_id','bichuv_detail_type_id', 'model_id','bichuv_given_roll_id', 'type', 'created_by', 'status', 'created_at', 'updated_at', 'remain_roll'], 'integer'],
            [['quantity', 'roll_count', 'required_count', 'otxod', 'remain'], 'number'],
            [['party_no','musteri_party_no'],'string','max' => 50],
            [['entity_id'],'required'],
            [['bichuv_given_roll_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvGivenRolls::className(), 'targetAttribute' => ['bichuv_given_roll_id' => 'id']],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['model_id' => 'id']],
            [['bichuv_detail_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDetailTypes::className(), 'targetAttribute' => ['bichuv_detail_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'model_id' => Yii::t('app', 'Model ID'),
            'new_model_id' => Yii::t('app', 'Yangi Model'),
            'bichuv_given_roll_id' => Yii::t('app', 'Bichuv Given Roll ID'),
            'bichuv_detail_type_id' => Yii::t('app', 'Bichuv Detial Types'),
            'party_no' => Yii::t('app', 'Partiya No'),
            'required_count' => Yii::t('app', 'Required Count'),
            'musteri_party_no' => Yii::t('app', 'Musteri Party No'),
            'quantity' => Yii::t('app', 'Quantity'),
            'roll_count' => Yii::t('app', 'Rulon soni'),
            'type' => Yii::t('app', 'Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'otxod' => Yii::t('app', 'Otxod'),
            'remain' => Yii::t('app', 'Remain'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRoll()
    {
        return $this->hasOne(BichuvGivenRolls::className(), ['id' => 'bichuv_given_roll_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetails()
    {
        return $this->hasMany(BichuvNastelDetails::className(), ['bichuv_given_roll_items_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvGivenRollItemsSubs()
    {
        return $this->hasMany(BichuvGivenRollItemsSub::className(), ['bichuv_given_roll_items_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetailItems()
    {
        return $this->hasMany(BichuvNastelDetailItems::className(), ['bichuv_given_roll_items_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Product::className(), ['id' => 'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDetailType()
    {
        return $this->hasOne(BichuvDetailTypes::className(), ['id' => 'bichuv_detail_type_id']);
    }
    public function getBichuvMatoInfo()
    {
        return $this->hasOne(BichuvMatoInfo::className(), ['id' => 'entity_id']);
    }
    public function getMatoInfo()
    {
        return $this->bichuvMatoInfo->mato->name;
    }
    /**
     * @return string|null
     */
    public function getParties(){
        $result = $this->party_no." / ".$this->musteri_party_no;
        if($result){
            return $result;
        }
        return null;
    }

    /**
     * @param $type
     * @return int|mixed
     */
    public function getRemain($type){
        $result = 0;
        switch ($type){
            case 'roll_count':
                $ib = BichuvRmItemBalance::find()->select(['roll_inventory'])->where(['entity_id' => $this->entity_id])->asArray()->orderBy(['id' => SORT_DESC])->limit(1)->one();
                if($ib){
                    $result = number_format($ib['roll_inventory'],0);
                }
                break;

            case 'roll_kg':
                $ib = BichuvRmItemBalance::find()->select(['inventory'])->where(['entity_id' => $this->entity_id])->asArray()->orderBy(['id' => SORT_DESC])->limit(1)->one();
                if($ib){
                    $result = $ib['inventory'];
                }
                break;
        }
        return $result;
    }
    public function getMato()
    {
        return $this->bichuvMatoInfo->mato->name;
    }
    public function getChild($i=1){
        $child = $this->bichuvNastelDetailItems;
        $content = '<div class="parentDiv">
                        <div id="modal_roll_acs_'.$i.'" class="fade modal modal_roll" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4>'.$this->getMatoName($this->entity_id).'</h4>
                                    </div>
                                    <div class="modal-body">';
        $input = "";
        if($child){
            $count = 0;
            foreach ($child as $key => $item) {
                $input .= "<div class='row parentRow size_acs_div_{$item['size_id']}' style='margin-bottom: 6px;'>
                                <div class='col-md-5 noPaddingRight'>
                                    <input type='text' class='form-control' value='{$item->size->name}' disabled>
                                </div>
                                <div class='col-md-5 noPaddingRight'>
                                    <input type='text' class='form-control size_input isInteger' data-count-size='count_size_acs_".$i."' name='BichuvGivenRollItemsAcs[{$i}][child][{$item['size_id']}]' value='{$item['required_count']}'>
                                </div>
                                <div class='col-md-2'>
                                    <button type='button' class='btn btn-xs btn-danger remove_size'>
                                        <i class='fa fa-remove'></i>
                                    </button>
                                </div>
                            </div>";
                $count += $item['required_count'];
            }
        }
            $content .= $input.'</div>
                            <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">'.Yii::t('app', 'Saqlash').'</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" value="'.$count.'" name="BichuvGivenRollItemsAcs['.$i.'][required_count]" class="form-control count_size" aria-describedby="basic-addon_acs_'.$i.'">
                    <span class="input-group-addon noPadding" id="basic-addon_acs_'.$i.'">
                          <button type="button" class="btn btn-success btn-xs plus_size_acs" data-toggle="modal" data-target="#modal_roll_acs_'.$i.'"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
            </div>';
        return $content;
    }
}
