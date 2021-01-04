<?php

namespace app\modules\base\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "model_types".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property int $status
 * @property int $level
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ModelsList[] $modelsLists
 * @property ModelsList[] $modelsLists0
 * @property ModelsList[] $modelsLists1
 */
class ModelTypes extends BaseModel
{
    use \kartik\tree\models\TreeTrait; /*{
        isDisabled as parentIsDisabled; // note the alias
    }*/

    /**
     * @var string the classname for the TreeQuery that implements the NestedSetQueryBehavior.
     * If not set this will default to `kartik	ree\models\TreeQuery`.
     */
    public static $treeQueryClass; // change if you need to set your own TreeQuery

    /**
     * @var bool whether to HTML encode the tree node names. Defaults to `true`.
     */
    public $encodeNodeNames = true;

    /**
     * @var bool whether to HTML purify the tree node icon content before saving.
     * Defaults to `true`.
     */
    public $purifyNodeIcons = true;

    /**
     * @var array activation errors for the node
     */
    public $nodeActivationErrors = [];

    /**
     * @var array node removal errors
     */
    public $nodeRemovalErrors = [];

    /**
     * @var bool attribute to cache the `active` state before a model update. Defaults to `true`.
     */
    public $activeOrig = true;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'model_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['parent', 'status', 'created_by', 'level', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['token', 'default'],
            ['token', 'unique']
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
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsLists()
    {
        return $this->hasMany(ModelsList::className(), ['type_2x_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsLists0()
    {
        return $this->hasMany(ModelsList::className(), ['type_child_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsLists1()
    {
        return $this->hasMany(ModelsList::className(), ['type_id' => 'id']);
    }

    public function getParents($level = 1){
        $parents = self::find()->where(['status' => self::STATUS_ACTIVE, 'level' => $level])->asArray()->all();
        return ArrayHelper::map($parents,'id','name');
    }

    public function getParent(){
        if(!empty($this->parent)){
            $mType = ModelTypes::find()->where(['parent' => $this->parent])->asArray()->one();
            if(!empty($mType)){
                return $mType['name'];
            }
        }
        return null;
    }

    /**
     * TreeView uchun query qaytaradi
     * @return ActiveQuery
     */
    public static function getQueryForTreeView(): ActiveQuery
    {
        return static::find()->addOrderBy('root, lft');
    }
}
