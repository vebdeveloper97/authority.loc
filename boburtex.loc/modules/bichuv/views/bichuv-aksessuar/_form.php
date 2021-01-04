<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */
/* @var $responsible app\modules\bichuv\models\BichuvDocResponsible */
/* @var $form yii\widgets\ActiveForm */
$tayyor_emas = Yii::t('app', 'Tayyor emas');
$tayyor = Yii::t('app', 'Tayyor');
$sizes = $model->getSizeCustomListPercentage('customDisabled alert-success','');

?>

    <div class="bichuv-aksessuar-form">
        <h4><?php echo Yii::t('app','Model')?> : <?=($model->moi)?$model->moi->info:''?></h4><br>
        <div class="pull-right checkbox">
            <?= Html::beginTag("label") ?>
            <?= Html::checkbox('check_all_checkbox', false, [
                'class' => "checkbox_input",
                'id' => "check_all_checkbox",

            ]) ?>
            <?= Html::tag("div", Yii::t('app', "Hammasini tanlash"), ['class' => 'checkbox__text'] ) ?>
            <?= Html::endTag("label") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2 text-right noPadding"><b><?php echo Yii::t('app','Rejadagi o\'lchovlar')?> </b></div>
                    <div class="col-md-9 "><?=$sizes['list']?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 text-right noPadding"> <b><?php echo Yii::t('app','Umumiy miqdori')?> : </b></div>
                    <div class="col-md-7">
                        <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$sizes['all_count']?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>

