<?php

use app\modules\bichuv\models\BichuvAcs;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\helpers\Html as KHtml;
use app\modules\wms\models\WmsDesen;
use app\components\TabularInput\CustomTabularInput;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $modelList app\modules\base\models\ModelsList */
/* @var $prints app\modules\base\models\ModelVarPrints */
/* @var $form yii\widgets\ActiveForm */
/* @var $pechat \app\modules\base\models\ModelVarPrints */
/* @var $naqsh \app\modules\base\models\ModelVarStone */
/* @var $acs \app\modules\base\models\ModelsAcs */
/* @var $isModel */


$urlRemain = Yii::$app->urlManager->createUrl('base/models-list/ajax-request');
$urlListBoyoqhona = Yii::$app->urlManager->createUrl(['base/models-list/ajax-request','colorType'=>'boyoq']);
$model_list_id = ($_GET['list']) ? $_GET['list'] : '';
?>
    <div id="form-variation_<?=time()?>" class="models-variations-form" style="padding-left: 10px;">
        <?php if (Yii::$app->request->isAjax) { ?>
            <p style="padding-bottom: 10px">
                <button type="button" class="btn btn-danger cansel pull-right"><i class="fa fa-close"></i></button>
            </p>
        <?php } ?>
        <?php if($modelList){?>
            <?php echo Yii::t('app','Model')?> : <span><?=$modelList['name']?> <b><?=$modelList['article']?></b></span>
        <?php }?>
        <?php
        $form = ActiveForm::begin(['options' => [
            'enableAjaxValidation' => true,
            'class' => 'formVariation',
            'id' => 'formVariation_'.time(),
            'validationUrl' => Yii::$app->urlManager->createUrl('base/models-variations/validate'),
            'saveUrl' => Yii::$app->urlManager->createUrl([
                'base/models-variations/save', 'id' => ($model->isNewRecord)
                    ? 0 : $model->id
            ]),
        ]
        ]); ?>
        <div class="row" style="padding-top: 15px;">
            <div class="col-md-6">
                <?= $form->field($model, 'wms_color_id', ['enableAjaxValidation' => true])->widget(\kartik\select2\Select2::className(),
                    [
                        'data' => \app\modules\wms\models\WmsColor::getMapList(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Color'),
                        ],
                        'size' => Select2::SIZE_SMALL,
                        'addon' => [
                            'append' => [
                                'content' => KHtml::button(KHtml::icon('plus'), [
                                    'class' => 'showModalButton btn btn-success btn-sm wms-color-id',
                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                    'title' => Yii::t('app', 'Create'),
                                    'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                    'data-form-id' => 'wms_color_form',
                                    'data-input-name' => 'modelsvariations-wms_color_id'
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

                <?= $form->field($model, 'toquv_raw_material_id')->widget(\kartik\select2\Select2::className(),
                    [
                        'data' => $model::getMaterialList(null,$model_list_id),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Qidirish uchun kamida 3ta belgi yozing'),
                        ],
                        'pluginEvents' => [
                            "select2:unselect" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }"),
                            "select2:select" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }")
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression(
                                "function (markup) { 
                                                    return markup;
                                                }"
                            ),
                            'templateResult' => new JsExpression(
                                "function(data) {
                                                       return data.text;
                                                 }"
                            ),
                            'templateSelection' => new JsExpression(
                                "function (data) { return data.text; }"
                            ),
                        ]
                    ]
                )->label(Yii::t('app', 'Asosiy mato')) ?>

                <?= $form->field($model, 'code')->hiddenInput(['value' => 1])->label(false); ?>

                <?= $form->field($model, 'model_var_prints_id')->widget(Select2::class,
                    [
                        'data' => \app\modules\base\models\ModelVarPrints::getMapList(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Models Pechat'),
                        ],
                        'pluginOptions' => [
                            'width' => '100%',
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.text; }'),
                            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        ],
                        'size' => Select2::SIZE_SMALL,
                        'addon' => [
                            'append' => [
                                'content' => KHtml::button(KHtml::icon('plus'), [
                                    'class' => 'btn btn-success btn-sm pechat-id',
                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                    'title' => Yii::t('app', 'Create'),
//                                    'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                    'data-toggle' => "modal",
//                                    'data-form-id' => 'wms_color_form',
                                    'data-target' => '#pechat_modal',
//                                    'data-input-name' => 'modelsvariations-wms_color_id'
                                ]),
                                'asButton' => true
                            ]
                        ],
                    ]
                ); ?>

                <?=$form->field($model, 'bichuv_acs_id')->widget(Select2::class, [
                    'data' => \yii\helpers\ArrayHelper::map($acs, 'id', function($m){
                        if(!empty($m['id'])){
                            $properties = \app\modules\bichuv\models\BichuvAcsProperties::find()->where(['bichuv_acs_id' => $m['id']])->all();
                            $propertie = '';
                            foreach ($properties as $property){
                                $propertie = $propertie .' '. $property['value'];
                            }
                            return $m['name'].' '.$m['sku'].' '.$propertie;
                        }
                        return [];
                    }),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Bichuv Acs'),
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'width' => '100%',
                        'allowClear' => true,
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                    'size' => Select2::SIZE_SMALL,
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'add_info')->textarea() ?>
                <?= $form->field($model, 'wms_desen_id')->widget(\kartik\select2\Select2::className(),
                    [
                        'data' => WmsDesen::getMapList(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Desen name'),
                        ],
                        'size' => Select2::SIZE_SMALL,
                        'addon' => [
                            'append' => [
                                'content' => KHtml::button(KHtml::icon('plus'), [
                                    'class' => 'showModalButton btn btn-success btn-sm wms-desen-id',
                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                    'title' => Yii::t('app', 'Create'),
                                    'value' => Url::to(['/wms/wms-desen/create', 'type' => 'other_modal']),
                                    'data-toggle' => "modal",
                                    'data-form-id' => 'wms_desen_form',
                                    'data-input-name' => 'modelsvariations-wms_desen_id'
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
                <?= $form->field($model, 'model_var_stone_id')->widget(Select2::class,
                    [
                        'data' => \app\modules\base\models\ModelVarStone::getMapList(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Models Naqsh'),
                        ],
                        'pluginOptions' => [
                            'width' => '100%',
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.text; }'),
                            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                            'allowClear' => true
                        ],
                        'size' => Select2::SIZE_SMALL,
                        'addon' => [
                            'append' => [
                                'content' => KHtml::button(KHtml::icon('plus'), [
                                    'class' => 'btn btn-success btn-sm naqsh-id',
                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                    'title' => Yii::t('app', 'Create'),
//                                    'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                    'data-toggle' => "modal",
//                                    'data-form-id' => 'wms_color_form',
                                    'data-target' => '#naqsh_modal',
//                                    'data-input-name' => 'modelsvariations-wms_color_id'
                                ]),
                                'asButton' => true
                            ]
                        ],
                    ]
                ); ?>
                <?= $form->field($model, 'make_all_as_main', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Make all main") . '</span>
            </label>',])->checkbox(['class' => 'checkbox__input makeAllMain', 'id' => 'makeAllMain_'.time()], false) ?>
            </div>
        </div>

        <?= $form->field($model, 'model_list_id', ['enableAjaxValidation' => true])->hiddenInput(['value' => $model_list_id])->label(false) ?>
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Detallar uchun ranglar va matolar'),
                    'content' => $this->render('form/_colors', [
                        'colors' => $colors,
                        'form' => $form,
                        'model' => $model,
                        'modelList' => $modelList,
                    ]),
                    'options' => ['id' => 'color_tab_'.time()],
                    'active' => true
                ],
                [
                    'label' => Yii::t('app', 'Variation attachments'),
                    'content' => $this->render('form/_attachments', [
                        'attachments' => $attachments, 'form' => $form
                    ]),
                    'options' => ['id' => 'attachment_tab_'.time()],
                ],
            ]
        ]); ?>
        <div class="form-group" style="padding-top: 20px">
            <?php if(!$isModel): ?>
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success send-variation']) ?>
            <?php endif; ?>
            <?=(!Yii::$app->request->isAjax) ? Html::a(Yii::t('app', 'Bekor qilish'), [
                '/base/models-list/update',
                'id' => $_GET['list'], 'active' => 'variation'
            ], [
                'class' => 'btn btn-primary cansel',
                'data' => (!Yii::$app->request->isAjax) ? [
                    'confirm' => Yii::t('app', "Siz rostdan ham ma'lumotlarni saqlamasdan orqaga qaytmoqchimisiz?"),
                ] : [],
            ]) : '' ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
    \yii\bootstrap\Modal::begin([
        'headerOptions' => ['id' => 'modalHead'],
        'options' => [
             'tabindex' => false
        ],
        'size'=>'modal-lg',
        'id' => 'pechat_modal'
    ]);
    if($pechat):?>
    <div class="container">
        <?php $f = ActiveForm::begin([
            'id' => 'pechat_form',
            'class' => 'formVariation'
        ]); ?>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                        echo $f->field($pechat, 'image')->widget(\app\components\KCFinderInputWidgetCustom::class, [
                            'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
                            'kcfBrowseOptions' => [
                                'langCode' => 'ru'
                            ],
                            'kcfOptions' => [
                                'uploadURL' =>  '/uploads',
                                'cookieDomain' => $_SERVER['SERVER_NAME'],
                                'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                'access' => [
                                    'files' => [
                                        'upload' => true,
                                        'delete' => true,
                                        'copy' => true,
                                        'move' => true,
                                        'rename' => true,
                                    ],
                                    'dirs' => [
                                        'create' => true,
                                        'delete' => true,
                                        'rename' => true,
                                    ],
                                ],
                                'thumbsDir' => 'thumbs',
                                'thumbWidth' => 150,
                                'thumbHeight' => 150,
                            ]
                        ]);
                    ?>
                </div>
                <div class="col-sm-3"><?=$f->field($pechat, 'name')?></div>
                <div class="col-sm-3"><?=$f->field($pechat, 'width')?></div>
                <div class="col-sm-3"><?=$f->field($pechat, 'height')?></div>
                <div class="col-sm-3"><?=$form->field($pechat, 'base_details_list_id')->widget(Select2::class, [
                        'data' => \yii\helpers\ArrayHelper::map(\app\modules\base\models\BaseDetailLists::find()->all(), 'id', 'name'),
                        'options' => [
                                'placeholder' => Yii::t('app', 'Select...'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.text; }'),
                            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        ],
                        'addon' => [
                            'append' => [
                                'content' => KHtml::button(KHtml::icon('plus'), [
                                    'class' => 'showModalButton btn btn-success btn-sm details',
                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                    'title' => Yii::t('app', 'Create'),
                                    'value' => Url::to(['base-detail-lists/create']),
                                    'data-toggle' => "modal",
                                    'data-form-id' => 'detailsLists',
                                    'data-input-name' => 'modelvarprints-base_details_list_id'
                                ]),
                                'asButton' => true
                            ]
                        ],
                    ])->label(Yii::t('app', 'Base Details List')); ?></div>
                <div class="col-sm-12">
                    <span class="btn btn-success create-pechat" data-id="models-variations/pechat-data"><?=Yii::t('app', 'Create')?></span>
                </div>
            </div>
        <?php ActiveForm::end();    ?>
    </div>
<?php
    endif;
    \yii\bootstrap\Modal::end();
?>

<?php
\yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modal', 'class' => 'formVariation'],
    'options' => [
        'tabindex' => false
    ],
    'size'=>'modal-lg',
    'id' => 'naqsh_modal'
]);
if($naqsh):
?>
    <div class="container">
        <?php $f = ActiveForm::begin([
            'id' => 'naqsh_form',
            'class' => 'formVariation'
        ]); ?>
        <div class="row">
            <div class="col-sm-12">
                <?php
                echo $f->field($naqsh, 'image')->widget(\app\components\KCFinderInputWidgetCustom::class, [
                    'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
                    'kcfBrowseOptions' => [
                        'langCode' => 'ru'
                    ],
                    'kcfOptions' => [
                        'uploadURL' =>  '/uploads',
                        'cookieDomain' => $_SERVER['SERVER_NAME'],
                        'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                        'access' => [
                            'files' => [
                                'upload' => true,
                                'delete' => true,
                                'copy' => true,
                                'move' => true,
                                'rename' => true,
                            ],
                            'dirs' => [
                                'create' => true,
                                'delete' => true,
                                'rename' => true,
                            ],
                        ],
                        'thumbsDir' => 'thumbs',
                        'thumbWidth' => 150,
                        'thumbHeight' => 150,
                    ]
                ]);
                ?>
            </div>

            <div class="col-sm-3"><?=$f->field($naqsh, 'name')?></div>
            <div class="col-sm-3"><?=$f->field($naqsh, 'width')?></div>
            <div class="col-sm-3"><?=$f->field($naqsh, 'height')?></div>
            <div class="col-sm-3"><?=$form->field($naqsh, 'base_details_list_id')->widget(Select2::class, [
                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\base\models\BaseDetailLists::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select...'),
                    ],
                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                        'allowClear' => true
                    ],
                    'addon' => [
                        'append' => [
                            'content' => KHtml::button(KHtml::icon('plus'), [
                                'class' => 'showModalButton btn btn-success btn-sm details',
                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                'title' => Yii::t('app', 'Create'),
                                'value' => Url::to(['base-detail-lists/create']),
                                'data-toggle' => "modal",
                                'data-form-id' => 'detailsLists',
                                'data-input-name' => 'modelvarstone-base_details_list_id'
                            ]),
                            'asButton' => true
                        ]
                    ],
                ])->label(Yii::t('app', 'Base Details List')); ?>
            </div>
            <div class="col-sm-12">
                <span class="btn btn-success create-naqsh" data-id="models-variations/naqsh-data"><?=Yii::t('app', 'Create')?></span>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
