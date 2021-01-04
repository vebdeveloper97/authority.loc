<?php

namespace app\modules\toquv\models;

use app\models\modules\RolePermission;
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
 * @property ToquvMusteriType $musteriType
 */
class ToquvMusteri extends BaseModel
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
            [['musteri_type_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => ToquvMusteriType::className(),
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
            'name' => Yii::t("app", "Customer name"),
            'add_info' => Yii::t("app", "Add info"),
            'musteri_type_id' => Yii::t("app", "turi"),
            'tel' => Yii::t("app", "phone"),
            'address' => Yii::t("app", "address"),
            'director' => Yii::t("app", "director"),
            'status' => Yii::t("app", "status"),
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'token' => Yii::t('app', 'Token'),
            'code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteriType()
    {
        return $this->hasOne(ToquvMusteriType::className(), ['id' => 'musteri_type_id']);
    }

    public function getMusteris()
    {
        return $this->hasOne(ToquvMusteriType::className(),['id' =>'musteri_type_id']);
    }

    public static function getMyMusteri()
    {
        $musteri = ToquvMusteri::find()->asArray()->all(); //where(['created_by' => Yii::$app->user->getId()])->
        return ArrayHelper::map($musteri,'id','name');
    }

    /**
     * @return array
     */
    public static function getAllMusteriTypes()
    {
        $musteriType = ToquvMusteriType::find()->asArray()->all();
        return ArrayHelper::map($musteriType,'id','name');
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
