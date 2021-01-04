<?php

namespace app\modules\base\models;

use app\modules\tikuv\models\TikuvOutcomeProducts;
use Yii;

/**
 * This is the model class for table "size_type".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property TikuvOutcomeProducts[] $tikuvOutcomeProducts
 *
 * @property Size[] $sizes
 */
class SizeType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'size_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['code','string','max' => 50],
            [['name','code'],'unique'],
            [['name'], 'string', 'max' => 100],
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
    public function getSizes()
    {
        return $this->hasMany(Size::className(), ['size_type_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvOutcomeProducts()
    {
        return $this->hasMany(TikuvOutcomeProducts::className(), ['size_type_id' => 'id']);
    }
    public function saveSize($data){
        $saved = false;
        foreach ($data as $key) {
            if(!empty($key)){
                $saved = false;
                $item = Size::findOne([
                    'size_type_id' => $this->id,
                    'name' => $key['name']
                ]);
                $size = ($item)?$item:new Size();
                $size->setAttributes([
                    'size_type_id' => $this->id,
                    'name' => $key['name'],
                    'code' => $key['code'],
                    'order' => $key['order'],
                ]);
                if($size->save()){
                    $saved = true;
                }
            }
        }
        if ($saved)
            return true;
        return false;
    }
}
