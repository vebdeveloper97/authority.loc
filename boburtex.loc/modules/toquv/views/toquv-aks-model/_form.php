<?php

use app\components\TabularInput\CustomTabularInput;
use app\modules\toquv\models\ToquvRawMaterials;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvAksModel */
/* @var $form yii\widgets\ActiveForm */
$urlColor = Yii::$app->urlManager->createUrl('toquv/toquv-aks-model/ajax-request');
$list = $model->getAksessuar();
?>

<div class="toquv-aks-model-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'raw_material_type')->hiddenInput(['id' => 'aks_raw_material_type'])->label(false) ?>
            <?= $form->field($model, 'trm_id')->widget(\kartik\select2\Select2::className(),[
                'data' => $list['list'],
                'options' => [
                    'options' => $list['ip'],
                    'id' => 'trm_aks_id',
                    'prompt' => Yii::t('app', 'Aksessuar turini tanlang')
                ],
                'pluginEvents' => [
                    "select2:select" => new JsExpression("function() { 
                            let t = $(this);
                            $('#aks_raw_material_type').val(t.find('option:selected').attr('type'));
                            let ip = JSON.parse(t.find('option:selected').attr('ip'));
                            $('#documentitems_id').multipleInput('clear');
                            ip.map(function(index,key){
                                $('#documentitems_id').multipleInput('add');
                                let lastObj = $('#documentitems_id table tbody tr:last');
                                let newOptionNe = new Option(index.toquvNe.name, index.ne_id, true, true);
                                lastObj.find('.toquv_ne_id').append(newOptionNe).val(index.ne_id).trigger('change');
                                let newOptionThread = new Option(index.toquvThread.name, index.thread_id, true, true);
                                lastObj.find('.toquv_thread_id').append(newOptionThread).trigger('change');
                                lastObj.find('.aks_ip_name').val(index.toquvNe.name+'-'+index.toquvThread.name);
                                lastObj.find('.aks_ip_id').val(index.id);
                                lastObj.find('.aks_parent_percentage').val(index.percentage);
                                lastObj.find('.aks_percentage').val(index.percentage).attr('data-percentage',index.percentage).attr('data-class','aks_percentage_'+index.id).addClass('aks_percentage_'+index.id);
                                lastObj.find('.js-input-remove').addClass('hidden');
                            });
                     }"),
                ]
            ]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'qavat')->textInput(['class'=>'number form-control']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'palasa')->textInput(['class'=>'number form-control']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'width')->textInput(['class'=>'number form-control']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'height')->textInput(['class'=>'number form-control']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <label for="fileImageAks"><?php echo Yii::t('app','Image')?></label>
            <br>
            <label class="upload-label labelUpload" <?=(!$model->isNewRecord)?"style='background-image: url(/web/".$model->image.")'":""?>>
                <input type="file" class="upload-image" id="fileImageAks">
            </label>
            <input type="hidden" id="textImageAks" name="image" disabled>
            <?= $form->field($model, 'add_info')->textarea(['rows'=>1]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= CustomTabularInput::widget([
                'id' => 'documentitems_id',
                'form' => $form,
                'models' => $models,
                'min' => 0,
                'theme' => 'bs',
                'rowOptions' => [
                    'id' => 'row{multiple_index_documentitems_id}',
                    'data-row-index' => '{multiple_index_documentitems_id}'
                ],
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'addButtonOptions' => [
                    'class' => 'hidden',
                ],
                'removeButtonOptions' => [
                    'class' => 'hidden',
                ],
                'cloneButton' => false,
                'extraButtons' => function ($model, $index, $context) {
                    return Html::tag('span', '', ['class' => "btn-show-hide-{$index} clone glyphicon glyphicon-duplicate btn btn-xs btn-info"]).Html::tag('span', '', ['class' => "btn-show-hide-{$index} glyphicon glyphicon-remove multiple-input-list__btn js-input-remove btn btn-xs btn-danger"]);
                },
                /*'showFooter' => true,*/
                'columns' => [
                    [
                        'name' => 'name',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'aks_ip_name'
                        ]
                    ],
                    [
                        'name' => 'parent_percentage',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'aks_parent_percentage'
                        ]
                    ],
                    [
                        'name' => 'ip_id',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'aks_ip_id'
                        ]
                    ],
                    [
                        'name' => 'toquv_ne_id',
                        'title' => 'Ne',
                        'type' => 'dropdownList',
                        'items' => function($model){
                            return $model->getList('ne');
                        },
                        'options' => [
                            'class' => 'toquv_ne_id'
                        ]
                    ],
                    [
                        'name' => 'toquv_thread_id',
                        'title' => 'Iplik turi',
                        'type' => 'dropdownList',
                        'items' => function($model){
                            return $model->getList('thread');
                        },
                        'options' => [
                            'class' => 'toquv_thread_id'
                        ]
                    ],
                    [
                        'name' => 'toquv_ip_color_id',
                        'type' => Select2::className(),
                        'title' => 'Ip rangi',
                        'options' => [
                            'data' => \app\modules\toquv\models\ToquvIpColor::getFullNameAllTypes(),
                            'options' => [
                                'prompt' => Yii::t('app', 'Ip rangi'),
                                'class' => 'toquv_ip_color_id'
                            ],
                            'pluginOptions' =>[
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
                            ],
                        ],
                    ],
                    [
                        'name' => 'color_pantone_id',
                        'type' => Select2::className(),
                        'title' => 'Rang',
                        'options' => [
                            'data' => ($model->isNewRecord)?[]: \app\modules\toquv\models\ToquvAksModel::getColorList(false,null,$model['id']),
                            'options' => [
                                'prompt' => Yii::t('app', 'Panton rangi'),
                                'class' => 'color_pantone_id',
                            ],
                            'pluginOptions' =>[
                                'minimumInputLength' => 3,
                                'language' => [
                                    'errorLoading' => new JsExpression(
                                        "function () { return '...'; }"
                                    ),
                                ],
                                'ajax' => [
                                    'url' => $urlColor,
                                    'dataType' => 'json',
                                    'data' => new JsExpression(
                                        "function(params) {
                                        var currIndex = 
                                        $(this).parents('tr').attr('data-row-index');
                                        return { 
                                            q:params.term,index:currIndex
                                        };
                                
                                    }"),
                                    'cache' => true
                                ],
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
                            ],
                        ],
                    ],
                    [
                        'name' => 'height',
                        'title' => Yii::t('app', "Eni(%)"),
                        'options' => [
                            'prompt' => Yii::t('app', "Eni foizda"),
                            'class' => 'aks_height number',
                        ],
                        'headerOptions' => [
                            'width' => '60px'
                        ]
                    ],
                    [
                        'name' => 'height_sm',
                        'title' => Yii::t('app', "Eni(sm)"),
                        'options' => [
                            'prompt' => Yii::t('app', "Eni"),
                            'class' => 'aks_height_sm number',
                        ],
                        'headerOptions' => [
                            'width' => '60px'
                        ]
                    ],
                    [
                        'name' => 'percentage',
                        'title' => Yii::t('app', "Miqdori(%)"),
                        'options' => function($model){
                            return [
                                'prompt' => Yii::t('app', "Miqdori foizda"),
                                'class' => 'aks_percentage number aks_percentage_'.$model->ip_id,
                                'data-percentage' => $model->parent_percentage,
                                'data-class' => 'aks_percentage_'.$model->ip_id
                            ];
                        },
                        'headerOptions' => [
                            'width' => '60px'
                        ]
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$css = <<< CSS
    .form-control{
        padding: 1px 3px;
    }
CSS;
$this->registerCss($css);
$css = <<< CSS
    .upload-label{
    background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQyIDQyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0MiA0MjsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiPgo8cGF0aCBzdHlsZT0iZmlsbDojMjNBMjREOyIgZD0iTTM3LjA1OSwxNkgyNlY0Ljk0MUMyNiwyLjIyNCwyMy43MTgsMCwyMSwwcy01LDIuMjI0LTUsNC45NDFWMTZINC45NDFDMi4yMjQsMTYsMCwxOC4yODIsMCwyMSAgczIuMjI0LDUsNC45NDEsNUgxNnYxMS4wNTlDMTYsMzkuNzc2LDE4LjI4Miw0MiwyMSw0MnM1LTIuMjI0LDUtNC45NDFWMjZoMTEuMDU5QzM5Ljc3NiwyNiw0MiwyMy43MTgsNDIsMjFTMzkuNzc2LDE2LDM3LjA1OSwxNnoiLz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==);
     background-size: 100% 100%;
     width: 420px;
     height: 180px;
     border: 1px solid;
     /*border-radius: 25px;*/
     margin-right: 5px;
     cursor: pointer;
     position: relative;
}
.upload-label:hover{
     background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQyIDQyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0MiA0MjsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiPgo8cGF0aCBzdHlsZT0iZmlsbDojMjNBMjREOyIgZD0iTTM3LjA1OSwxNkgyNlY0Ljk0MUMyNiwyLjIyNCwyMy43MTgsMCwyMSwwcy01LDIuMjI0LTUsNC45NDFWMTZINC45NDFDMi4yMjQsMTYsMCwxOC4yODIsMCwyMSAgczIuMjI0LDUsNC45NDEsNUgxNnYxMS4wNTlDMTYsMzkuNzc2LDE4LjI4Miw0MiwyMSw0MnM1LTIuMjI0LDUtNC45NDFWMjZoMTEuMDU5QzM5Ljc3NiwyNiw0MiwyMy43MTgsNDIsMjFTMzkuNzc2LDE2LDM3LjA1OSwxNnoiLz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==);
     background-size: 100px 100px;
     background-repeat: no-repeat;
     background-position: center;
}
label.upload-label .udalit{
     position: absolute!important;
     right: 0px!important;
     top: -20px;
     width: 40px!important;
     font-size: 11px!important;
     z-index: 9;
}
.upload-image{
     opacity: 0;
}
.upload-container .form-group{
     float: left;
     padding: 10px;
}
.modal-lg {
    width: 900px;
}
.multiple-input-list.table-renderer .list-cell__button {
    width: 60px;
}
CSS;
$this->registerCss($css);