<?php
endif;
\yii\bootstrap\Modal::end();
?>

<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'id' => 'zaybal_modal',
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();
$css = <<< CSS
    .select2-container .select2-selection--single .select2-selection__clear {
        position: absolute!important;
    }
CSS;
$this->registerCss($css);
?>
<?php
$modelsId = $modelList->id;
$required = Yii::t("app", "Ushbu maydon to'ldirilishi majburiy");
$saved = Yii::t('app', 'Saved Successfully');
$infoError = Yii::t('app', "To`ldirish majburiy bo`lgan maydonlarni hammasi  to`ldirilmagan");
$amount = Yii::t('app', "ta qoldi");
$infoErrorRaw = Yii::t('app', 'Asosiy mato tanlanishi lozim');
$infoErrorColor = Yii::t('app', 'Asosiy panton rang kodi tanlanishi lozim');
$infoConfirm = Yii::t('app', 'Siz rostdan ham barcha andoza detallarini asosiy mato va asosiy rangalarga o\'zgartirmoqchimisiz?');
$js = <<< JS
    function errorInfo(n){
        return "{$infoError} ("+n+" {$amount})";
    }
    $(".formVariation").submit(function(event) {
        event.preventDefault(); // stopping submitting
        let required = $(".shart");
        let n = 0;
        $(required).each(function (index, value){
            if($(this).val()==""){
                $(this).css("border-color","red");
                if($(this).parent().find(".help-block").length>0){
                    $(this).parent().find(".help-block").css("color","red").html("{$required}");
                }else{
                    $(this).parent().append("<div class='help-block'></div>");
                    $(this).parent().find(".help-block").css("color","red").html("{$required}");
                }
                n++;
            }
        });
        if(n>0){
            let infoError = $("#infoErrorForm");
            if(infoError.length==0){
                $(this).after("<div id='infoErrorForm' style='color:red'>{$infoError}</div>");
            }else{
                infoError.html(errorInfo(n));
            }
        }
        else{
            var data = $(this).serializeArray();
            var url = $(this).attr('saveUrl');
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data
            })
            .done(function(response) {
                if (response.data.success == true) {
                    $("#model-variation-form").hide().html('');
                    $("#modelList").show();
                    $.pjax.reload({container:"#modelPjax"}).done(function(){
                        $("#alertModel").html('<div class="alert-success alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{$saved}</div>');
                    });
                }else{
                    let error = response.data.message;
                    var result = Object.keys(error).map(function(key) {
                      return [key, error[key]];
                    });
                    for(var i = 0; i < result.length; i++){
                        var name = Object.keys(error)[i];
                        let input = $("input[name='ModelsVariations["+name+"]']");
                        input.css("border-color","red");
                        input.parent().find(".help-block").css("color","red").html(result[i][1]);
                    }
                }
            })
            .fail(function() {
                $("#model-variation-form").hide().html('');
                $("#modelList").show();
            });
        }
    });

    $("body").delegate(".shart","blur",function(){
        if($(this).val()!=""){
            $(this).css("border-color","green");
            $(this).parent().find(".help-block").html('');
        }else{
            $(this).css("border-color","red");
            if($(this).parent().find(".help-block").length>0){
                $(this).parent().find(".help-block").css("color","red").html("{$required}");
            }else{
                $(this).parent().append("<div class='help-block'></div>");
                $(this).parent().find(".help-block").css("color","red").html("{$required}");
            }    
        }
        let required = $(".shart");
        let n = 0;
        $(required).each(function (index, value){
            if($(this).val()==""){
                n++;
                $("#infoErrorForm").html(errorInfo(n));
            }
        });
        if(n==0){
            $("#infoErrorForm").remove();
        }
    });
    
