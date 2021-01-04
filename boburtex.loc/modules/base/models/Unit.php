<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string $name
 * @property string $add_info
 * @property string $code
 *
 * @property BichuvAcs[] $bichuvAcs
 * @property TikuvGoodsDoc[] $tikuvGoodsDocs
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 * @property ToquvDocumentItems[] $toquvDocumentItems
 * @property ToquvServicePricing[] $toquvServicePricings
 * @property WhItems[] $whItems
 */
class Unit extends \yii\db\ActiveRecord
{
    const CODE_DONA = 'DONA';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
    }

    public static function getIdByCode(string $code)
    {
        return static::find()
            ->select('id')
            ->andWhere(['code' => $code])
            ->scalar();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['add_info'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 20],
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
            'code' => Yii::t('app', 'Code'),
        ];
    }

    public static function getList($id=null,$array=false)
    {
        $list = self::find();
        if($id){
            $list = $list->where(['unit_id'=>$id]);
        }
        $list = $list->asArray()->all();

        return ArrayHelper::map($list,'id','name');
    }
}
