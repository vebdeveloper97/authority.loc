<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "user_roles".
 *
 * @property int $id
 * @property string $role_name
 * @property string $code
 * @property string $department
 */
class UserRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_name'], 'string', 'max' => 100],
            [['code', 'department'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role_name' => Yii::t('app', 'Role Name'),
            'code' => Yii::t('app', 'Code'),
            'department' => Yii::t('app', 'Department'),
        ];
    }
    public static function getUserRoles()
    {
        $models = UserRoles::find()->all();
        $permissions = ArrayHelper::map($models , 'id' ,'role_name');
        return $permissions;
    }
}