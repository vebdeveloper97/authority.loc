<?php
use app\modules\base\models\ModelsList;
use yii\helpers\Url;
use kartik\widgets\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\helpers\Script;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseModelDocument */
/* @var $form yii\widgets\ActiveForm */
/* @var $sizes \app\modules\base\models\BaseModelSizes */
/* @var $note \app\modules\base\models\BaseModelTikuvNote */
/* @var $pluginOptionsTable */
/* @var $pluginOptionsTikuv */
?>

<div class="base-model-document-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row" >
        <div class="col-md-6">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'model_id')->widget(Select2::class,[
                'data' => ArrayHelper::map(ModelsList::find()->all(), 'id', function($m){
                    return $m['article'];
                }),
                'options' => [
                        'placeholder' => Yii::t('app', 'Select...')
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items',
                'widgetItem' => '.house-item',
                'limit' => 100,
                'min' => 1,
                'insertButton' => '.add-house',
                'deleteButton' => '.remove-house',
                'model' => $sizes[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'add_info',
                    'tikuv_file',
                    'table_file',
                ],
            ]); ?>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><?=Yii::t('app', 'Size Select')?></th>
                    <th style="width: 70%;"><?=Yii::t('app', 'Tikuv ta\'limoti izoh')?></th>
                    <th class="text-center" style="width: 90px;">
                        <button type="button" class="add-house btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
                    </th>
                </tr>
                </thead>
                <tbody class="container-items">
                <?php foreach ($sizes as $indexHouse => $modelHouse): ?>
                        <tr class="house-item" style="border-top: 30px solid #3c8dbc; border-left: 3px solid #3c8dbc; border-right: 3px solid #3c8dbc; border-bottom: 5px solid #3c8dbc;  ">
                            <td class="vcenter" style="width: 50%">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form->field($modelHouse, "[{$indexHouse}]size_id")->widget(Select2::className(),[
                                                'data' => ArrayHelper::map(\app\models\Size::find()->all(), 'id', 'name'),
                                                'options' => [
                                                    'placeholder' => Yii::t('app', 'Size Select'),
                                                    'multiple' => true,
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true,
                                                ],
                                                'pluginEvents' => [
                                                        "change" => "function() { console.log('change'); }",]
                                            ]) ?>
                                        </div>
                                        <div class="col-md-12">
                                            <?= $form->field($modelHouse, "[{$indexHouse}]add_info")->textarea(['rows' => 1, 'columns' => 5])?>
                                            <?php if($pluginOptionsTikuv): ?>
                                            <?=$form->field($modelHouse, "[{$indexHouse}]items_id")->hiddenInput()->label(false)?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-12">
                                            <?= $form->field($modelHouse,"[{$indexHouse}]table_file")->widget(\kartik\widgets\FileInput::class, [
                                                'options' => [
                                                    'multiple'=>true,
                                                    'class' => 'fileUploadTable'
                                                ],
                                                'pluginOptions' => [
                                                    'showPreview' => false,
                                                    'showCaption' => true,
                                                    'showRemove' => true,
                                                    'showUpload' => false,
                                                    'initialPreview'=> [],
                                                    'browseClass' => 'btn btn-success',
                                                    'removeClass' => 'btn btn-danger',
                                                    'initialPreviewAsData'=>true,
                                                    'overwriteInitial'=>false,
                                                ]
                                            ]);?>
                                            <?php
                                            if($pluginOptionsTable && $pluginOptionsTable[$indexHouse]){
                                                echo "<div class='parentDiv'><strong>".Yii::t('app', 'Yuklangan fayllar: ')."</strong><br>";
                                                foreach ($pluginOptionsTable[$indexHouse] as $key => $item) {
                                                    if(is_int($key)){
                                                        ?>
                                                        <div>
                                                            <span class="text-success"><?=substr($item, '0', '50')?></span>
                                                            <a href="<?=$item; ?>" class="btn btn-success btn-xs eye-open-tikuv" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                            <a href="<?=Url::to(['delete-files', 'id' => $pluginOptionsTable[$indexHouse]['id'][$key], 'type' => 'table']); ?>" class="btn btn-danger btn-xs eye-open-tikuv"><i class="glyphicon glyphicon-trash"></i></a>
                                                        </div>
                                                        <br>
                                            <?php
                                                    }
                                                }
                                                echo "</div>";
                                            }
                                            ?>
                                savlo        </div>
                                        <div class="col-md-12">
                                            <?= $form->field($modelHouse,"[{$indexHouse}]tikuv_file")->widget(\kartik\widgets\FileInput::class, [
                                                'options' => [
                                                    'multiple'=>true,
                                                ],
                                                'pluginOptions' => [
                                                    'showPreview' => false,
                                                    'showCaption' => true,
                                                    'showRemove' => true,
                                                    'showUpload' => false,
                                                    'initialPreview'=> [],
                                                    'browseClass' => 'btn btn-success',
                                                    'removeClass' => 'btn btn-danger',
                                                    'initialPreviewAsData'=>true,
                                                    'overwriteInitial'=>false,
                                                ]
                                            ]);?>
                                            <?php
                                            if($pluginOptionsTikuv && $pluginOptionsTikuv[$indexHouse]){
                                                echo "<div class='parentDiv'><strong>".Yii::t('app', 'Yuklangan fayllar: ')."</strong><br>";
                                                foreach ($pluginOptionsTikuv[$indexHouse] as $k => $item) {
                                                    if(is_int($k)){
                                                        ?>
                                                        <div>
                                                            <span class="text-success"><?=substr($item, '0', '50')?></span>
                                                            <a href="<?=$item; ?>" class="btn btn-success btn-xs eye-open-tikuv" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                            <a href="<?=Url::to(['delete-files', 'id' => $pluginOptionsTikuv[$indexHouse]['id'][$k], 'type' => 'tikuv'])?>" class="btn btn-danger btn-xs eye-open-tikuv"><i class="glyphicon glyphicon-trash"></i></a>
                                                        </div>
                                                        <br>
                                            <?php
                                                    }
                                                }
                                                echo "</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>

                            </td>
                            <td style="width: 30%">
                                <?= $this->render('_items/_form-rooms', [
                                    'form' => $form,
                                    'indexHouse' => $indexHouse,
                                    'modelsRoom' => $note[$indexHouse],
                                    'modelHouse' => $modelHouse,
                                ]) ?>
                            </td>
                            <td class="text-center vcenter deleteItems" style="width: 200px">
                                <?php if($pluginOptionsTikuv): ?>
                                    <a href="<?=Url::to(['delete-items', 'id' => $modelHouse['doc_items_id']])?>" title="<?=Yii::t('app', "Elementni o'chirish")?>" class="delete-items btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a>
                                    <hr>
                                    <a href="<?=Url::to(['save-items', 'id' => $modelHouse['doc_items_id']])?>" title="<?=Yii::t('app', "Save and finish")?>" class="save-items btn btn-success btn-xs"><i class="glyphicon glyphicon-save-file"></i></a>
                                <?php else: ?>
                                    <button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php DynamicFormWidget::end(); ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerCss("
    html{
        zoom: 80%;
    }
    .eye-open-tikuv{
        border-radius: none!important;
        padding: 2px 25px;
    }

");
$message = Yii::t('app', "Siz rostdan ham shu elementni o'chirmoqchimisiz?");
$messages = Yii::t('app', "Siz rostdan ham shu elementni saqlamoqchimisiz?");
$js =<<< JS
    $('.delete-items').click(function(e){
        let isTrue = confirm("$message");
        if(!isTrue){
            e.preventDefault();
        }
    });
    
    $('.save-items').click(function(e){
        let isFalse = confirm("$messages");
        if(!isFalse){
            e.preventDefault();
        }
    })

JS;
$this->registerJs($js);

Script::begin();
?>
    <script>

        $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            function initSelect2DropStyle(a,b,c){
                initS2Loading(a,b,c);
            }
            function initSelect2Loading(a,b){
                initS2Loading(a,b);
            }

            $('.house-item:last').find('select').val('').trigger('change');
            $('.house-item:last').find('.parentDiv').css('display', 'none');
            $('.house-item:last').find('.delete-items').css('display', 'none');
            $('.house-item:last').find('.deleteItems').html('<button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>');
        });
        (function ($) {
            var pluginName = 'yiiDynamicForm';

            var regexID = /^(.+?)([-\d-]{1,})(.+)$/i;

            var regexName = /(^.+?)([\[\d{1,}\]]{1,})(\[.+\]$)/i;

            $.fn.yiiDynamicForm = function (method) {
                if (methods[method]) {
                    return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
                } else if (typeof method === 'object' || !method) {
                    return methods.init.apply(this, arguments);
                } else {
                    $.error('Method ' + method + ' does not exist on jQuery.yiiDynamicForm');
                    return false;
                }
            };

            var events = {
                beforeInsert: 'beforeInsert',
                afterInsert: 'afterInsert',
                beforeDelete: 'beforeDelete',
                afterDelete: 'afterDelete',
                limitReached: 'limitReached'
            };

            var methods = {
                init: function (widgetOptions) {
                    return this.each(function () {
                        widgetOptions.template = _parseTemplate(widgetOptions);
                    });
                },

                addItem: function (widgetOptions, e, $elem) {
                    _addItem(widgetOptions, e, $elem);
                },

                deleteItem: function (widgetOptions, e, $elem) {
                    _deleteItem(widgetOptions, e, $elem);
                },

                updateContainer: function () {
                    var widgetOptions = eval($(this).attr('data-dynamicform'));
                    _updateAttributes(widgetOptions);
                    _restoreSpecialJs(widgetOptions);
                    _fixFormValidaton(widgetOptions);
                }
            };

            var _parseTemplate = function(widgetOptions) {

                var $template = $(widgetOptions.template);
                $template.find('div[data-dynamicform]').each(function(){
                    var widgetOptions = eval($(this).attr('data-dynamicform'));
                    if ($(widgetOptions.widgetItem).length > 1) {
                        var item = $(this).find(widgetOptions.widgetItem).first()[0].outerHTML;
                        $(this).find(widgetOptions.widgetBody).html(item);
                    }
                });

                $template.find('input, textarea, select').each(function() {
                    $(this).val('');
                });

                $template.find('input[type="checkbox"], input[type="radio"]').each(function() {
                    var inputName = $(this).attr('name');
                    var $inputHidden = $template.find('input[type="hidden"][name="' + inputName + '"]').first();
                    if ($inputHidden) {
                        $(this).val(1);
                        $inputHidden.val(0);
                    }
                });

                return $template;
            };

            var _getWidgetOptionsRoot = function(widgetOptions) {
                return eval($(widgetOptions.widgetBody).parents('div[data-dynamicform]').last().attr('data-dynamicform'));
            };

            var _getLevel = function($elem) {
                var level = $elem.parents('div[data-dynamicform]').length;
                level = (level < 0) ? 0 : level;
                return level;
            };

            var _count = function($elem, widgetOptions) {
                return $elem.closest('.' + widgetOptions.widgetContainer).find(widgetOptions.widgetItem).length;
            };

            var _createIdentifiers = function(level) {
                return new Array(level + 2).join('0').split('');
            };

            var _addItem = function(widgetOptions, e, $elem) {
                var count = _count($elem, widgetOptions);

                if (count < widgetOptions.limit) {
                    $toclone = $(widgetOptions.template);
                    $newclone = $toclone.clone(false, false);

                    if (widgetOptions.insertPosition === 'top') {
                        $elem.closest('.' + widgetOptions.widgetContainer).find(widgetOptions.widgetBody).prepend($newclone);
                    } else {
                        $elem.closest('.' + widgetOptions.widgetContainer).find(widgetOptions.widgetBody).append($newclone);
                    }

                    _updateAttributes(widgetOptions);
                    _restoreSpecialJs(widgetOptions);
                    _fixFormValidaton(widgetOptions);
                    $elem.closest('.' + widgetOptions.widgetContainer).triggerHandler(events.afterInsert, $newclone);
                } else {
                    // trigger a custom event for hooking
                    $elem.closest('.' + widgetOptions.widgetContainer).triggerHandler(events.limitReached, widgetOptions.limit);
                }
            };

            var _removeValidations = function($elem, widgetOptions, count) {
                if (count > 1) {
                    $elem.find('div[data-dynamicform]').each(function() {
                        var currentWidgetOptions = eval($(this).attr('data-dynamicform'));
                        var level           = _getLevel($(this));
                        var identifiers     = _createIdentifiers(level);
                        var numItems        = $(this).find(currentWidgetOptions.widgetItem).length;

                        for (var i = 1; i <= numItems -1; i++) {
                            var aux = identifiers;
                            aux[level] = i;
                            currentWidgetOptions.fields.forEach(function(input) {
                                var id = input.id.replace("{}", aux.join('-'));
                                if ($("#" + currentWidgetOptions.formId).yiiActiveForm("find", id) !== "undefined") {
                                    $("#" + currentWidgetOptions.formId).yiiActiveForm("remove", id);
                                }
                            });
                        }
                    });

                    var level          = _getLevel($elem.closest('.' + widgetOptions.widgetContainer));
                    var widgetOptionsRoot       = _getWidgetOptionsRoot(widgetOptions);
                    var identifiers    = _createIdentifiers(level);
                    identifiers[0]     = $(widgetOptionsRoot.widgetItem).length - 1;
                    identifiers[level] = count - 1;

                    widgetOptions.fields.forEach(function(input) {
                        var id = input.id.replace("{}", identifiers.join('-'));
                        if ($("#" + widgetOptions.formId).yiiActiveForm("find", id) !== "undefined") {
                            $("#" + widgetOptions.formId).yiiActiveForm("remove", id);
                        }
                    });
                }
            };

            var _deleteItem = function(widgetOptions, e, $elem) {
                var count = _count($elem, widgetOptions);

                if (count > widgetOptions.min) {
                    $todelete = $elem.closest(widgetOptions.widgetItem);

                    // trigger a custom event for hooking
                    var eventResult = $('.' + widgetOptions.widgetContainer).triggerHandler(events.beforeDelete, $todelete);
                    if (eventResult !== false) {
                        _removeValidations($todelete, widgetOptions, count);
                        $todelete.remove();
                        _updateAttributes(widgetOptions);
                        _restoreSpecialJs(widgetOptions);
                        _fixFormValidaton(widgetOptions);
                        $('.' + widgetOptions.widgetContainer).triggerHandler(events.afterDelete);
                    }
                }
            };

            var _updateAttrID = function($elem, index) {
                var widgetOptions = eval($elem.closest('div[data-dynamicform]').attr('data-dynamicform'));
                var id            = $elem.attr('id');
                var newID         = id;

                if (id !== undefined) {
                    var matches = id.match(regexID);
                    if (matches && matches.length === 4) {
                        matches[2] = matches[2].substring(1, matches[2].length - 1);
                        var identifiers = matches[2].split('-');
                        identifiers[0] = index;

                        if (identifiers.length > 1) {
                            var widgetsOptions = [];
                            $elem.parents('div[data-dynamicform]').each(function(i){
                                widgetsOptions[i] = eval($(this).attr('data-dynamicform'));
                            });

                            widgetsOptions = widgetsOptions.reverse();
                            for (var i = identifiers.length - 1; i >= 1; i--) {
                                if(typeof widgetsOptions[i] !== 'undefined'){
                                    identifiers[i] = $elem.closest(widgetsOptions[i].widgetItem).index();
                                }
                            }
                        }

                        newID = matches[1] + '-' + identifiers.join('-') + '-' + matches[3];
                        $elem.attr('id', newID);
                    } else {
                        newID = id + index;
                        $elem.attr('id', newID);
                    }
                }

                if (id !== newID) {
                    $elem.closest(widgetOptions.widgetItem).find('.field-' + id).each(function() {
                        $(this).removeClass('field-' + id).addClass('field-' + newID);
                    });
                    // update "for" attribute
                    $elem.closest(widgetOptions.widgetItem).find("label[for='" + id + "']").attr('for',newID);
                }

                return newID;
            };

            var _updateAttrName = function($elem, index) {
                var name = $elem.attr('name');

                if (name !== undefined) {
                    var matches = name.match(regexName);

                    if (matches && matches.length === 4) {
                        matches[2] = matches[2].replace(/\]\[/g, "-").replace(/\]|\[/g, '');
                        var identifiers = matches[2].split('-');
                        identifiers[0] = index;

                        if (identifiers.length > 1) {
                            var widgetsOptions = [];
                            $elem.parents('div[data-dynamicform]').each(function(i){
                                widgetsOptions[i] = eval($(this).attr('data-dynamicform'));
                            });

                            widgetsOptions = widgetsOptions.reverse();
                            for (var i = identifiers.length - 1; i >= 1; i--) {
                                identifiers[i] = $elem.closest(widgetsOptions[i].widgetItem).index();
                            }
                        }

                        name = matches[1] + '[' + identifiers.join('][') + ']' + matches[3];
                        $elem.attr('name', name);
                    }
                }

                return name;
            };

            var _updateAttributes = function(widgetOptions) {
                var widgetOptionsRoot = _getWidgetOptionsRoot(widgetOptions);

                $(widgetOptionsRoot.widgetItem).each(function(index) {
                    var $item = $(this);
                    $(this).find('*').each(function() {
                        // update "id" attribute
                        _updateAttrID($(this), index);

                        // update "name" attribute
                        _updateAttrName($(this), index);
                    });
                });
            };

            var _fixFormValidatonInput = function(widgetOptions, attribute, id, name) {
                if (attribute !== undefined) {
                    attribute           = $.extend(true, {}, attribute);
                    attribute.id        = id;
                    attribute.container = ".field-" + id;
                    attribute.input     = "#" + id;
                    attribute.name      = name;
                    attribute.value     = $("#" + id).val();
                    attribute.status    = 0;

                    if ($("#" + widgetOptions.formId).yiiActiveForm("find", id) !== "undefined") {
                        $("#" + widgetOptions.formId).yiiActiveForm("remove", id);
                    }

                    $("#" + widgetOptions.formId).yiiActiveForm("add", attribute);
                }
            };

            var _fixFormValidaton = function(widgetOptions) {
                var widgetOptionsRoot = _getWidgetOptionsRoot(widgetOptions);

                $(widgetOptionsRoot.widgetBody).find('input, textarea, select').each(function() {
                    var id   = $(this).attr('id');
                    var name = $(this).attr('name');

                    if (id !== undefined && name !== undefined) {
                        currentWidgetOptions = eval($(this).closest('div[data-dynamicform]').attr('data-dynamicform'));
                        var matches = id.match(regexID);

                        if (matches && matches.length === 4) {
                            matches[2]      = matches[2].substring(1, matches[2].length - 1);
                            var level       = _getLevel($(this));
                            var identifiers = _createIdentifiers(level -1);
                            var baseID      = matches[1] + '-' + identifiers.join('-') + '-' + matches[3];
                            var attribute   = $("#" + currentWidgetOptions.formId).yiiActiveForm("find", baseID);
                            _fixFormValidatonInput(currentWidgetOptions, attribute, id, name);
                        }
                    }
                });
            };

            var _restoreSpecialJs = function(widgetOptions) {
                var widgetOptionsRoot = _getWidgetOptionsRoot(widgetOptions);

                // "kartik-v/yii2-widget-datepicker"
                var $hasDatepicker = $(widgetOptionsRoot.widgetItem).find('[data-krajee-datepicker]');
                if ($hasDatepicker.length > 0) {
                    $hasDatepicker.each(function() {
                        $(this).parent().removeData().datepicker('remove');
                        $(this).parent().datepicker(eval($(this).attr('data-krajee-datepicker')));
                    });
                }

                // "kartik-v/yii2-widget-timepicker"
                var $hasTimepicker = $(widgetOptionsRoot.widgetItem).find('[data-krajee-timepicker]');
                if ($hasTimepicker.length > 0) {
                    $hasTimepicker.each(function() {
                        $(this).removeData().off();
                        $(this).parent().find('.bootstrap-timepicker-widget').remove();
                        $(this).unbind();
                        $(this).timepicker(eval($(this).attr('data-krajee-timepicker')));
                    });
                }

                // "kartik-v/yii2-money"
                var $hasMaskmoney = $(widgetOptionsRoot.widgetItem).find('[data-krajee-maskMoney]');
                if ($hasMaskmoney.length > 0) {
                    $hasMaskmoney.each(function() {
                        $(this).parent().find('input').removeData().off();
                        var id = '#' + $(this).attr('id');
                        var displayID  = id + '-disp';
                        $(displayID).maskMoney('destroy');
                        $(displayID).maskMoney(eval($(this).attr('data-krajee-maskMoney')));
                        $(displayID).maskMoney('mask', parseFloat($(id).val()));
                        $(displayID).on('change', function () {
                            var numDecimal = $(displayID).maskMoney('unmasked')[0];
                            $(id).val(numDecimal);
                            $(id).trigger('change');
                        });
                    });
                }

                // "kartik-v/yii2-widget-fileinput"
                var $hasFileinput = $(widgetOptionsRoot.widgetItem).find('[data-krajee-fileinput]');
                if ($hasFileinput.length > 0) {
                    $hasFileinput.each(function() {
                        $(this).fileinput(eval($(this).attr('data-krajee-fileinput')));
                    });
                }

                // "kartik-v/yii2-widget-touchspin"
                var $hasTouchSpin = $(widgetOptionsRoot.widgetItem).find('[data-krajee-TouchSpin]');
                if ($hasTouchSpin.length > 0) {
                    $hasTouchSpin.each(function() {
                        $(this).TouchSpin('destroy');
                        $(this).TouchSpin(eval($(this).attr('data-krajee-TouchSpin')));
                    });
                }

                // "kartik-v/yii2-widget-colorinput"
                var $hasSpectrum = $(widgetOptionsRoot.widgetItem).find('[data-krajee-spectrum]');
                if ($hasSpectrum.length > 0) {
                    $hasSpectrum.each(function() {
                        var id = '#' + $(this).attr('id');
                        var sourceID  = id + '-source';
                        $(sourceID).spectrum('destroy');
                        $(sourceID).unbind();
                        $(id).unbind();
                        var configSpectrum = eval($(this).attr('data-krajee-spectrum'));
                        configSpectrum.change = function (color) {
                            jQuery(id).val(color.toString());
                        };
                        $(sourceID).attr('name', $(sourceID).attr('id'));
                        $(sourceID).spectrum(configSpectrum);
                        $(sourceID).spectrum('set', jQuery(id).val());
                        $(id).on('change', function(){
                            $(sourceID).spectrum('set', jQuery(id).val());
                        });
                    });
                }

                var _restoreKrajeeDepdrop = function($elem) {
                    var configDepdrop = $.extend(true, {}, eval($elem.attr('data-krajee-depdrop')));
                    var inputID = $elem.attr('id');
                    var matchID = inputID.match(regexID);

                    if (matchID && matchID.length === 4) {
                        for (index = 0; index < configDepdrop.depends.length; ++index) {
                            var match = configDepdrop.depends[index].match(regexID);
                            if (match && match.length === 4) {
                                configDepdrop.depends[index] = match[1] + matchID[2] + match[3];
                            }
                        }
                    }
                    $elem.depdrop(configDepdrop);
                };

                // "kartik-v/yii2-widget-depdrop"
                var _restoreKrajeeDepdrop = function($elem) {
                    var configDepdrop = $.extend(true, {}, eval($elem.attr('data-krajee-depdrop')));
                    var inputID = $elem.attr('id');
                    var matchID = inputID.match(regexID);

                    if (matchID && matchID.length === 4) {
                        for (index = 0; index < configDepdrop.depends.length; ++index) {
                            var match = configDepdrop.depends[index].match(regexID);
                            if (match && match.length === 4) {
                                configDepdrop.depends[index] = match[1] + matchID[2] + match[3];
                            }
                        }
                    }
                    $elem.depdrop(configDepdrop);
                };
                var $hasDepdrop = $(widgetOptionsRoot.widgetItem).find('[data-krajee-depdrop]');
                if ($hasDepdrop.length > 0) {
                    $hasDepdrop.each(function() {
                        if ($(this).data('select2') === undefined) {
                            $(this).removeData().off();
                            $(this).unbind();
                            _restoreKrajeeDepdrop($(this));
                        }
                        var configDepdrop = eval($(this).attr('data-krajee-depdrop'));
                        $(this).depdrop(configDepdrop);
                    });
                }

                // "kartik-v/yii2-widget-select2"
                var $hasSelect2 = $(widgetOptionsRoot.widgetItem).find('[data-krajee-select2]');
                if ($hasSelect2.length > 0) {
                    $hasSelect2.each(function() {
                        var id = $(this).attr('id');
                        var configSelect2 = eval($(this).attr('data-krajee-select2'));
                        $.when($('#' + id).select2(configSelect2)).done(initS2Loading(id));
                        $('#' + id).on('select2-open', function() {
                            initSelect2DropStyle(id)
                        });
                        if ($(this).attr('data-krajee-depdrop')) {
                            $(this).on('depdrop.beforeChange', function(e,i,v) {
                                var configDepdrop = eval($(this).attr('data-krajee-depdrop'));
                                var loadingText = (configDepdrop.loadingText)? configDepdrop.loadingText : 'Loading ...';
                                $('#' + id).select2('data', {text: loadingText});
                            });
                            $(this).on('depdrop.change', function(e,i,v,c) {
                                $('#' + id).select2('val', $('#' + id).val());
                            });
                        }
                    });
                }
            };

        })(window.jQuery);

    </script>
<?php
Script::end();
?>