<?php

namespace app\modules\boyoq\models;

use Yii;

/**
 * This is the model class for table "boyahane_siparis_part".
 *
 * @property int $id
 * @property int $siparis_id
 * @property string $partiya_no
 * @property int $color_group_id
 * @property int $boyama_turi
 * @property int $color_tone
 * @property int $color_type
 * @property int $color_id
 * @property int $color_id2
 * @property int $yumshatma_id
 * @property int $product_id
 * @property int $user_uid
 * @property string $reg_date
 * @property int $partileme_user_uid
 * @property string $partileme_date
 * @property string $add_info
 */
class BoyahaneSiparisPart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'boyahane_siparis_part';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siparis_id', 'partiya_no', 'color_group_id', 'color_tone', 'color_type', 'color_id', 'color_id2', 'yumshatma_id', 'product_id', 'user_uid'], 'required'],
            [['siparis_id', 'color_group_id', 'boyama_turi', 'color_tone', 'color_type', 'color_id', 'color_id2', 'yumshatma_id', 'product_id', 'user_uid', 'partileme_user_uid'], 'integer'],
            [['reg_date', 'partileme_date'], 'safe'],
            [['add_info'], 'string'],
            [['partiya_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'siparis_id' => Yii::t('app', 'Siparis ID'),
            'partiya_no' => Yii::t('app', 'Partiya No'),
            'color_group_id' => Yii::t('app', 'Color Group ID'),
            'boyama_turi' => Yii::t('app', 'Boyama Turi'),
            'color_tone' => Yii::t('app', 'Color Tone'),
            'color_type' => Yii::t('app', 'Color Type'),
            'color_id' => Yii::t('app', 'Color ID'),
            'color_id2' => Yii::t('app', 'Color Id2'),
            'yumshatma_id' => Yii::t('app', 'Yumshatma ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'user_uid' => Yii::t('app', 'User Uid'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'partileme_user_uid' => Yii::t('app', 'Partileme User Uid'),
            'partileme_date' => Yii::t('app', 'Partileme Date'),
            'add_info' => Yii::t('app', 'Add Info'),
        ];
    }
}