JS;
if (!Yii::$app->request->isAjax) { \app\widgets\helpers\Script::begin();
?>
    <script>
        $('body').delegate('.makeAllMain', 'change', function (e) {
            let checkbox = $(this).is(':checked');

            let form = $(this).parents('.formVariation');
            let raw = form.find('.toquvRawMaterialId').val();
            let color = form.find('#modelsvariations-wms_color_id').val();
            let boyoq = form.find('#modelsvariations-wms_desen_id').val();

            let colorTxt = form.find('#modelsvariations-wms_color_id option:selected').text();
            let rawTxt = form.find('.toquvRawMaterialId option:selected').text();
            let boyoqTxt = form.find('#modelsvariations-wms_desen_id option:selected').text();

            if (!raw && checkbox) {
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: "<?= $infoErrorRaw; ?>", type: 'error'});
                $(this).prop("checked", false);
                return false;
            }

            if (checkbox) {
                let confirm = window.confirm("<?=$infoConfirm?>");
                if (confirm) {
                    let objCVB = form.find('.colorVariationBox');
                    let vcp = objCVB.find('.wms_color_id_variations');
                    let vrm = objCVB.find('.variation-raw-material');
                    let bcp = objCVB.find('.wms_desen_variations');

                    if (vcp) {
                        vcp.each(function (key, val) {
                            let newOption = new Option(colorTxt, color, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(color).trigger('change');
                        });
                    }
                    if (vrm) {
                        vrm.each(function (key, val) {
                            let newOption = new Option(rawTxt, raw, true, true);
                            let checkOption = vrm.find('option[value="'+raw+'"]');
                            if(checkOption.length==0) {
                                $(val).append(newOption).trigger('change');
                            }
                            $(val).val(raw).trigger('change');
                        });
                    }
                    if (bcp) {
                        bcp.each(function (key, val) {
                            let newOption = new Option(boyoqTxt, boyoq, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(boyoq).trigger('change');
                        });
                    }
                }
            }
        });
    </script>
<?php \app\widgets\helpers\Script::end(); }
if (!Yii::$app->request->isAjax) {
    $this->registerJs($js, View::POS_READY);
}
$js = <<<JS
var formEl;
var url;
var formId;
var inputId;
var modalForm = $('#zaybal_modal');
var pechat = 'create-pechat';
var naqsh = 'create-naqsh';
$(document).on('click', '.showModalButton', function(){
    formId = $(this).data('formId');
    inputId = $(this).data('inputName');
    url = $(this).attr('value');
    if (modalForm.data('bs.modal').isShown) {
        modalForm.find('#modalContent')
                .load($(this).attr('value'));
        //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
    } else {
        //if modal isn't open; open it and load content
        modalForm.modal('show').find('#modalContent')
                .load($(this).attr('value'), function(responseTxt, statusTxt, jqXHR){
            if(statusTxt === "success"){
                formProcess();
                initJs();
            }
            if(statusTxt === "error"){
                alert("Error: " + jqXHR.status + " " + jqXHR.statusText);
            }
        });
         //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' 
        +'<h4>' + $(this).attr('title') + '</h4>';
    }
});

