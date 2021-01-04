<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%base_pattern_mini_postal}}".
 *
 * @property int $id
 * @property int $base_patterns_id
 * @property double $loss
 * @property string $name
 * @property int $size
 * @property string $extension
 * @property string $type
 * @property string $path
 *
 * @property BasePatterns $basePatterns
 * @property BasePatternMiniPostalSizes[] $basePatternMiniPostalSizes
 */
class BasePatternMiniPostal extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%base_pattern_mini_postal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['base_patterns_id', 'size'], 'integer'],
            [['loss'], 'number'],
            ['file','file'],
            [['name', 'path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 120],
            [['base_patterns_id'], 'exist', 'skipOnError' => true, 'targetClass' => BasePatterns::className(), 'targetAttribute' => ['base_patterns_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'base_patterns_id' => Yii::t('app', 'Base Patterns ID'),
            'loss' => Yii::t('app', "Yo'qotishlar foizi"),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'extension' => Yii::t('app', 'Extension'),
            'type' => Yii::t('app', 'Type'),
            'path' => Yii::t('app', 'Path'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatterns()
    {
        return $this->hasOne(BasePatterns::className(), ['id' => 'base_patterns_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasePatternMiniPostalSizes()
    {
        return $this->hasMany(BasePatternMiniPostalSizes::className(), ['base_pattern_mini_postal_id' => 'id'])->joinWith('size');
    }
    public function getSizeList(){
        $sizes = BasePatternMiniPostalSizes::find()->select('size_id')->where(['base_pattern_mini_postal_id'=>$this->id])->asArray()->all();
        $list = [];
        if(!empty($sizes)){
            return ArrayHelper::getColumn($sizes,'size_id');
        }
        return $list;
    }
}
