<?php

namespace app\modules\bichuv\models;

use app\models\PaymentMethod;
use app\modules\toquv\models\PulBirligi;
use app\modules\toquv\models\ToquvDepartments;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bichuv_saldo".
 *
 * @property int $id
 * @property string $credit1
 * @property string $credit2
 * @property string $debit1
 * @property string $debit2
 * @property int $musteri_id
 * @property int $department_id
 * @property int $pb_id
 * @property int $bd_id
 * @property int $payment_method
 * @property string $operation
 * @property string $comment
 * @property string $reg_date
 * @property int $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BichuvDoc $bd
 * @property ToquvDepartments $department
 * @property BichuvMusteri $musteri
 * @property PulBirligi $pb
 */
class BichuvSaldo extends BaseModel
{
    public $summa;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bichuv_saldo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit1', 'credit2', 'debit1', 'debit2'], 'number'],
            [['summa', 'musteri_id', 'reg_date', 'payment_method'], 'required'],
            [['musteri_id', 'department_id', 'pb_id', 'bd_id', 'status', 'created_by', 'created_at', 'updated_at', 'payment_method'], 'integer'],
            [['comment'], 'string'],
            [['reg_date', 'summa'], 'safe'],
            [['operation'], 'string', 'max' => 255],
            [['bd_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvDoc::className(), 'targetAttribute' => ['bd_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => BichuvMusteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['pb_id'], 'exist', 'skipOnError' => true, 'targetClass' => PulBirligi::className(), 'targetAttribute' => ['pb_id' => 'id']],
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
            'department_id' => Yii::t('app', 'Department ID'),
            'pb_id' => Yii::t('app', 'Pb ID'),
            'bd_id' => Yii::t('app', 'Bd ID'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'operation' => Yii::t('app', 'Operation'),
            'comment' => Yii::t('app', 'Comment'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
    public function getBd()
    {
        return $this->hasOne(BichuvDoc::className(), ['id' => 'bd_id']);
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

    public function getBichuvDocs()
    {
        $return = [];
        if ($toquv_docs = BichuvDoc::find()->where(['document_type'=>BichuvDoc::DOC_TYPE_INCOMING])->asArray()->all()) {
            foreach ($toquv_docs as $i => $doc) {
                $return[$doc['id']] = $doc['doc_number'] . " " . $doc['reg_date'];
            }
        }

        return $return;
    }
}
