<?php

use app\components\PermissionHelper as P;
use app\modules\hr\models\HrDepartments;
use yii\helpers\Url;
use yii\widgets\Menu;

$this->title = Yii::t('app', '{type}', ['type' => $this->context->mobileTable['name']]);
$this->params['breadcrumbs'][] = [
    'label' => '<i class="fa fa-2x fa-chevron-circle-left"></i>',
    'url' => ['/mobile/tikuv/process-menu', 'department_id' => HrDepartments::getDepartmentIdByToken(HrDepartments::TOKEN_TIKUV)],
];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <?= Menu::widget([
        'options' => ['class' => 'list-group'],
        'itemOptions' => [
            'class' => 'list-group-item',
        ],
        'items' => [
            [
                'label' => Yii::t('app', "Qabul qilish"),
                'url' => Url::to(['/mobile/tikuv/conveyor-in', 'slug' => $this->context->slug]),
                'visible' => P::can('mobile/tikuv/conveyor-in'),
                'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-shirtsinbulk"></i><span> {label}</span></a>',
            ],
            [
                'label' => Yii::t('app', "Ko'chirish"),
                'url' => Url::to(['/mobile/tikuv/conveyor-out', 'slug' => $this->context->slug]),
                'visible' => P::can('mobile/tikuv/conveyor-out'),
                'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-shirtsinbulk"></i><span> {label}</span></a>',
            ],
        ],
        'linkTemplate' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-circle-o"></i> {label}</a>',
        'encodeLabels' => false,
        'activateParents' => true,
    ]); ?>
    </div>
