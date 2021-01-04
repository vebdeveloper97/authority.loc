<?php

namespace app\modules\toquv\models;

use app\modules\tikuv\models\TikuvOutcomeProducts;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sort_name".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 *
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 * @property ToquvKalite[] $toquvKalites
 */
class SortName extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sort_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['code','string','max' => 50],
            ['code','unique'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 50],
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
            'code' => Yii::t('app', 'Code'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['sort_type_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToquvKalites()
    {
        return $this->hasMany(ToquvKalite::className(), ['sort_name_id' => 'id']);
    }

    public static function getSortList(){
        $sorts = self::find()->asArray()->all();
        if (!empty($sorts)){
            return $sorts;
        }else{
            return false;
        }
    }

    public static function getSortListMap(){
        $sorts = self::getSortList();
        if (!empty($sorts)){
            return ArrayHelper::map($sorts,'id','name');
        }else{
            return [];
        }
    }
}
