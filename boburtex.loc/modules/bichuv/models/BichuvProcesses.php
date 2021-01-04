<?php

namespace app\modules\bichuv\models;

use app\models\Users;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_processes".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $is_countable
 * @property string $slug
 *
 * @property BichuvDetailTypes[] $bichuvDetailTypes
 * @property BichuvNastelDetailItems[] $bichuvNastelDetailItems
 * @property BichuvNastelProcesses[] $bichuvNastelProcesses
 * @property BichuvProcessesUsers[] $bichuvProcessesUsers
 * @property Users[] $users
 * @property BichuvTables[] $bichuvTables
 */
class BichuvProcesses extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_processes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'created_by', 'created_at', 'updated_at', 'is_countable'], 'integer'],
            [['add_info'], 'string'],
            [['name', 'slug'], 'string', 'max' => 255],
            [ 'slug', 'unique'],
        ];
    }
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => SluggableBehavior::className(),
                    'attribute' => 'name',
                    'ensureUnique' => true
                ]
            ]
        );
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_countable' => Yii::t('app', 'Is Countable'),
            'slug' => Yii::t('app', 'Slug'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDetailTypes()
    {
        return $this->hasMany(BichuvDetailTypes::className(), ['bichuv_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelDetailItems()
    {
        return $this->hasMany(BichuvNastelDetailItems::className(), ['bichuv_processes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvNastelProcesses()
    {
        return $this->hasMany(BichuvNastelProcesses::className(), ['bichuv_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvProcessesUsers()
    {
        return $this->hasMany(BichuvProcessesUsers::className(), ['bichuv_processes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['id' => 'users_id'])->viaTable('bichuv_processes_users', ['bichuv_processes_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvTables()
    {
        return $this->hasMany(BichuvTables::className(), ['bichuv_processes_id' => 'id']);
    }

    public static function getList($id = null){

        $list = "";
        if($id === null){
            $list = static::find();
        }else{
            $list = $list->andWhere(['id' => $id]);
        }
        $list = $list->asArray()->all();
        return $list;
    }

    public static function  getListMap(){
        $list = self::getList();
        return ArrayHelper::map($list, 'id', 'name');
    }


}
