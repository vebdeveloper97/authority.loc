<?php

namespace app\modules\bichuv\models;

use Yii;

/**
 * This is the model class for table "bichuv_sub_doc_items".
 *
 * @property int $id
 * @property int $doc_item_id
 * @property int $musteri_id
 * @property int $bss_id
 * @property int $paket_id
 * @property string $musteri_party_no
 * @property string $party_no
 * @property string $roll_weight
 * @property string $roll_order
 * @property string $en
 * @property string $gramaj
 * @property string $ne
 * @property string $thread
 * @property string $pus_fine
 * @property string $ctone
 * @property string $color_id
 * @property string $pantone
 * @property string $mato
 * @property string $model
 * @property string $paketlama
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BoyahaneSiparisSubpart $bss
 * @property BichuvDocItems $docItem
 * @property Musteri $musteri
 * @property Paketlar $paket
 * @property string $thread_consist [varchar(20)]
 * @property string $first_weight [decimal(10,3)]
 * @property string $roll_count [decimal(20,2)]
 * @property int $rm_id [int(11)]
 * @property int $ne_id [int(11)]
 * @property int $thread_id [int(11)]
 * @property int $pus_fine_id [int(11)]
 * @property int $c_id [int(11)]
 */
class BichuvSubDocItems extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_sub_doc_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_item_id','rm_id','ne_id','thread_id','pus_fine_id','c_id', 'musteri_id', 'bss_id', 'paket_id', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['roll_weight','roll_count','en', 'gramaj','first_weight'], 'number'],
            [['musteri_party_no', 'party_no','thread_consist'], 'string', 'max' => 20],
            [['roll_order', 'pus_fine'], 'string', 'max' => 15],
            [['ne'], 'string', 'max' => 10],
            [['thread', 'ctone'], 'string', 'max' => 50],
            [['color_id', 'mato'], 'string', 'max' => 100],
            [['pantone'], 'string', 'max' => 50],
            [['model', 'paketlama'], 'string', 'max' => 150],
            [['bss_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoyahaneSiparisSubpart::className(), 'targetAttribute' => ['bss_id' => 'id']],
            [['doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDocItems::className(), 'targetAttribute' => ['doc_item_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['paket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paketlar::className(), 'targetAttribute' => ['paket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_item_id' => Yii::t('app', 'Doc Item ID'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'bss_id' => Yii::t('app', 'Bss ID'),
            'paket_id' => Yii::t('app', 'Paket ID'),
            'thread_consist' => Yii::t('app','Mato tarkibi'),
            'musteri_party_no' => Yii::t('app', 'Musteri Party No'),
            'party_no' => Yii::t('app', 'Party No'),
            'roll_weight' => Yii::t('app', 'Roll Weight'),
            'roll_order' => Yii::t('app', 'Roll Order'),
            'en' => Yii::t('app', 'En'),
            'gramaj' => Yii::t('app', 'Gramaj'),
            'ne' => Yii::t('app', 'Ne'),
            'thread' => Yii::t('app', 'Thread'),
            'pus_fine' => Yii::t('app', 'Pus Fine'),
            'ctone' => Yii::t('app', 'Ctone'),
            'color_id' => Yii::t('app', 'Color ID'),
            'pantone' => Yii::t('app', 'Pantone'),
            'mato' => Yii::t('app', 'Mato'),
            'model' => Yii::t('app', 'Model'),
            'paketlama' => Yii::t('app', 'Paketlama'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBss()
    {
        return $this->hasOne(BoyahaneSiparisSubpart::className(), ['id' => 'bss_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocItem()
    {
        return $this->hasOne(BichuvDocItems::className(), ['id' => 'doc_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(BichuvMusteri::className(), ['id' => 'musteri_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaket()
    {
        return $this->hasOne(Paketlar::className(), ['id' => 'paket_id']);
    }

    /**
     * @param $musteriId
     * @return int
     */
    public static function getLastParty($musteriId)
    {
        $res = BichuvDocItems::find()
            ->select(['bichuv_doc_items.party_no'])
            ->leftJoin('bichuv_doc','bichuv_doc.id = bichuv_doc_items.bichuv_doc_id')
            ->where(['bichuv_doc.status' => self::STATUS_SAVED, 'bichuv_doc.musteri_id' => $musteriId])
            ->asArray()
            ->orderBy(['bichuv_doc_items.id' => SORT_DESC])
            ->limit(1)
            ->one();
        if(!empty($res)){
            return (int)$res['party_no'] + 1;
        }
        return 1;
    }

    /**
     * @param $item
     * @return int|mixed|null
     */
    public static function getEntityId($item)
    {
        $entity = BichuvMatoInfo::find()->where([
            'rm_id' => !empty($item['rm_id'])?$item['rm_id']:NULL,
            'ne_id' => !empty($item['ne_id'])?$item['ne_id']:NULL,
            'thread_id' => !empty($item['thread_id'])?$item['thread_id']:NULL,
            'pus_fine_id' => !empty($item['pus_fine_id'])?$item['pus_fine_id']:NULL,
            'color_id' => !empty($item['c_id'])?$item['c_id']:NULL,
            'en' => !empty($item['en'])?$item['en']:NULL,
            'gramaj' => !empty($item['gramaj'])?$item['gramaj']:NULL
        ])->asArray()->one();
        if(!empty($entity)){
            return $entity['id'];
        }else{
            $model = new BichuvMatoInfo();
            $model->setAttributes([
                'rm_id' => $item['rm_id'],
                'ne_id' => $item['ne_id'],
                'thread_id' => $item['thread_id'],
                'pus_fine_id' => $item['pus_fine_id'],
                'color_id' => $item['c_id'],
                'en' => $item['en'],
                'gramaj' => $item['gramaj']
            ]);
            if($model->save()){
                return $model->id;
            }
        }
        return 1;
    }
}