function formProcess() {
    formEl = document.getElementById(formId);
    $('#'+formId).on('beforeSubmit', function () {
        const yiiForm = $(this);
        $.ajax({
                type: yiiForm.attr('method'),
                url: yiiForm.attr('action'),
                data: yiiForm.serializeArray()
                })
                .done(function(data) {
                    if(data.success) {
                        const response = data;
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text:"Success",type:'success'});
                        modalForm.modal('hide');
                        let newOption = new Option(response.title, response.selected_id, true, true);
                        $('#'+inputId).append(newOption).trigger('change');
                    
                    } else if (data.validation) {
                        // server validation failed
                        yiiForm.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 3000;
                        PNotify.alert({text:'Error',type:'error'});
                    } else {
                        // incorrect server response
                    }
                })
                .fail(function () {
                    // request failed
                });
        
            return false; // prevent default form submission
    });
}

function initJs() {
  if (url.indexOf('wms-color') != -1){
    const colorPantoneSelectEl = document.getElementById('wmscolor-color_pantone_id');
    const fieldsetAnotherColorEl = document.getElementById('fieldset_another_color');
    const isAnotherColorCheckboxEl = document.getElementById('wmscolor-is_another_color');
 
    isAnotherColorCheckboxEl.addEventListener('change', anotherColorListener);
        
    function anotherColorListener() {
        if (this.checked) {
            colorPantoneSelectEl.disabled = true;
            fieldsetAnotherColorEl.disabled = false;
        } else {
            fieldsetAnotherColorEl.disabled = true;
            colorPantoneSelectEl.disabled = false;
        }
    }
        
    anotherColorListener();
  }
}

