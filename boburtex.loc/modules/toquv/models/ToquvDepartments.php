<?php

namespace app\modules\toquv\models;

use app\modules\base\models\MoiRelDept;
use Yii;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvItemBalance;
use app\models\Notifications;
use app\modules\bichuv\models\BichuvSaldo;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_departments".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string $tel
 * @property string $address
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property string $token
 * @property int $type
 *@property int $company_categories_id
 *
 * @property BichuvDoc[] $bichuvDocs
 * @property BichuvDoc[] $bichuvDocs0
 * @property BichuvItemBalance[] $bichuvItemBalances
 * @property BichuvSaldo[] $bichuvSaldos
 * @property MoiRelDept[] $moiRelDepts
 * @property Notifications[] $notifications
 * @property Notifications[] $notifications0
 * @property ToquvDocuments[] $toquvDocuments
 * @property ToquvDocuments[] $toquvDocuments0
 * @property ToquvItemBalance[] $toquvItemBalances
 * @property ToquvItemBalance[] $toquvItemBalances0
 * @property ToquvSaldo[] $toquvSaldos
 * @property ToquvUserDepartment[] $toquvUserDepartments
 */
class ToquvDepartments extends BaseModel
{
    const PRODUCTION = 1;
    const SKLAD  = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['token', 'unique'],
            [['name', 'token'], 'required'],
            [['parent', 'status', 'created_at', 'updated_at', 'created_by', 'type', 'company_categories_id'], 'integer'],
            [['address'], 'string'],
            [['name', 'tel', 'token'], 'string', 'max' => 255],
            [['token'], 'unique'],
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
            'parent' => Yii::t('app', 'Parent'),
            'tel' => Yii::t('app', 'Tel'),
            'address' => Yii::t('app', 'Address'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'token' => Yii::t('app', 'Token'),
            'type' => Yii::t('app', 'Type'),
            'company_categories_id' => Yii::t('app', 'Company Categories ID'),
        ];
    }
    public static function getList($key = null, $id = null, $token = null){
        $list = static::find()->where(['status'=>static::STATUS_ACTIVE]);
        if($token){
            $list = $list->andWhere(['token'=>$token]);
        }
        $list = $list->all();
        $result = ArrayHelper::map($list,'id','name');

        if(!empty($key)){
            if($key=='options') {
                $options = ArrayHelper::map($list,'id',function($model) use ($id){
                    return ($id && $model->company_categories_id == $id)?['cat_id' => $model->company_categories_id]:['cat_id' => $model->company_categories_id,'disabled'=>'', 'class'=>'hidden'];
                });
                return $options;
            }
            else {
                return $result[$key];
            }
        }
        return $result;
    }
    public static function getTypeList($key = null){
        $result = [
            self::PRODUCTION   => Yii::t('app','Ishlab chiqarish'),
            self::SKLAD => Yii::t('app','Ombor')
        ];
        if(!empty($key)){
            return $result[$key];
        }
        return $result;
    }

    public static function getMusteriAddressByParentId($parent_id){
        $result = self::find()
            ->select(["tdma.*"])
            ->from("toquv_departments td")
            ->innerJoin("toquv_department_musteri_address tdma", "`tdma`.`toquv_department_id` = `td`.`id`")
            ->where(['tdma.toquv_department_id' => $parent_id])
            ->asArray()
            ->all();
        foreach ($result as $k => $v) {
            $result[$k]['status'] = \app\modules\toquv\models\ToquvDepartmentMusteriAddress::getStatusList($v['status']);
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDocs()
    {
        return $this->hasMany(BichuvDoc::className(), ['from_department' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDocs0()
    {
        return $this->hasMany(BichuvDoc::className(), ['to_department' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvItemBalances()
    {
        return $this->hasMany(BichuvItemBalance::className(), ['department_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoiRelDepts()
    {
        return $this->hasMany(MoiRelDept::className(), ['toquv_departments_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvSaldos()
    {
        return $this->hasMany(BichuvSaldo::className(), ['department_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notifications::className(), ['dept_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications0()
    {
        return $this->hasMany(Notifications::className(), ['dept_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocuments()
    {
        return $this->hasMany(ToquvDocuments::className(), ['from_department' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvDocuments0()
    {
        return $this->hasMany(ToquvDocuments::className(), ['to_department' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvItemBalances()
    {
        return $this->hasMany(ToquvItemBalance::className(), ['department_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvItemBalances0()
    {
        return $this->hasMany(ToquvItemBalance::className(), ['to_department' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvSaldos()
    {
        return $this->hasMany(ToquvSaldo::className(), ['department_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvUserDepartments()
    {
        return $this->hasMany(ToquvUserDepartment::className(), ['department_id' => 'id']);
    }
}
