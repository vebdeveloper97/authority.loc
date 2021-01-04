<?php

namespace app\modules\bichuv\models;

use app\modules\tikuv\models\TikuvKonveyer;
use Yii;

/**
 * This is the model class for table "tikuv_konveyer_bichuv_doc".
 *
 * @property double $indeks
 * @property int $tikuv_konveyer_id
 * @property int $bichuv_doc_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 *
 * @property BichuvDoc $bichuvDoc
 * @property TikuvKonveyer $tikuvKonveyer
 */
class TikuvKonveyerBichuvDoc extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tikuv_konveyer_bichuv_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indeks'], 'number'],
            [['tikuv_konveyer_id', 'bichuv_doc_id'], 'required'],
            [['tikuv_konveyer_id', 'bichuv_doc_id', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['tikuv_konveyer_id', 'bichuv_doc_id'], 'unique', 'targetAttribute' => ['tikuv_konveyer_id', 'bichuv_doc_id']],
            [['bichuv_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bichuv_doc_id' => 'id']],
            [['tikuv_konveyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => TikuvKonveyer::className(), 'targetAttribute' => ['tikuv_konveyer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'indeks' => Yii::t('app', 'Indeks'),
            'tikuv_konveyer_id' => Yii::t('app', 'Tikuv Konveyer ID'),
            'bichuv_doc_id' => Yii::t('app', 'Bichuv Doc ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvDoc()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'bichuv_doc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTikuvKonveyer()
    {
        return $this->hasOne(TikuvKonveyer::className(), ['id' => 'tikuv_konveyer_id']);
    }
}
