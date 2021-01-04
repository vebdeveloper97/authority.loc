<?php


namespace app\modules\bichuv\models;

/**
 * Class TayyorlovNastelAcs
 * @package app\modules\bichuv\models
 *
 * @property int $id
 * @property string $nastel_no
 * @property int $acs_doc_id
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property-read \yii\db\ActiveQuery $acsDoc
 */
class TayyorlovNastelAcs extends BaseModel
{
    public static function tableName()
    {
        return 'tayyorlov_nastel_acs';
    }

    public function rules()
    {
        return [
            ['nastel_no', 'trim'],
            [['nastel_no', 'acs_doc_id'], 'required'],
            ['nastel_no', 'string', 'max' => 20],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['acs_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::class, 'targetAttribute' => ['acs_doc_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcsDoc() {
        return $this->hasOne(BichuvDoc::class, ['id' => 'acs_doc_id']);
    }
}