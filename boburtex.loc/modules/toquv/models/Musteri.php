<?php

namespace app\modules\toquv\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\base\models\ModelTypes;

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
 *
 * @property BichuvDoc[] $bichuvDocs
 * @property BichuvSaldo[] $bichuvSaldos
 * @property MusteriType $musteriType
 * @property ToquvDocuments[] $toquvDocuments
 * @property ToquvOrders[] $toquvOrders
 * @property ToquvSaldo[] $toquvSaldos
 * @property string $token [varchar(255)]
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
            [['add_info', 'address'], 'string'],
            [['musteri_type_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 50],
            [['director'], 'string', 'max' => 200],
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
        ];
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
    public static function getMyMusteri()
    {
        $musteri = ToquvMusteri::find()->asArray()->all(); //where(['created_by' => Yii::$app->user->getId()])->
        return ArrayHelper::map($musteri,'id','name');
    }

    /**
     * @return array
     */
    public function getAllMusteriTypes()
    {
        $musteriType = ToquvMusteriType::find()->asArray()->all();
        return ArrayHelper::map($musteriType,'id','name');
    }
}