// multiple input events
jQuery('.formVariation').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.wms-color-id-var')
        .data('inputName', 'modelsvariationcolors-'+currentIndex+'-wms_color_id');
    row.find('.wms-desen-id-var')
        .data('inputName', 'modelsvariationcolors-'+currentIndex+'-wms_desen_id');
});

$('.create-pechat').click(function (e){
    e.preventDefault();
    var data = $('#pechat_form').serializeArray();
    let name;
    let title;
    let content;
    let attachments;
    let base_details;
    for(let i in data){
        let item = data[i];
        if(item.name == 'ModelVarPrints[width]'){
            title = item.value;
        }
        if(item.name == 'ModelVarPrints[name]'){
            name = item.value;
        }
        if(item.name == 'ModelVarPrints[height]'){
            content = item.value;
        }
        if(item.name == 'ModelVarPrints[image]'){
            attachments = item.value;
        }
        if(item.name == 'ModelVarPrints[base_details_list_id]'){
            base_details = item.value;
        }
    }
    $.ajax({
        type: 'GET',
        data: {name: name, title: title, content: content, base_details: base_details, attachments: attachments, id: "$modelsId"},
        url: pechat,
        success: function (res){
            if(res.status){
                if(res.data){
                    let items = res.data;
                    let w = items.width==null?'':items.width+'(sm)';
                    let h = items.height==null?'':items.height+'(sm)';
                    let n = items.name==null?'':items.name;
                    let all_name = n+'('+w+' '+h+')';
                    let selectOptions = new Option(all_name, items.id==null?'':items.id, true, true);
                    $('#modelsvariations-model_var_prints_id').append(selectOptions);
                    $('#pechat_modal').modal('hide');
                }
            }
            else{
               
            }
        }
    });     
});

