<?php

namespace app\modules\base\models;

use Yii;
use app\modules\toquv\models\ToquvDepartments;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wh_department_area".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $dep_id
 * @property int $parent_id
 * @property int $type
 * @property string $add_info
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ToquvDepartments $dep
 * @property WhDocumentItems[] $whDocumentItems
 * @property WhDocumentItems[] $whDocumentItems0
 * @property WhItemBalance[] $whItemBalances
 * @property WhItemBalance[] $whItemBalances0
 */
class WhDepartmentArea extends BaseModel
{
    public $children = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wh_department_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dep_id', 'parent_id', 'type', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['add_info'], 'string'],
            [['code'], 'unique'],
            [['name', 'dep_id'], 'required'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['dep_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToquvDepartments::className(), 'targetAttribute' => ['dep_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'dep_id' => Yii::t('app', 'Department ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'type' => Yii::t('app', 'Type'),
            'add_info' => Yii::t('app', 'Add Info'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDep()
    {
        return $this->hasOne(ToquvDepartments::className(), ['id' => 'dep_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(WhDepartmentArea::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(WhDepartmentArea::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhDocumentItems()
    {
        return $this->hasMany(WhDocumentItems::className(), ['dep_area' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhDocumentItems0()
    {
        return $this->hasMany(WhDocumentItems::className(), ['dep_section' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItemBalances()
    {
        return $this->hasMany(WhItemBalance::className(), ['dep_area' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhItemBalances0()
    {
        return $this->hasMany(WhItemBalance::className(), ['dep_section' => 'id']);
    }

    /**
     * @param bool $withoutKeyValue
     * @return array
     * @throws \yii\db\Exception
     */
    public function getMyDepartments($withoutKeyValue = false)
    {
        $currentID = Yii::$app->user->id;
        $sql = "select 
                    td.id,
                    td.name from toquv_departments td
                    where td.status = %d 
                        AND td.id IN 
                        (SELECT  tud.department_id from toquv_user_department tud 
                                    WHERE tud.user_id = %d);";
        $sql = sprintf($sql, self::STATUS_ACTIVE, $currentID);
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if (!empty($query)) {
            if ($withoutKeyValue) {
                return $query;
            }
            return ArrayHelper::map($query, 'id', 'name');
        }
        return [];
    }

    public function getMyParents()
    {
        $sql = "select id, name 
                from wh_department_area
                where status = %d AND id != %s ORDER BY updated_by DESC;";
        $sql = sprintf($sql, self::STATUS_ACTIVE, ($this->id ? $this->id : '0'));
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        return !empty($query) ? ArrayHelper::map($query, 'id', 'name') : [];
    }


    /**
     * Recursive func returns sections as root
     * @param bool $parent
     * @param null $dep_id
     * @return array
     */
    public function getAsRoot( $parent = false, $dep_id = null )
    {
        $sql = "select id, name 
                from wh_department_area
                where status = %d %s %s ORDER BY name ASC;";
        $sql = sprintf(
            $sql,
            self::STATUS_ACTIVE,
            ($dep_id ? "AND dep_id = {$dep_id}" : ""),
            (!$parent ? "AND parent_id IS NULL" : "AND parent_id = {$parent}"));

        try {
            $items = Yii::$app->db->createCommand($sql)->queryAll();
            if (!empty($items)) {
                foreach ($items as $i => $item) {
                    $child = $this->getAsRoot($item['id']);
                    $items[$i]['children'] = $child ? $child : false;
                }
            }

            return $items;
        } catch (\Exception $e) {
            Yii::info('WH department area getAsRoot' . $e, 'model');
        }

    }

    public function getFullName () {

    }
}