<?= CustomTabularInput::widget([
    'id' => 'documentitems_id',
    'form' => $form,
    'models' => $models,
    'theme' => 'bs',
    'rowOptions' => [
        'id' => 'row{multiple_index_documentitems_id}',
        'data-row-index' => '{multiple_index_documentitems_id}'
    ],
    'max' => 100,
    'min' => 0,
    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
    'addButtonOptions' => [
        'class' => 'btn btn-success hidden',
    ],
    'cloneButton' => false,
    'columns' => [
        [
            'type' => 'hiddenInput',
            'name' => 'id',
        ],
        [
            'name' => 'name',
            'title' => Yii::t('app', 'Maxsulot nomi'),
            'options' => [
                'class' => 'name',
                'disabled' => true
            ],
            'headerOptions' => [
                'style' => 'width: 40%;',
            ]
        ],
        [
            'name' => 'count',
            'title' => Yii::t('app', 'Berilishi kerak(dona)'),
            'options' => [
                'class' => 'document_qty',
                'disabled' => true
            ],
        ],
        [
            'name' => 'quantity',
            'title' => Yii::t('app', 'Berilishi kerak (kg)'),
            'options' => [
                'class' => 'tabular-cell-mato roll-fact number',
                'disabled' => true
            ],
        ],
        [
            'name' => 'status',
            'title' => Yii::t('app', 'Holati'),
            'type' => 'checkbox',
            'value' => function ($model) {
                if ($model->status == 1) {
                    return 0;
                } else {
                    return 1;
                }
            },
            'options' => function ($model) use ($tayyor, $tayyor_emas) {
                if ($model->status == 1) {
                    return [
                        'label' => '<div class="checkbox__text">' . $tayyor_emas . '</div>',
                        'class' => 'tabular-cell-status checkbox_input',
                    ];
                } else {
                    return [
                        'label' => '<div class="checkbox__text">' . $tayyor . '</div>',
                        'class' => 'tabular-cell-status checkbox_input',
                    ];
                }
            },
        ],
    ]
]);
?>

    <div class="responsible-div row">
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="responsible-checkbox" <?=($count>0)?'checked':''?> class="checkbox_input" name="responsible_checkbox">
                    <div class="checkbox__text">
                        <b><?php echo Yii::t('app','Javobgarlikni zimmasiga olish')?></b>
                        <small>(<?php echo Yii::t('app',"Aksessuar tayyor bo'lmasa matoni bichuvga berib bo'lmaydi, agar aksessuarni o'z vaqtida yetkazib berishni o'z zimmangizga olsangiz matoni bichuvga bersa bo'ladi. Agar zimmangizga olsangiz izohga qo'shimcha ma'lumotlar yozishingiz mumkin")?>)</small>
                    </div>
                </label>
            </div>
        </div>
        <?php
        $required = ($count>0)?'customRequired':'';
        $display = ($count>0)?'block':'none';
        ?>
        <div class="col-md-6 parent-responsible" style="display: <?=$display?>;">
            <?= $form->field($responsible, 'users_id')->widget(Select2::className(),[
                'data' => \app\models\Users::getUserList(),
                'options' => [
                    'prompt' =>Yii::t('app','Tanlang'),
                    'class' => "{$required} responsible",
                    'disabled' => ($count>0)?false:true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6 parent-responsible" style="display: <?=$display?>;">
            <?= $form->field($responsible, 'add_info')->textarea(['class'=>"{$required} form-control responsible",'disabled' => ($count>0)?false:true])?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success submitButton']) ?>
    </div>

<?php ActiveForm::end();


$js = <<< JS
    $('table .checkbox_input').on('change',function(e) {
        if($(this).prop("checked") == true){
            $(this).parent().find('.checkbox__text').html('{$tayyor}');
        }else{
            $(this).parent().find('.checkbox__text').html('{$tayyor_emas}');
        }
        var numberNotChecked = $('table .checkbox_input:checkbox:not(":checked")').length;
        if(numberNotChecked==0){
            $('#responsible-checkbox').prop('checked',false);
            $('.responsible').attr('disabled',true).removeClass('customRequired').parents('.parent-responsible').hide();
        }else{
            $('#responsible-checkbox').prop('checked',true);
            $('.responsible').removeAttr('disabled').addClass('customRequired').parents('.parent-responsible').show();
        }
    });
    $('#responsible-checkbox').on('change',function(e) {
        if($(this).prop("checked") == true){
            $('.responsible').removeAttr('disabled').addClass('customRequired').parents('.parent-responsible').show();
        }else{
            $('.responsible').attr('disabled',true).removeClass('customRequired').parents('.parent-responsible').hide();
        }
    });
    $('.submitButton').on('click',function(e) {
        let required = $(".customRequired");
        $(required).each(function (index, value){
            if($(this).val()==0||$(this).val()==null){
                e.preventDefault();
                $(this).css("border-color","red").parents('.form-group').addClass('has-error');
                $(this).focus();
            }
        });
    });
    $("body").delegate(".customRequired","change",function(){
        if($(this).val()!==0){
            $(this).css("border-color","#d2d6de").parents('.form-group').removeClass('has-error');
        }
    });
    $("#check_all_checkbox").click(function() {
        if($(this).prop('checked')) {
            $("#documentitems_id tbody tr").each(function() {
                $(".list-cell__status input").prop('checked', true);
                $('.list-cell__status .checkbox__text').html('{$tayyor}');
            });
        } else {
            $("#documentitems_id tbody tr").each(function() {
                $(".list-cell__status input").prop('checked', false);
                $('.list-cell__status .checkbox__text').html('{$tayyor_emas}');
            });
        }
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
$css = <<< CSS
.checkbox > label input {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 20px;
}
.checkbox__text {
	position: relative;
	padding: 0 0 0 60px;
	cursor: pointer;
}
.checkbox__text:before {
	content: '';
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 26px;
	border-radius: 13px;
	background: #CDD1DA;
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
	transition: .2s;
}
.checkbox__text:after {
	content: '';
	position: absolute;
	top: -2px;
	left: 2px;
	width: 22px;
	height: 22px;
	border-radius: 10px;
	background: #FFF;
	box-shadow: 0 2px 5px rgba(0,0,0,.3);
	transition: .2s;
}
.checkbox > label input:checked + .checkbox__text:before {
	background: #9FD468;
}
.checkbox > label input:checked + .checkbox__text:after {
	left: 26px;
}
.checkbox > label input:focus + .checkbox__text:before {
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);
}
.list-cell__button{
    display: none;
}
CSS;
$this->registerCss($css);