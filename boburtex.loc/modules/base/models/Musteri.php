<?php

namespace app\modules\base\models;

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvSaldo;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvItemBalance;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvSaldo;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "musteri".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 * @property int $musteri_type_id
 * @property string $tel
 * @property string $address
 * @property string $director
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property string $token
 * @property string $code
 *
 * @property BichuvDoc[] $bichuvDocs
 * @property BichuvSaldo[] $bichuvSaldos
 * @property ModelOrders[] $modelOrders
 * @property MusteriType $musteriType
 * @property ToquvDocuments[] $toquvDocuments
 * @property ToquvItemBalance[] $toquvItemBalances
 * @property ToquvOrders[] $toquvOrders
 * @property ToquvSaldo[] $toquvSaldos
 */
class Musteri extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'musteri';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['add_info', 'address'], 'string'],
            [['musteri_type_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name', 'token'], 'string', 'max' => 255],
            [['tel', 'code'], 'string', 'max' => 50],
            [['director'], 'string', 'max' => 200],
            [['name'], 'unique'],
            [['token'], 'unique'],
            [['musteri_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MusteriType::className(), 'targetAttribute' => ['musteri_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'add_info' => Yii::t('app', 'Add Info'),
            'musteri_type_id' => Yii::t('app', 'Musteri Type ID'),
            'tel' => Yii::t('app', 'Tel'),
            'address' => Yii::t('app', 'Address'),
            'director' => Yii::t('app', 'Director'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'token' => Yii::t('app', 'Token'),
            'code' => Yii::t('app', 'Code'),
        ];
    }
    public static function getList($key = null, $id = null){
        $list = static::find()->where(['status'=>static::STATUS_ACTIVE])->all();
        $result = ArrayHelper::map($list,'id','name');

        if(!empty($key)){
            if($key=='options') {
                $options = ArrayHelper::map($list,'id',function($model) use ($id){
                    return ['disabled'=>'', 'class'=>'hidden'];
                });
                return $options;
            }
            else {
                return $result[$key];
            }
        }
        return $result;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDocs()
    {
        return $this->hasMany(BichuvDoc::className(), ['musteri_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvSaldos()
    {
        return $this->hasMany(BichuvSaldo::className(), ['musteri_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrders()
    {
        return $this->hasMany(ModelOrders::className(), ['musteri_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteriType()
    {
        return $this->hasOne(MusteriType::className(), ['id' => 'musteri_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocuments()
    {
        return $this->hasMany(ToquvDocuments::className(), ['musteri_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvItemBalances()
    {
        return $this->hasMany(ToquvItemBalance::className(), ['musteri_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvOrders()
    {
        return $this->hasMany(ToquvOrders::className(), ['musteri_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvSaldos()
    {
        return $this->hasMany(ToquvSaldo::className(), ['musteri_id' => 'id']);
    }
}
