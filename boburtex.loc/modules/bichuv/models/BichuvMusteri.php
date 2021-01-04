<?php

namespace app\modules\bichuv\models;

use app\components\behaviors\log\LogBehavior;
use app\modules\usluga\models\UslugaDoc;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;


/**
 * Class BichuvMusteri
 * @package app\modules\bichuv\models
 * @property int $id [bigint(20)]
 * @property string $name [varchar(255)]
 * @property string $add_info
 * @property int $musteri_type_id [int(11)]
 * @property string $tel [varchar(50)]
 * @property string $address
 * @property string $director [varchar(200)]
 * @property int $status [smallint(6)]
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 * @property int $created_by [int(11)]
 * @property ActiveQuery $musteriType
 * @property mixed $musteris
 * @property array $allMusteriTypes
 * @property string $token [varchar(255)]
 *
 * @property UslugaDoc[] $uslugaDocFrom
 * @property ActiveQuery $serviceBalance
 * @property UslugaDoc[] $uslugaDocTo
 * @property string $code [varchar(50)]
 */
class BichuvMusteri extends BaseModel
{
    public $logIgnoredAttributes = ['created_at','updated_at','created_by'];
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
            [['name', 'musteri_type_id', 'address', 'tel', 'director'], 'required'],
            [['name'], 'unique'],
            [['add_info', 'address', 'token'], 'string'],
            [['musteri_type_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 50],
            [['director'], 'string', 'max' => 200],
            [['musteri_type_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => BichuvMusteriType::className(),
                'targetAttribute' => ['musteri_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t("app", "Name"),
            'add_info' => Yii::t("app", "add_info"),
            'musteri_type_id' => Yii::t("app", "turi"),
            'tel' => Yii::t("app", "phone"),
            'address' => Yii::t("app", "address"),
            'director' => Yii::t("app", "director"),
            'status' => Yii::t("app", "status"),
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
        ];
    }
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            LogBehavior::className()
        ]);
    }

    public static function getMusteriById($id){
        return self::find()
            ->where(['id' => $id])
            ->asArray()
            ->limit(1)
            ->one();
    }
    /**
     * @return ActiveQuery
     */
    public function getMusteriType()
    {
        return $this->hasOne(BichuvMusteriType::className(), ['id' => 'musteri_type_id']);
    }

    public function getMusteris()
    {
        return $this->hasOne(BichuvMusteriType::className(),['id' =>'musteri_type_id']);
    }

    public static function getMyMusteri()
    {
        $musteri = self::find()->asArray()->all(); //where(['created_by' => Yii::$app->user->getId()])->
        return ArrayHelper::map($musteri,'id','name');
    }

    /**
     * @return array
     */
    public function getAllMusteriTypes()
    {
        $musteriType = BichuvMusteriType::find()->asArray()->all();
        return ArrayHelper::map($musteriType,'id','name');
    }
    /**
     * @return ActiveQuery
     */
    public function getUslugaDocFrom()
    {
        return $this->hasMany(UslugaDoc::className(), ['from_musteri' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getUslugaDocTo()
    {
        return $this->hasMany(UslugaDoc::className(), ['to_musteri' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getServiceBalance()
    {
        return $this->hasMany(BichuvServiceItemBalance::className(), ['musteri_id' => 'id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getServiceBalanceFrom()
    {
        return $this->hasMany(BichuvServiceItemBalance::className(), ['from_musteri' => 'id']);
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->token = self::generateToken($this->name);
        }
        return parent::beforeSave($insert);
    }

    static function generateToken($name)
    {
        $chars = ["Q","W","E","R","T","Y","U","I","O","P",
            "A","S","D","F","G","H","J","K","L",
            "Z","X","C","V","B","N","M"];

        $name = mb_strtoupper($name);
        $name = str_replace([' ', '"', '-', "'", "_", "OOO", "MCHJ"], "", $name);
        if (strlen($name) < 3) {
            $name.= $chars[array_rand($chars)]
                .  $chars[array_rand($chars)]
                .  $chars[array_rand($chars)];
        }
        $name = mb_substr($name, 0, 3);

        $name.= $chars[array_rand($chars)]
             .  $chars[array_rand($chars)];
        $name.= rand(1000, 9999);
        $count = self::find()
            ->where(['token' => $name])
            ->count();
        if ( $count != 0 ) {
            self::generateToken($name);
        }
        return $name;
    }

}
