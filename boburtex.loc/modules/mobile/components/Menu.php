<?php
namespace app\modules\mobile\components;


use app\modules\hr\models\HrDepartments;
use app\modules\mobile\models\MobileProcess;
use Yii;
use yii\helpers\Url;
use app\components\PermissionHelper as P;

class Menu
{
    public static $menu = [];

    public static function init()
    {
        self::$menu = [
            [
                'label' => Yii::t('app', "Tikuv"),
                'url' => Url::to(['/mobile/tikuv/process-menu', 'department_id' => HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TIKUV)]),
                'visible' => P::can('mobile/tikuv/index'),
                'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-shirtsinbulk"></i><span> {label}</span></a>',
            ],
            [
                'label' => Yii::t('app', "Bichuv"),
                'url' => Url::to(['/mobile/bichuv']),
                'visible' => P::can('bichuv'),
                'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-scissors"></i><span> {label}</span></a>',
            ],
            'tayyorlov' => [
                'label' => Yii::t('app', "Tayyorlov"),
                'url' => Url::to(['index', 'm' => 'tayyorlov']),
                'visible' => P::can('tayyorlov'),
                'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-gift"></i><span> {label}</span></a>',
            ],
        ];
    }

    public static function getSubMenuByKey($key) {
        return isset(self::$menu[$key]) ? self::$menu[$key]['items'] : self::$menu;
    }

    public static function getProcessMenuItemsByDepartmentId($department_id)
    {
        $processes = MobileProcess::getProcessesByDepartmentIdAndUserId($department_id, Yii::$app->user->identity->id);

        $menuItems = [];

        /** @var MobileProcess $process */
        foreach ($processes as $process) {
            $menuItems[$process->process_order] = [
                'label' => $process->name,
                'url' => Url::to(['/mobile/tikuv/index', 'process_id' => $process->id, 'slug' => $process->token]),
//                'visible' => P::can('mobile/tikuv/conveyor-in'),
                'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-shirtsinbulk"></i><span> {label}</span></a>',
            ];
        }

        return $menuItems;
    }
}