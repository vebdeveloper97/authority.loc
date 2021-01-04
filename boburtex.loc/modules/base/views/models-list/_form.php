<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;
use app\modules\base\models\ModelsToquvAcs;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */
/* @var $rawMaterials \app\modules\base\models\ModelOrdersItemsMaterial*/
/* @var $acs \app\modules\base\models\ModelOrdersItemsAcs */
/* @var $variations \app\modules\base\models\ModelOrdersVariations */
/* @var $colorPantone \app\modules\boyoq\models\ColorPantone */
/* @var $toquvRawMaterials \app\modules\toquv\models\ToquvRawMaterials */
/* @var $pechat \app\modules\base\models\ModelsPechat */
/* @var $naqsh \app\modules\base\models\ModelsNaqsh */
/* @var $pechatImages \app\modules\base\models\ModelsPechat */
/* @var $naqshImages \app\modules\base\models\ModelsNaqsh */
/* @var $wmsMatoInfo \app\modules\wms\models\WmsMatoInfo */
/* @var $list */

$type = Yii::$app->request->get('type');
 ?>
<?php Pjax::begin(['id' => 'modelPjax']) ?>
<div id="modelList" class="models-list-form">
    <div id="alertModel"></div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app','Main Information'),
                    'content' => $this->render('form/_models', [
                            'model' => $model, 'form' => $form
                    ]),
                    'active' => ($model->isNewRecord || !isset($_GET['active']) || $_GET['active'] == 'true')?true:false,
                     'options' => [
                         'id' => 'content_model'
                     ]
                 ],
                [
                    'label' => Yii::t('app','Raw materials and Acsessuars'),
                    'content' => (!$model->isNewRecord)?$this->render('form/_materials', [
                            'rawMaterials' => $rawMaterials, 'toquvRawMaterials' => $toquvRawMaterials?$toquvRawMaterials:[new ModelsToquvAcs()], 'model' => $model, 'wmsMatoInfo' => $wmsMatoInfo, 'acs' => $acs, 'form' => $form
                    ]):$this->render('form/_new.php'),
                    'active' => (!$model->isNewRecord && isset($_GET['active']) && $_GET['active'] == 'material') ?true:false,
                    'options' => [
                        'id' => 'content_material'
                    ]
                ],
                [
                    'label' => Yii::t('app','Variations'),
                    'content' => (!$model->isNewRecord)?$this->render('view/_variations', [
                        'variations' => $variations, 'list' => $list, 'model' => $model, 'form' => $form
                    ]):$this->render('form/_new.php'),
                    'active' => (!$model->isNewRecord && isset($_GET['active']) && $_GET['active'] == 'variation')?true:false
                ],
                [
                    'label' => Yii::t('app','Sketchs'),
                    'content' => (!$model->isNewRecord)?$this->render('form/_sketch', [
                        'model' => $model, 'form' => $form
                    ]):$this->render('form/_new.php'),
                    'active' => (!$model->isNewRecord && isset($_GET['active']) && $_GET['active'] == 'sketch')?true:false
                ],
                [
                    'label' => Yii::t('app','Measurement'),
                    'content' => (!$model->isNewRecord)?$this->render('form/_measurement', [
                        'model' => $model, 'form' => $form
                    ]):$this->render('form/_new.php'),
                    'active' => (!$model->isNewRecord && isset($_GET['active']) && $_GET['active'] == 'measurement')?true:false
                ],
                [
                    'label' => Yii::t('app','Others'),
                    'content' => (!$model->isNewRecord)?$this->render('form/_others', [
                        'model' => $model, 'form' => $form
                    ]):$this->render('form/_new.php'),
                    'active' => (!$model->isNewRecord && isset($_GET['active']) && $_GET['active'] == 'others')?true:false
                ],
            ]
    ]);?>

    <div class="row form-group" style="padding-top: 15px">
        <div class="col-md-6">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id' => 'saveButtonModel']) ?>
        </div>
    </div>
    <br>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
<?php Pjax::begin(['id' => 'variationPjax']) ?>
<div id="model-variation-form">

</div>
<?php Pjax::end(); ?>
<?php
$this->registerJs(
'$("document").ready(function(){
    $("#variationPjax").on("pjax:end", function() {
        $.pjax.reload({container:"#modelPjax"});  //Reload GridView
        $("#model-variation-form").hide();
        $("#modelList").show();
    });
});'
);