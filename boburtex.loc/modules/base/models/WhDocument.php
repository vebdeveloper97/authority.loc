<?php

namespace app\modules\base\models;

use app\components\OurCustomBehavior;
use app\models\Constants;
use app\models\UserRoles;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\bichuv\models\BichuvItemBalance;
use app\modules\toquv\models\PulBirligi;
use Yii;
use app\modules\toquv\models\ToquvDepartments;
use app\models\Users;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_document".
 *
 * @property int $id
 * @property int $document_type
 * @property int $action
 * @property string $doc_number
 * @property string $reg_date
 * @property int $musteri_id
 * @property string $musteri_responsible
 * @property int $from_department
 * @property int $from_employee
 * @property int $to_department
 * @property int $to_employee
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDepartments $fromDepartment
 * @property Users $fromEmployee
 * @property Musteri $musteri
 * @property ToquvDepartments $toDepartment
 * @property Users $toEmployee
 * @property WhDocumentItems[] $whDocumentItems
 * @property WhItemBalance[] $whItemBalances
 */
class WhDocument extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const DOC_TYPE_INCOMING = 1;
    const DOC_TYPE_MOVING = 2;
    const DOC_TYPE_SELLING = 3;
    const DOC_TYPE_RETURN = 4;
    const DOC_TYPE_OUTGOING = 5;
    const DOC_TYPE_PENDING = 7;
    const DOC_TYPE_MIXING = 8;

    const DOC_TYPE_INCOMING_LABEL = 'kirim';
    const DOC_TYPE_MOVING_LABEL = 'transfer';
    const DOC_TYPE_SELLING_LABEL = 'sell';
    const DOC_TYPE_OUTGOING_LABEL = 'chiqim';
    const DOC_TYPE_RETURN_LABEL = 'qaytarish';
    const DOC_TYPE_PENDING_LABEL = 'pending';
    const DOC_TYPE_MIXING_LABEL = 'mixing';

    public $mixing_item;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type', 'action', 'musteri_id', 'from_department', 'from_employee', 'to_department', 'to_employee', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['reg_date', 'doc_number'], 'required'],
            [['reg_date'], 'safe'],
            [['add_info'], 'string'],
            [['add_info'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_OUTGOING;
            }],
            [['mixing_item'], 'required', 'when' => function ($model) {
                return $model->document_type == $model::DOC_TYPE_MIXING;
            }],
            [['doc_number'], 'string', 'max' => 25],
            [['musteri_responsible'], 'string', 'max' => 255],
            [['from_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['from_department' => 'id']],
            [['from_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['from_employee' => 'id']],
            [['musteri_id'], 'exist', 'skipOnError' => true, 'targetClass' => Musteri::className(), 'targetAttribute' => ['musteri_id' => 'id']],
            [['to_department'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['to_department' => 'id']],
            [['to_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['to_employee' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'document_type' => Yii::t('app', 'Document Type'),
            'action' => Yii::t('app', 'Action'),
            'doc_number' => Yii::t('app', 'Doc Number'),
            'reg_date' => Yii::t('app', 'Reg Date'),
            'musteri_id' => Yii::t('app', 'Musteri ID'),
            'musteri_responsible' => Yii::t('app', 'Musteri Responsible'),
            'from_department' => Yii::t('app', 'From Department'),
            'from_employee' => Yii::t('app', 'From Employee'),
            'to_department' => Yii::t('app', 'To Department'),
            'to_employee' => Yii::t('app', 'To Employee'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => OurCustomBehavior::className(),
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->action = $this->action ? $this->action : 1;
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'from_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'from_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMusteri()
    {
        return $this->hasOne(Musteri::className(), ['id' => 'musteri_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToDepartment()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'to_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToEmployee()
    {
        return $this->hasOne(Users::className(), ['id' => 'to_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhDocumentItems()
    {
        return $this->hasMany(WhDocumentItems::className(), ['wh_document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItemBalances()
    {
        return $this->hasMany(WhItemBalance::className(), ['wh_document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->reg_date = $this->reg_date ? date('d.m.Y', strtotime($this->reg_date)) : date('d.m.Y');
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDocTypes($key = null)
    {
        $result = [
            self::DOC_TYPE_INCOMING => Yii::t('app', 'Kirim'),
            self::DOC_TYPE_MOVING => Yii::t('app', "Ko'chirish"),
            self::DOC_TYPE_SELLING => Yii::t('app', "Sotish"),
            self::DOC_TYPE_RETURN => Yii::t('app', "Qaytarish"),
            self::DOC_TYPE_OUTGOING => Yii::t('app', "Chiqim"),
            self::DOC_TYPE_PENDING => Yii::t('app', "Qabul qilish"),
            self::DOC_TYPE_MIXING => Yii::t('app', "Aralashtirish"),
        ];
        if (!empty($key)) {
            return $result[$key];
        }
        return $result;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public static function getDocTypeBySlug($key = null)
    {
        $result = [
            self::DOC_TYPE_INCOMING_LABEL => Yii::t('app', 'Kirim'),
            self::DOC_TYPE_MOVING_LABEL => Yii::t('app', "Ko'chirish"),
            self::DOC_TYPE_OUTGOING_LABEL => Yii::t('app', "Chiqim"),
            self::DOC_TYPE_SELLING_LABEL => Yii::t('app', "Sotish"),
            self::DOC_TYPE_RETURN_LABEL => Yii::t('app', "Qaytarish"),
            self::DOC_TYPE_PENDING_LABEL => Yii::t('app', "Qabul qilish"),
            self::DOC_TYPE_MIXING_LABEL => Yii::t('app', "Aralashtirish"),
        ];
        if ($key)
            return $result[$key];
        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getSlugLabel()
    {
        $slug = Yii::$app->request->get('slug');
        if (!empty($slug)) {
            return self::getDocTypeBySlug($slug);
        }
    }

    public static function getAuthorList()
    {
        $sql = "select u.id,user_fio from users u
                left join wh_document ml on u.id = ml.created_by
                WHERE ml.id is not null
                GROUP BY u.id
        ";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return ArrayHelper::map($list,'id','user_fio');
    }

    /**
     * @param bool $isAll
     * @return array|null
     */
    public function getEmployees($isAll = false)
    {

        if ($isAll) {
            $user = Users::find()->select(['id', 'user_fio'])->asArray()->all();
        } else {
            $user = Users::find()->select(['id', 'user_fio'])->where(['id' => Yii::$app->user->id])->asArray()->all();
        }
        if (!empty($user)) {
            return ArrayHelper::map($user, 'id', 'user_fio');
        }
        return null;
    }

    /**
     * @param array $token
     * @return array|null
     */
    public static function getEmployeesByRole($token = [], $dept = null)
    {
        if ($dept) {
            $subQuery = UserRoles::find()->select(['id'])->where(['department' => $dept]);
            $res = Users::find()->select(['id', 'user_fio'])->where(['user_role' => $subQuery])->asArray()->all();
            return ArrayHelper::map($res, 'id', 'user_fio');
        }
        if (!empty($token)) {
            $subQuery = UserRoles::find()->select(['id'])->where(['code' => $token]);
            $res = Users::find()->select(['id', 'user_fio'])->where(['user_role' => $subQuery])->asArray()->all();
            return ArrayHelper::map($res, 'id', 'user_fio');
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getDepartments($isGetAll = false)
    {
        if ($isGetAll) {
            $depts = ToquvDepartments::find()->where(['status' => ToquvDepartments::STATUS_ACTIVE])->asArray()->all();
            return ArrayHelper::map($depts, 'id', 'name');
        } else {
            $availIds = ToquvUserDepartment::find()->select(['department_id'])
                ->where(['status' => self::STATUS_ACTIVE, 'user_id' => Yii::$app->user->id])
                ->asArray()->all();
            if (!empty($availIds)) {
                $ids = ArrayHelper::getColumn($availIds, 'department_id');
                $result = ToquvDepartments::find()->select(['id', 'name'])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['in', 'id', $ids])->asArray()->all();
            } else {
                return null;
            }
            if (!empty($result)) {
                return ArrayHelper::map($result, 'id', 'name');
            }
        }
        return null;
    }

    public function getAllPulBirligi()
    {
        $results = PulBirligi::find()->select(['id', 'name'])->where(['status' => self::STATUS_ACTIVE])->asArray()->all();
        if (!empty($results)) {
            return ArrayHelper::map($results, 'id', 'name');
        }
        return null;
    }

    /**
     * @param $params
     * @param bool $isAll
     * @return array|mixed|null
     * @throws \yii\db\Exception
     */
    public function getRemain($params, $isAll = false)
    {
        if ($isAll) {
            $sql = "select entity_id, inventory from wh_item_balance where entity_id IN (%s) AND entity_type = %d AND department_id = %d;";
            $sql = sprintf($sql, $params['id'], $params['type'], $params['depId']);
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            return $res;
        } else {
            $results = BichuvItemBalance::find()->select(['inventory'])
                ->where(['entity_type' => $params['type'], 'entity_id' => $params['id'], 'department_id' => $params['depId']])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();
            if (!empty($results)) {
                return $results['inventory'];
            }
        }
        return null;
    }

    /**
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public function searchEntities($params)
    {
        $wh = new WhItemBalance();
        return $wh->searchEntities($params);

    }

    /**
     * @param null $token
     * @param null $musteri_type
     * @return array|null
     */
    public function getMusteries($token = null, $musteri_type = null)
    {
        if ($token) {
            $result = Musteri::find()->select(['id', 'name'])->where([
                'status' => self::STATUS_ACTIVE,
                'token' => $token
            ])->asArray()->one();
            return [$result['id'] => $result['name']];
        } else {
            $query = Musteri::find();
            if (!empty($musteri_type)) {
                $id = Constants::$NillGranitID;
                $query->andFilterWhere(['OR', ['musteri_type_id' => $musteri_type], ['id' => $id]]);
            }
            $query->andFilterWhere(['status' => self::STATUS_ACTIVE])->select(['id', 'name']);
            $results = $query->asArray()->orderBy(['name' => SORT_ASC])->all();
            if (!empty($results)) {
                return ArrayHelper::map($results, 'id', 'name');
            }
        }
        return null;
    }

    public function getItems($id = false, $all = true)
    {
        $active = WhItems::STATUS_ACTIVE;
        if ($id) {
            $sql = "select i.code, i.name, c.name as category, 
                        t.name as type, ct.name as country, u.name as unit 
                    from wh_items i 
                        left join wh_item_category c on i.category_id = c.id 
                        left join wh_item_types t on i.type_id = t.id 
                        left join wh_item_country ct on i.country_id = ct.id 
                        left join unit u on i.unit_id = u.id 
                    where i.id = :id limit 1";
            $item = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryOne();
            if ($item) {
                return $item['code'] . ' - '
                    . $item['name'] . ' - '
                    . $item['type']. ' - '
                    . $item['category']. ' - '
                    . $item['country']. ' ('
                    . $item['unit'] . ')';
            }
        } else {
            if ($all) {
                $sql = "select i.id, i.code, i.barcode, i.name, c.name as category, 
                        t.name as type, ct.name as country, u.name as unit 
                    from wh_items i 
                        left join wh_item_category c on i.category_id = c.id 
                        left join wh_item_types t on i.type_id = t.id 
                        left join wh_item_country ct on i.country_id = ct.id 
                        left join unit u on i.unit_id = u.id 
                    where i.status = {$active} ORDER BY i.updated_at ASC LIMIT 10000;";
            } else {
                $sql = "select accs.id, accs.sku, accs.name, bap.name as property from bichuv_acs accs 
                    left join bichuv_acs_property bap on accs.property_id = bap.id
                    left join bichuv_item_balance bib on bib.entity_id = accs.id
                    where accs.status = 1 AND bib.inventory > 0 AND bib.id IN (select MAX(bib2.id) from bichuv_item_balance bib2 where bib2.entity_id = accs.id) limit 1000";
            }
            $items = Yii::$app->db->createCommand($sql)->queryAll();

            if (!empty($items)) {
                $result = [];

                foreach ($items as $item) {

                    $result['data'][$item['id']] =  $item['code'] . ' - '
                        . $item['name'] . ' - '
                        . $item['type']. ' - '
                        . $item['category']. ' - '
                        . $item['country'] . ' ('
                        . $item['unit'] . ')';
                    $result['barcodeAttr'][$item['id']] = ['data-barcode' => $item['barcode']];

                }

                return $result;
            }

            return null;
        }
    }

    public static function getCountPending($deps)
    {
        $sql = self::find()->select(['count(id) as count'])
            ->andFilterWhere(['status' => self::STATUS_ACTIVE])
            ->andFilterWhere(['document_type' => self::DOC_TYPE_PENDING])
            ->andFilterWhere(['in', 'to_department', array_keys($deps)])->asArray()->one();
        return $sql;
    }

}
