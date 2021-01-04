<?php

namespace app\models;

use app\modules\base\models\ModelOrders;
use app\modules\hr\models\HrDepartments;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocuments;
use app\widgets\helpers\Telegram;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%notifications}}".
 *
 * @property int $id
 * @property int $doc_id
 * @property int $type
 * @property string $body
 * @property string $subject
 * @property int $dept_from
 * @property int $dept_to
 * @property int $from
 * @property int $to
 * @property string $expire
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $url
 * @property string $reg_date
 * @property int $created_by
 * @property int $updated_by
 * @property string $module
 * @property string $actions
 * @property string $controllers
 * @property string $pharams [json]
 *
 * @property ToquvDepartments $deptFrom
 * @property ToquvDepartments $deptTo
 */
class Notifications extends \yii\db\ActiveRecord
{
    public $users_id = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notifications}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_id', 'type', 'dept_from', 'dept_to', 'from', 'to', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['body'], 'string'],
            [['expire', 'reg_date', 'pharams'], 'safe'],
            [['subject', 'url', 'module', 'actions', 'controllers'], 'string', 'max' => 255],
            [['dept_from'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['dept_from' => 'id']],
            [['dept_to'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['dept_to' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'doc_id' => Yii::t('app', 'Doc ID'),
            'type' => Yii::t('app', 'Type'),
            'body' => Yii::t('app', 'Body'),
            'subject' => Yii::t('app', 'Subject'),
            'dept_from' => Yii::t('app', 'Dept From'),
            'dept_to' => Yii::t('app', 'Dept To'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'expire' => Yii::t('app', 'Expire'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'url' => Yii::t('app', 'Url'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'module' => Yii::t('app', 'Module'),
            'actions' => Yii::t('app', 'Actions'),
            'controllers' => Yii::t('app', 'Controllers'),
            'pharams' => Yii::t('app', 'Pharams'),
        ];
    }

    public function saveNotice($params = [], $isStatus = false){
        if(!empty($params)){
            $transaction = Yii::$app->db->beginTransaction();
            $updateOrders = true;
            $saved = false;
            try{
                if(!$isStatus){
                    $updateOrders = ModelOrders::updateAll(
                        [
                            'orders_status' => ModelOrders::STATUS_SAVED,
                            'status' => ModelOrders::STATUS_SAVED,
                        ],
                        [
                            'id' => $params['id']
                        ]
                    );
                }
                else{
                    $updateOrders = true;
                }
                if($updateOrders){
                    $notification = new Notifications();
                    $dep_from = HrDepartments::findOne(['token' => ModelOrders::TOKEN_MARKETING]) ? HrDepartments::findOne(['token' => ModelOrders::TOKEN_MARKETING]) : null;
                    $dep_to = HrDepartments::findOne(['token' => ModelOrders::TOKEN_KONSTRUKT]) ? HrDepartments::findOne(['token' => ModelOrders::TOKEN_KONSTRUKT]) : null;
                    $notification->setAttributes([
                        'doc_id' => $params['id'],
                        'type' => $params['type'] ?? 1,
                        'body' => $params['doc_number']." - Buyurtma Konstruktorga yuborildi",
                        'dept_from' => $dep_from->id,
                        'dept_to' => $dep_to->id,
                        'module' => Yii::$app->controller->module->id,
                        'actions' => Yii::$app->controller->action->id,
                        'controllers' => Yii::$app->controller->id,
                        'pharams' => json_encode(['id' => $params['id']]),
                        'to' => Yii::$app->user->id,
                    ]);
                    if($notification->save()){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Saved Successfully'));
                        return true;
                    }
                    else{
                        Yii::error($notification->getErrors(), 'save');
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Error'));
                        return false;
                    }
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Document Yangilanmadi!'));
                    return false;
                }
            }
            catch(\Exception $e){
                Yii::info('Error Message '.$e->getMessage(),' save');
            }
        }
        else{
            return false;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeptFrom()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'dept_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeptTo()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'dept_to']);
    }
    /**
     * @param bool $insert
     * @return bool|void
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->expire = (!empty($this->expire)) ?  date('Y-m-d H:i:s', strtotime($this->expire)) : null;
            $this->reg_date = (!empty($this->reg_date)) ? date('Y-m-d H:i:s', strtotime($this->reg_date)) : date('Y-m-d H:i:s', time());
            return true;
        }
    }

    public function afterFind()
    {
        $this->expire = date('d.m.Y H:I', strtotime($this->expire));
        parent::afterFind();
    }
    /**
     * @param $key
     * @return mixed|string
     */
    static function getMessageLabels($key){
        $res = [
            ToquvDocuments::DOC_TYPE_INCOMING => Yii::t('app','Sizda yangi kirim hujjati bor'),
            ToquvDocuments::DOC_TYPE_MOVING => Yii::t('app','Sizda yangi kochirish hujjati bor'),
            ToquvDocuments::DOC_TYPE_SELLING => Yii::t('app','Sizda yangi sotish hujjati bor'),
        ];
        return isset($res[$key])?$res[$key]:'';
    }

    /**
     * @params $id
     * @array $notType
     * @int $user_id, $status, $type
     * */
    public function getNotifications($user_id, $status, $type=1, $notType = null)
    {
        $array = [];
        $notification = self::find()
            ->where(['to' => $user_id])
            ->andWhere(['status' => $status])
            ->andWhere(['type' => $type])
            ->andFilterWhere(['not in', 'type', $notType])
            ->asArray();
        $array['count'] = $notification->count();
        $array['result'] = $notification->all();
        return $array;
    }
}
