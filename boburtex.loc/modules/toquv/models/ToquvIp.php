<?php

namespace app\modules\toquv\models;

use app\models\Users;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_ip".
 *
 * @property int $id
 * @property string $name
 * @property int $ne_id
 * @property int $thread_id
 * @property int $color_id
 * @property string $barcode
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property ToquvIpColor $color
 * @property ToquvNe $ne
 * @property ToquvThread $thread
 * @property ToquvIpTarkibi[] $toquvIpTarkibis
 */
class ToquvIp extends BaseModel
{
    public $ip_tarkibi;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_ip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ne_id', 'thread_id','color_id'], 'required'],
            [['ne_id', 'thread_id', 'color_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['barcode'], 'string', 'max' => 100],
            [['color_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => ToquvIpColor::className(),
                'targetAttribute' => ['color_id' => 'id']],
            [['ne_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => ToquvNe::className(),
                'targetAttribute' => ['ne_id' => 'id']],
            [['thread_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => ToquvThread::className(),
                'targetAttribute' => ['thread_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Fabrika nomi'),
            'ne_id' => Yii::t('app', 'Ne ID'),
            'thread_id' => Yii::t('app', 'Thread ID'),
            'color_id' => Yii::t('app', 'Color ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'neName' => Yii::t('app', 'Ne ID'),
            'colorName' => Yii::t('app', 'Color Name'),
            'threadName' => Yii::t('app', 'Thread ID'),
            'rawMaterialConsist' => Yii::t('app','Fabric Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(ToquvIpColor::className(), ['id' => 'color_id']);
    }

    public function getColorName()
    {
        return $this->color->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNe()
    {
        return $this->hasOne(ToquvNe::className(), ['id' => 'ne_id']);
    }

    public function getNeName()
    {
        return $this->ne->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(ToquvThread::className(), ['id' => 'thread_id']);
    }

    public function getThreadName()
    {
        return $this->thread->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvIpTarkibis()
    {
        return $this->hasMany(ToquvIpTarkibi::className(), ['ip_id' => 'id'])->asArray();
    }
    public function getToquvIpTarkibi()
    {
        return $this->hasMany(ToquvIpTarkibi::className(), ['ip_id' => 'id']);
    }

    public function getAllFabricTypes() {
        return FabricTypes::getAllTypes();
    }

    public function getAllNe()
    {
        $ne = ToquvNe::find()->all();

        return ArrayHelper::map($ne,'id','name');
    }

    public function getAllThread()
    {
        $ne = ToquvThread::find()->all();

        return ArrayHelper::map($ne,'id','name');
    }
    public function getAllColors()
    {
        $ne = ToquvIpColor::find()->all();

        return ArrayHelper::map($ne,'id','name');
    }

    public function getUserName(){
        $user = Users::findOne($this->created_by);
        if($user !== null){
            return $user->username;
        }
        return null;
    }
    public function getFullName(){
        $name = $this->name ." - ". $this->ne->name . " - " . $this->thread->name . " - ". $this->color->name;
        return $name;
    }
    public static function getAllTypes()
    {
        $types = self::find()->all();

        return ArrayHelper::map($types,'id','name');
    }

    public static function getFullNameAllTypes()
    {
        $types = self::find()->where(['status' => 1])->all();

        $arr = [];
        foreach ($types as $type)
        {
            $arr[$type['id']] = $type['name'].'-'.$type['neName'].'-'.$type['colorName'].'-'.$type['threadName'];
        }
        return $arr;

    }
    //Ip tarkibi
    public function getRawMaterialConsist()
    {
        $consist = $this->toquvIpTarkibi;
        $name = '';
        foreach ($consist as $key){
            $name .= $key->fullName;
        }
        return $name;
    }
}
