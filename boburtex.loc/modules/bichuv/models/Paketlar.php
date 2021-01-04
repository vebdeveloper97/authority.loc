<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "paketlar".
 *
 * @property int $id
 * @property int $subpart_id
 * @property int $rulon
 * @property double $kg
 * @property double $metr
 * @property int $sort
 * @property string $comment
 * @property string $reg_date
 * @property int $user_uid
 *
 * @property BichuvSubDocItems[] $bichuvSubDocItems
 */
class Paketlar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paketlar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subpart_id', 'rulon', 'kg', 'metr', 'sort', 'comment', 'user_uid'], 'required'],
            [['subpart_id', 'rulon', 'sort', 'user_uid'], 'integer'],
            [['kg', 'metr'], 'number'],
            [['comment'], 'string'],
            [['reg_date'], 'safe'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvSubDocItems()
    {
        return $this->hasMany(BichuvSubDocItems::className(), ['paket_id' => 'id']);
    }
}
