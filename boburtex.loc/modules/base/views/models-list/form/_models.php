<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\components\KCFinderInputWidgetCustom;
use yii\web\JsExpression;
use yii\helpers\Html;
use app\components\CustomFileInput\CustomFileInput as FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */

?>


    <div class="row form-group">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'article') ?>
            <?= $form->field($model, 'view_id')->dropDownList($model->getModelView()) ?>

        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'model_season')->dropDownList($model->seasonList) ?>
                </div>
                <div class="col-md-8">
                    <?= $form->field($model, 'users_id')->widget(Select2::class, ['data' => $model->getHrEmployeeList(),
                        'options' => [
                            'prompt' => Yii::t('app', 'Dizaynerni tanlang'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="checkboxList">
                        <?= $form->field($model, 'is_kit', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Komplekt") . '</span>
                </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="checkboxList">
                        <?= $form->field($model, 'baski', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Tub bosma") . '</span>
            </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                        <?= $form->field($model, 'baski_rotatsion', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Rotatsion bosma") . '</span>
            </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                        <?= $form->field($model, 'prints', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Print") . '</span>
            </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                        <?= $form->field($model, 'stone', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Naqsh\Tosh") . '</span>
            </label>',])->checkbox(['class' => 'checkbox__input'], false) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-12">
            <?= $form->field($model, 'base_pattern_id')->widget(Select2::class, [
                'options' => ['placeholder' => Yii::t('app', 'Select')],
                'data' => $model->getPatternList(),
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-lg-6">
            <?= $form->field($model, 'model_images')->widget(KCFinderInputWidgetCustom::class, [
                'multiple' => true,
                'buttonLabel' => Yii::t('app', "Rasm qo'shish"),
                'isMultipleValue' => true,
                'id' => 'attachedImage',
                'kcfBrowseOptions' => [
                    'langCode' => 'ru'
                ],
                'kcfOptions' => [
                    'uploadURL' => '/uploads',
                    'cookieDomain' => $_SERVER['SERVER_NAME'],
                    'uploadDir' => Yii::getAlias('@app') . '/web/uploads',
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
            ])->label(''); ?>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'washing_notes')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'product_details')->textarea(['rows' => 2]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'finishing_notes')->textarea(['rows' => 1]) ?>
            <?= $form->field($model, 'packaging_notes')->textarea(['rows' => 1]) ?>
        </div>
    </div>
<?php
$js = <<< JS
    $("body").delegate(".removeImage","click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        if(keyImage==$(".removeImage").eq(0).attr("data-key")||keyImage==$(".removeImage[data-url!='deleted']").eq(0).attr("data-key")){
            if($(".removeImage[data-url!='deleted']").length>1){
                let isMain = $(".removeImage[data-url!='deleted']").eq(1);
                let num = isMain.attr("data-key");
                if($("#model-is-main").length>0){
                    $("#model-is-main").val(num);
                }else{
                    $('#imagesDiv').append('<input type="hidden" id="model-is-main" name="ModelsList[isMain]" value="'+num+'">');
                }
            }else{
               $("#model-is-main").remove();
            }
        }
        $("#imagesDiv").append('<input id="removeAttachment'+keyImage+'" type="hidden" class="remove-image-list" name="ModelsList[remove][]" value="'+keyImage+'">');
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",0.4);
        if(t.attr('data-url')!='restored'){
            t.after('<button type="button" class="kv-file-restore btn btn-sm btn-kv btn-default btn-outline-secondary" title="Restore deleted file" data-key="'+keyImage+'"><i class="glyphicon glyphicon-repeat"></i></button>');
        }else{
            t.next().show();
        }
        t.hide().attr('data-url','deleted');
    });
    $("body").delegate(".kv-file-restore","click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        $("#removeAttachment"+keyImage).remove();
        let indexPrev = $(".removeImage").index(t.prev());
        let index = $(".removeImage").index($(".removeImage[data-url!='deleted']").eq(0));
        if($(".removeImage[data-url!='deleted']").length>0){
            if(keyImage==$(".removeImage").eq(0).attr("data-key")){
                if($("#model-is-main").length>0){
                    $("#model-is-main").val(keyImage);
                }else{
                    $('#imagesDiv').append('<input type="hidden" id="model-is-main" name="ModelsList[isMain]" value="'+keyImage+'">');
                }
            }else{
                if(indexPrev<index){
                    if($("#model-is-main").length>0){
                        $("#model-is-main").val(keyImage);
                    }else{
                        $('#imagesDiv').append('<input type="hidden" id="model-is-main" name="ModelsList[isMain]" value="'+keyImage+'">');
                    }
                }
            }
        }else{
            if($("#model-is-main").length>0){
                $("#model-is-main").val(keyImage);
            }else{
                $('#imagesDiv').append('<input type="hidden" id="model-is-main" name="ModelsList[isMain]" value="'+keyImage+'">');
            }
        }
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",1);
        t.hide();
        t.prev().show().attr('data-url','restored');
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
$this->registerCss('
.checkbox__label:before{content:\' \';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:\' \';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:2.5rem;margin-left:15px;line-height:.75;font-size:11px;}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}
');