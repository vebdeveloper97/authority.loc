<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "boyahane_siparis_subpart".
 *
 * @property int $id
 * @property int $siparis_id
 * @property int $part_id
 * @property string $child_partiya_no
 * @property int $pus_fine_id
 * @property int $ne_id
 * @property string $gr_m2
 * @property int $thread_id
 * @property string $thread_consist
 * @property int $pamuk
 * @property int $polyester
 * @property int $lycra
 * @property int $raw_material_id
 * @property int $material_type_id
 * @property int $cekmezlik
 * @property int $roll
 * @property int $accepted_roll
 * @property double $weight
 * @property double $accepted_weight
 * @property double $ribana
 * @property int $yaka_soni
 * @property int $shardon
 * @property int $mayizli
 * @property int $samo_weav
 * @property int $fiksa
 * @property int $emzin
 * @property int $selikon
 * @property int $firchas
 * @property int $yakma
 * @property int $baski
 * @property int $baski_id
 * @property int $baski_save_user
 * @property string $ham_en
 * @property string $material_width
 * @property string $finish_gr
 * @property int $makine
 * @property string $add_info
 * @property int $accepted_status
 * @property int $user_uid
 * @property int $partileme_user_uid
 * @property string $partileme_date
 * @property int $accepted_user
 * @property string $reg_date
 * @property string $accepted_date
 * @property int $sevk_holati
 * @property int $tamirga_ketgan_holati
 * @property string $tamir_vaqt
 * @property int $tamir_user
 * @property int $tamirdan_qaytgan_holati
 * @property int $tamir_sabab_id
 * @property int $tamir_old_id
 * @property string $tamir_izoh
 *
 * @property BichuvSubDocItems[] $bichuvSubDocItems
 */
class BoyahaneSiparisSubpart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'boyahane_siparis_subpart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['siparis_id', 'part_id', 'child_partiya_no', 'thread_consist', 'accepted_roll', 'weight', 'accepted_weight', 'ribana', 'yaka_soni', 'samo_weav', 'fiksa', 'emzin', 'selikon', 'firchas', 'yakma', 'baski', 'add_info', 'accepted_status', 'user_uid', 'tamirga_ketgan_holati', 'tamir_user', 'tamir_sabab_id', 'tamir_izoh'], 'required'],
            [['siparis_id', 'part_id', 'pus_fine_id', 'ne_id', 'thread_id', 'pamuk', 'polyester', 'lycra', 'raw_material_id', 'material_type_id', 'cekmezlik', 'roll', 'accepted_roll', 'yaka_soni', 'shardon', 'mayizli', 'samo_weav', 'fiksa', 'emzin', 'selikon', 'firchas', 'yakma', 'baski', 'baski_id', 'baski_save_user', 'makine', 'accepted_status', 'user_uid', 'partileme_user_uid', 'accepted_user', 'sevk_holati', 'tamirga_ketgan_holati', 'tamir_user', 'tamirdan_qaytgan_holati', 'tamir_sabab_id', 'tamir_old_id'], 'integer'],
            [['weight', 'accepted_weight', 'ribana'], 'number'],
            [['partileme_date', 'reg_date', 'accepted_date', 'tamir_vaqt'], 'safe'],
            [['tamir_izoh'], 'string'],
            [['child_partiya_no', 'gr_m2', 'ham_en', 'material_width', 'finish_gr'], 'string', 'max' => 50],
            [['thread_consist'], 'string', 'max' => 100],
            [['add_info'], 'string', 'max' => 300],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBichuvSubDocItems()
    {
        return $this->hasMany(BichuvSubDocItems::className(), ['bss_id' => 'id']);
    }
}