$('.create-naqsh').click(function (e){
    e.preventDefault();
    var data = $('#naqsh_form').serializeArray();
    let name;
    let title;
    let content;
    let attachments;
    let base_details;
    for(let i in data){
        let item = data[i];
        if(item.name == 'ModelVarStone[width]'){
            title = item.value;
        }
        if(item.name == 'ModelVarStone[name]'){
            name = item.value;
        }
        if(item.name == 'ModelVarStone[height]'){
            content = item.value;
        }
        if(item.name == 'ModelVarStone[image]'){
            attachments = item.value;
        }
        if(item.name == 'ModelVarStone[base_details_list_id]'){
            base_details = item.value;
        }
    }
    $.ajax({
        type: 'GET',
        data: {name: name, title: title, content: content, base_details: base_details, attachments: attachments, id: "$modelsId"},
        url: naqsh,
        success: function (res){
            if(res.status){
                if(res.data){
                    let items = res.data;
                    let w = items.width==null?'':items.width+'(sm)';
                    let h = items.height==null?'':items.height+'(sm)';
                    let n = items.name==null?'':items.name;
                    let all_name = n+'('+w+' '+h+')';
                    let selectOptions = new Option(all_name, items.id==null?'':items.id, true, true);
                    $('#modelsvariations-model_var_stone_id').append(selectOptions);
                    $('#naqsh_modal').modal('hide');
                }
            }
            else{
               
            }
        }
    });     
});

JS;

$this->registerJs($js);