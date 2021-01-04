<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "boyahane_ready_goods".
 *
 * @property int $id
 * @property int $pus_fine
 * @property int $ne_id
 * @property int $raw_material_id
 * @property int $thread_id
 * @property int $rm_type
 * @property string $thread_consists
 * @property int $color_id
 * @property int $color_tone
 * @property int $color_type
 * @property string $finish_en
 * @property string $finish_gramaj
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 */
class BoyahaneReadyGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'boyahane_ready_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pus_fine', 'ne_id', 'raw_material_id', 'thread_id', 'rm_type', 'color_id', 'color_tone', 'color_type', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['thread_consists'], 'string', 'max' => 20],
            [['finish_en', 'finish_gramaj'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'pus_fine' => Yii::t('app', 'Pus Fine'),
            'ne_id' => Yii::t('app', 'Ne ID'),
            'raw_material_id' => Yii::t('app', 'Raw Material ID'),
            'thread_id' => Yii::t('app', 'Thread ID'),
            'rm_type' => Yii::t('app', 'Rm Type'),
            'thread_consists' => Yii::t('app', 'Thread Consists'),
            'color_id' => Yii::t('app', 'Color ID'),
            'color_tone' => Yii::t('app', 'Color Tone'),
            'color_type' => Yii::t('app', 'Color Type'),
            'finish_en' => Yii::t('app', 'Finish En'),
            'finish_gramaj' => Yii::t('app', 'Finish Gramaj'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
