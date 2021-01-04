<?php

use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsDesen;
use kartik\helpers\Html as KHtml;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $modelList app\modules\base\models\ModelsList */
/* @var $colors app\modules\base\models\ModelsVariationColor[] */
/* @var $form yii\widgets\ActiveForm */

$urlRemain = Yii::$app->urlManager->createUrl('base/models-list/ajax-request');
$urlColorBoyoq = Yii::$app->urlManager->createUrl(['base/models-list/ajax-request','colorType'=>'boyoq']);
$details = null;
$lastColorData = [];
if(!$model->isNewRecord){
    $modelList = $model->modelList;
}else{
    $lastColorData = $modelList->getLastVariation();
}
if (!empty($modelList->basePattern) && !empty($modelList->basePattern->basePatternItems)) {
    $details = $modelList->basePattern->getBasePatternItems()->joinWith(['baseDetailList'])->asArray()->all();
}
?>
<div id="colorVariationBox_<?=time()?>" class="colorVariationBox">
    <?php if (!empty($details)): ?>
        <?php foreach ($details as $key => $detail): ?>
            <?php $colormodel = $colors[0];
            if(isset($colors[$key])){
                $colormodel = $colors[$key];
            }
            if(key_exists($detail['baseDetailList']['id'],$lastColorData)){
                $rm = $lastColorData[$detail['baseDetailList']['id']];
            }else{
                $rm = $colormodel['toquv_raw_material_id'];
            }
//            \yii\helpers\VarDumper::dump($detail,10,true);die;
            ?>
            <div class="row parentRow">
                <div class="col-md-3">
                    <?= $form->field($colormodel, "[$key]base_detail_list_id")->dropDownList([$detail['baseDetailList']['id']=>$detail['baseDetailList']['name']]);?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($colormodel, "[$key]wms_color_id")->widget(Select2::className(),
                        [
                            'data' => \app\modules\wms\models\WmsColor::getMapList(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Color'),
                                'class' => 'wms_color_id_variations'
                            ],
                            'size' => Select2::SIZE_SMALL,
                            'addon' => [
                                'append' => [
                                    'content' => KHtml::button(KHtml::icon('plus'), [
                                        'class' => 'showModalButton btn btn-success btn-sm wms-color-id-var',
                                        'style' => 'width:15px; padding:2px; font-size: 8px',
                                        'title' => Yii::t('app', 'Create'),
                                        'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                        'data-toggle' => "modal",
                                        'data-form-id' => 'wms_color_form',
                                        'data-input-name' => 'modelsvariationcolors-'.$key.'-wms_color_id'
                                    ]),
                                    'asButton' => true
                                ]
                            ],
                            'pluginOptions' => [
                                'width' => '100%',
                                'allowClear' => true
                            ]
                        ]
                    )->label(Yii::t('app', 'Wms Color ID')) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($colormodel, "[$key]wms_desen_id")->widget(Select2::className(),
                        [
                            'data' => WmsDesen::getMapList(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Desen name'),
                                'class' => 'wms_desen_variations'
                            ],
                            'size' => Select2::SIZE_SMALL,
                            'addon' => [
                                'append' => [
                                    'content' => KHtml::button(KHtml::icon('plus'), [
                                        'class' => 'showModalButton btn btn-success btn-sm wms-desen-id-var',
                                        'style' => 'width:15px; padding:2px; font-size: 8px',
                                        'title' => Yii::t('app', 'Create'),
                                        'value' => Url::to(['/wms/wms-desen/create', 'type' => 'other_modal']),
                                        'data-toggle' => "modal",
                                        'data-form-id' => 'wms_desen_form',
                                        'data-input-name' => 'modelsvariationcolors-'.$key.'-wms_desen_id'
                                    ]),
                                    'asButton' => true
                                ]
                            ],
                            'pluginOptions' => [
                                'width' => '100%',
                                'allowClear' => true
                            ]
                        ]
                    )->label(Yii::t('app', 'Wms Desen ID')) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($colormodel, "[$key]toquv_raw_material_id")->widget(Select2::className(), [
                        'data' => $model::getMaterialList(null,$modelList['id']),
                        'options' => ['value' => $rm,  'class' => 'variation-raw-material shart', 'id' => 'variation-raw-material_'.$key.'-'.time()],
                        'pluginOptions' => [
                            'escapeMarkup' => new JsExpression(
                                "function (markup) {return markup;}"
                            ),
                            'templateResult' => new JsExpression(
                                "function(data) {return data.text;}"
                            ),
                            'templateSelection' => new JsExpression(
                                "function (data) { return data.text;}"
                            ),
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


