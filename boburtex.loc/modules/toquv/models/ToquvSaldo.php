<?php

namespace app\modules\toquv\models;

use app\models\PaymentMethod;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "toquv_saldo".
 *
 * @property int $id
 * @property string $credit1
 * @property string $credit2
 * @property string $debit1
 * @property string $debit2
 * @property int $musteri_id
 * @property int $department_id
 * @property string $operation
 * @property string $payment_method
 * @property string $comment
 * @property string $reg_date
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $pb_id
 * @property int $td_id
 *
 * @property ToquvDepartments $department
 * @property ToquvMusteri $musteri
 * @property PulBirligi $pb
 * @property ToquvDocuments $td
 */
class ToquvSaldo extends BaseModel
{
    public $summa;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'toquv_saldo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['musteri_id','operation','summa', 'payment_method'],'required'],
            [['credit1', 'credit2', 'debit1', 'debit2'], 'number'],
            [['musteri_id', 'department_id', 'status', 'created_by', 'created_at', 'updated_at', 'pb_id', 'td_id', 'payment_method'], 'integer'],
            [['comment'], 'string'],
            [['reg_date','summa'], 'safe'],
            [['operation'], 'string', 'max' => 255],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
            [['td_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDocuments::className(), 'targetAttribute' => ['td_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'credit1' => Yii::t('app', 'Credit1'),
            'credit2' => Yii::t('app', 'Credit2'),
            'debit1' => Yii::t('app', 'Debit1'),
            'debit2' => Yii::t('app', 'Debit2'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'department_id' => Yii::t('app', 'From Department'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'operation' => Yii::t('app', 'Kirim/Chiqim'),
            'comment' => Yii::t('app', 'Add Info'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'td_id' => Yii::t('app', 'Dokument №'),
            'summa' => Yii::t('app', 'Summa'),
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $date =  date('Y-m-d', strtotime($this->reg_date));
            $currentTime = date('H:i:s');
            $this->reg_date = date('Y-m-d H:i:s', strtotime($date.' '.$currentTime));
            return true;
        }else{
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = date('d.m.Y H:i:s', strtotime($this->reg_date));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'department_id']);
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
    public function getPb()
    {
        return $this->hasOne(PulBirligi::className(), ['id' => 'pb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTd()
    {
        return $this->hasOne(ToquvDocuments::className(), ['id' => 'td_id']);
    }

    /**
     * @return array
     */
    public function getPaymentMethods()
    {
        return PaymentMethod::getData();
    }

    /**
     * @return array
     */
    public static function getAllDocuments()
    {
        $ne = self::find()->all();

        return ArrayHelper::map($ne,'id','id');
    }

    public function getPulBirligi()
    {
        $ne = PulBirligi::find()->all();

        return ArrayHelper::map($ne,'id','name');
    }

    public function getToquvDocs()
    {
        $return = [];
        if ($toquv_docs = ToquvDocuments::find()->where(['document_type'=>ToquvDocuments::DOC_TYPE_INCOMING])->asArray()->all()) {
            foreach ($toquv_docs as $i => $doc) {
                $return[$doc['id']] = $doc['doc_number'] . " " . $doc['reg_date'];
            }
        }

        return $return;
    }


}
