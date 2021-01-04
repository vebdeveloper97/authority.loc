<?php
namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;

class PermissionHelper{
    private static $permissionsAndRoles;

    static function per($name){
        return Yii::$app->user->can($name);
    }

    /**
     * @param int $userId
     * @return array
     */
    public static function getRolesAndPermissionsByUser(int $userId): array
    {
        if (self::$permissionsAndRoles === null) {
            $authManager = Yii::$app->getAuthManager();

            $permissions = $authManager->getPermissionsByUser($userId);
            $roles = $authManager->getRolesByUser($userId);
            $userPermissionsAndRoles = ArrayHelper::merge($permissions, $roles);
            foreach ($roles as $roleName => $roleArray) {
                $userPermissionsAndRoles = ArrayHelper::merge($userPermissionsAndRoles, $authManager->getChildRoles($roleName));
            }
            self::$permissionsAndRoles = $userPermissionsAndRoles;
        }

        return self::$permissionsAndRoles;
    }

    public static function can($permissionName)
    {
        $arr = self::getRolesAndPermissionsByUser(Yii::$app->user->identity->id);
        return isset($arr[$permissionName]);
    }

    public static function role($role){
       return Yii::$app->authManager->getRole($role) !== null;
    }
}