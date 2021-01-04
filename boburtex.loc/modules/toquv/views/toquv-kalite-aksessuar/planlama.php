<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvKaliteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Planlama');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row" style="padding: 10px">
    <div class="col-md-5 noPadding " style="padding-left: 7px"">
        <p class="text-primary"><b><?=Yii::t('app','Buyurtmalar Ro\'yhati')?></b></p>
        <?=Html::dropDownList('depts', '', \app\modules\toquv\models\ToquvKalite::getAksBuyurtmaList(1) ,
            ['class' => 'form-control form-control-sm selectPlanning', 'id' => 'department_list','multiple'=>true,'size'=>40,])?>
    </div>
    <div class="col-md-7 noPadding">
        <p class="text-primary" style="padding-left: 10%"><b><?=Yii::t('app','Jarayonlar Ro\'yhati')?></b></p>
        <div class="flexRow">
             <div class="size10">
                 <p>
                     <button class="btn btn-default btn-lg"  onclick="saveId(3,'department_list');listbox_moveacross('department_list', 'order_department_list');">
                        <span class="glyphicon glyphicon-menu-right"></span>
                     </button>
                 </p>
                 <p>
                    <button class="btn btn-default btn-lg" onclick="saveId(1,'order_department_list');listbox_moveacross('order_department_list', 'department_list');">
                        <span class="glyphicon glyphicon-menu-left"></span>
                    </button>
                 </p>
             </div>
            <div class="size90">
                <?=Html::dropDownList('depts', '', \app\modules\toquv\models\ToquvKalite::getAksBuyurtmaList(3) ,
                ['class' => 'form-control form-control-sm selectPlanning', 'id' => 'order_department_list','multiple'=>true,'size'=>17,])?>
            </div>
        </div>
        <p style="text-align: center;padding-top: 10px;"><button class="btn btn-default btn-lg" onclick="saveId(3,'order_department_list_down');listbox_moveacross('order_department_list_down', 'order_department_list');">
                <span class="glyphicon glyphicon-menu-up"></span>
            </button>
            <button class="btn btn-default btn-lg" onclick="saveId(2,'order_department_list');listbox_moveacross('order_department_list', 'order_department_list_down');">
                <span class="glyphicon glyphicon-menu-down"></span>
            </button>
        <button class="btn btn-default btn-lg " style="float: right " onclick="listbox_moveacross('', ''); ">
                <span class="glyphicon glyphicon-hand-right"></span>
            </button>

        </p>


        <p class="text-primary" style="padding-left: 10%"><b><?=Yii::t('app','Tugalanganlar Ro\'yhati')?></b></p>
        <div class="flexRow">
            <div class="size10">
                <p><button class="btn btn-default btn-lg" onclick="saveId(2,'department_list');listbox_moveacross('department_list', 'order_department_list_down');">
                        <span class="glyphicon glyphicon-menu-right"></span>
                    </button></p>

                <p><button class="btn btn-default btn-lg" onclick="saveId(1,'order_department_list_down');listbox_moveacross('order_department_list_down', 'department_list');">
                        <span class="glyphicon glyphicon-menu-left"></span>
                    </button></p>

            </div>
            <div class="size90">
                <?=Html::dropDownList('depts', '', \app\modules\toquv\models\ToquvKalite::getAksBuyurtmaList(2) ,
                ['class' => 'form-control form-control-sm selectPlanning', 'id' => 'order_department_list_down','multiple'=>true,'size'=>17,])?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('/js/dept.js',['depends'=>\yii\web\YiiAsset::className()]);
$url = Yii::$app->urlManager->createUrl('toquv/toquv-kalite-aksessuar/save-id');
$this->registerJsVar('saveUrl',$url);
$js = <<< JS

JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
.flexRow{
    display: flex;
    flex-direction: row;
    width: 100%;
    align-content: center;
    justify-content: center;
    align-items: center;
}
.size10{
    width: 7%;
    margin-right: 7px;
}
.size90{
    width: 90%;
}
.selectPlanning option:hover {
    overflow-x: scroll;
}
CSS;
$this->registerCss($css);
?>

