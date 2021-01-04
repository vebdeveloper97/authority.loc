<?php
/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocuments */
/* @var $searchModel \app\modules\toquv\models\MatoSearch */
/* @var $kalite array|false */
/* @var $kalite_all array|false */
/* @var $to_employe array|false */
/* @var $brak string|null */

use kartik\date\DatePicker;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = Yii::t('app', "Matolarni omborga jo'natish");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyorlangan matolar'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['id' => 'toquv-kalite_send']); ?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search-save', [
                    'model' => $searchModel,
                    'kalite' => $kalite,
                ]),
                'contentOptions' => ['class' => 'out']
            ]
        ]
    ]);
    ?>
</div>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_MATO])->label(false) ?>
        <?= $form->field($model, 'musteri_id')->hiddenInput(['value' => $kalite['musteri']])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t('app','Sana')],
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'add_info')->textarea(['rows'=>1])->label(Yii::t('app','Asos')); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_department')->dropDownList($model->getDepartments(true),['disabled'=>true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->dropDownList($model->getDepartments(true),['disabled'=>true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->dropDownList($model->getEmployees()) ?>

    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_employee')->dropDownList(($to_employe)?$to_employe:$model->getEmployees()) ?>
    </div>
</div>
<input id="musteriFake" name="musteriFake" type="hidden">
<?php
$urlRemain = Url::to(['ajax-request-mato' ,'slug' => 'kochirish_mato']);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$url = Url::to(['/toquv/toquv-documents/get-department-user', 'slug' => 'kochirish_mato']);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
?>
<div class="document-items">
    <input type="hidden" name="ToquvDocumentItems[tib_id]" value="<?=$kalite['tir_id']?>">
    <input type="hidden" name="ToquvDocumentItems[entity_id]" value="<?=$kalite['tir_id']?>">
    <input type="hidden" name="ToquvDocumentItems[toquv_orders_id]" value="<?=$kalite['toquv_orders_id']?>">
    <input type="hidden" name="ToquvDocumentItems[toquv_rm_order_id]" value="<?=$kalite['toquv_rm_order_id']?>">
    <div class="row" style="padding-bottom: 5px;">
        <div class="col-md-4">
            <span>
                <?=Yii::t('app', 'Jo\'natilayotgan miqdor')?> :
            </span>
            <b>
                <span id="send_kalite">0</span> kg
            </b>
        </div>
        <div class="col-md-4">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id'=>'saveButton']) ?>
        </div>
        <div class="col-md-4" style="">
            <label>
                <div class="col-md-3 noPadding">
                    <input type="checkbox" class="option-input1 hidden" id="select_checked" checked>
                </div>
                <div class="col-md-9 noPadding">
                    <span id="status_checked" class="btn btn-danger">
                        Barchasini bekor qilish
                    </span>
                </div>
            </label>
        </div>
    </div>
    <div class="row">
        <?php if($kalite){?>
            <div class="col-md-4">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <?=Yii::t('app','Buyurtmachi')?>
                        </td>
                        <th>
                            <?=$kalite['musteri_id']?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?=Yii::t('app','Buyurtma')?>
                        </td>
                        <th>
                            <?=$kalite['doc_number']?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?=Yii::t('app','Mato nomi')?>
                        </td>
                        <th>
                            <?=$kalite['mato']?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?=Yii::t('app','Buyurtma miqdori')?>
                        </td>
                        <th>
                            <?=$kalite['quantity']?> kg
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?=(!$brak || $brak != 'BRAK')?Yii::t('app','Tayyor bo\'lgan miqdori'):Yii::t('app','Brak mato miqdori')?>
                        </td>
                        <th>
                            <?=$kalite['summa']?> kg
                        </th>
                    </tr>
                    <?php if (!$brak || $brak != 'BRAK'){?>
                    <tr>
                        <td>
                            <?=Yii::t('app','Tayyorlanishi kerak bo\'lgan miqdor')?>
                        </td>
                        <th>
                            <?php $remain = $kalite['quantity'] - $kalite['summa']?>
                            <?=($remain>0)?$remain.' kg':Yii::t('app', 'Buyurtma bajarildi');?>
                        </th>
                    </tr>
                    <?php }?>
                    <tr>
                        <td>
                            <?=Yii::t('app','Pus/Fine')?>
                        </td>
                        <th>
                            <?=$kalite['pus_fine']?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?=Yii::t('app', 'Thread Length')." - ".Yii::t('app', 'Finish En').' - '.Yii::t('app', 'Finish Gramaj')?>
                        </td>
                        <th>
                            <?=$kalite['info']?>
                        </th>
                    </tr>
                </table>
            </div>
        <?php }?>
        <div class="col-md-8 col-xs-12">
            <div class="well">
                <ul class="list-group checked-list-box">
                    <?php foreach ($kalite_all as $n => $m){?>
                    <li class="list-group-item" data-checked="true" <?php if($m['sort_id']==2){?>style="border: 5px solid red;"<?php }?>>
                        <span>
                            <span class="state-icon glyphicon glyphicon-unchecked"></span>
                            <b><?=$m['code']?></b> <small><?=date('H:i',$m['created_at'])?></small>
                        </span>
                        <span>
                            <?php echo Yii::t('app','Miqdori')?> : <b><?=$m['summa']?></b> kg
                        </span>
                        <span>
                            <?php echo Yii::t('app','T-chi')?> : <b><?=$m['user_fio']?> T-<?=$m['tabel']?></b>
                        </span>
                        <span>
                            <?php echo Yii::t('app','Mashina')?> : <b><?=$m['makine']?></b>
                        </span>
                        <span>
                            <?php echo Yii::t('app','Sort Name ID')?> : <b><?=$m['sort']?></b>
                        </span>
                        <input type="hidden" class="quantity" value="<?=$m['summa']?>" name="Items[<?=$n?>][quantity]">
                        <input type="hidden" value="<?=$m['id']?>" name="Items[<?=$n?>][id]">
                        <input type="hidden" value="<?=$m['sort_id']?>" name="Items[<?=$n?>][sort_id]">
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="hiddenItemBalanceIdBox">

</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<?php
$this->registerJs("
    var isFakeChange = true;
    if(isFakeChange){
        $('body').delegate('#{$toDepId}','change', function(e){
            var id = $(this).find('option:selected').val();
            $.ajax({
                url: '{$url}?id='+id,
                success: function(response){
                    if(response.status == 1){
                    var option = new Option(response.name, response.id);
                       $('#{$toEmp}').find('option').remove().end().append(option).val(response.id);
                    }
                }
            });
        });
    }
    
    $('body').delegate('.quantityMoving','keyup', function(e){
        let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
        let currentValue = $(this).val();
        if(parseFloat(currentValue) > parseFloat(remainQty)){
            $(this).val(parseFloat(remainQty));
        }
    });
    $('body').delegate('#saveButton','click',function(e){
        if($('#send_kalite').text()<=0){
            e.preventDefault();
        }
    });
");
$color = (!$brak)?"rgb(66, 139, 202)":"rgb(137, 9, 9)";
$css = <<< CSS
.document-items{
    padding-top: 10px;
}
.state-icon {
    left: -5px;
}
.list-group-item-primary {
    color: rgb(255, 255, 255);
    background-color: {$color}!important;
}
.list-group-item{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-right: 5px;
    margin-bottom: 5px;
    width: 255px;
}
.well .list-group {
    margin-bottom: 0px;
    display: flex;
    flex-direction: row; 
    flex-wrap: wrap;
    align-content: center;
    justify-content: left;
}
.list-group-item:last-child {
    margin-bottom: 5px;
}
@keyframes click-wave {
  0% {
    height: 40px;
    width: 40px;
    opacity: 0.35;
    position: relative;
  }
  100% {
    height: 200px;
    width: 200px;
    margin-left: -80px;
    margin-top: -80px;
    opacity: 0;
  }
}

.option-input {
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  -o-appearance: none;
  appearance: none;
  position: relative;
  top: 1px;
  right: 0;
  bottom: 0;
  left: -2px;;
  height: 40px;
  width: 40px;
  transition: all 0.15s ease-out 0s;
  background: #cbd1d8;
  border: none;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  margin-right: 0.5rem;
  outline: none;
  z-index: 1000;
}
.option-input:hover {
  background: #9faab7;
}
.option-input:checked {
  background: #40e0d0;
}
.option-input:checked::before {
  height: 40px;
  width: 40px;
  position: absolute;
  content: '✔';
  display: inline-block;
  font-size: 26.66667px;
  text-align: center;
  line-height: 40px;
}
.option-input:checked::after {
  -webkit-animation: click-wave 0.65s;
  -moz-animation: click-wave 0.65s;
  animation: click-wave 0.65s;
  background: #40e0d0;
  content: '';
  display: block;
  position: relative;
  z-index: 100;
}
.option-input.radio {
  border-radius: 50%;
}
.option-input.radio::after {
  border-radius: 50%;
}
.radio_div label {
  display: flex; 
  float: left; 
  margin-right: 10px; 
  align-content: center; 
  align-items: center; 
  font-size: 25px; 
  justify-content: center;
}
CSS;
$this->registerCss($css);
?>
<?php \app\widgets\helpers\Script::begin()?>
<script>
    $('body').delegate('#select_checked','click',function () {
        $('.list-group.checked-list-box .list-group-item').click();
        if($(this).is(':checked')){
            $('#status_checked').html("Barchasini bekor qilish").addClass('btn-danger').removeClass('btn-success');
        }else{
            $('#status_checked').html("Barchasini tanlash").addClass('btn-success').removeClass('btn-danger');
        }
    });
    $.fn.clickCheckbox = function() {
        $('.list-group.checked-list-box .list-group-item').each(function () {
            // Settings
            var $widget = $(this),
                $checkbox = $('<input type="checkbox" class="hidden" />'),
                $input = $(this).find('input[type="hidden"]'),
                $send_kalite = $('#send_kalite'),
                $quantity = $(this).find('.quantity').val(),
                color = ($widget.data('color') ? $widget.data('color') : "primary"),
                style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };

            $widget.css('cursor', 'pointer')
            $widget.append($checkbox);

            // Event Handlers
            $widget.on('click', function () {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                $input.prop('disabled', !$input.is(':disabled'));
                $input.triggerHandler('change');
                updateDisplay();
            });
            // $checkbox.on('change', function () {
            //     updateDisplay();
            // });
            // Actions
            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');

                // Set the button's state
                $widget.data('state', (isChecked) ? "on" : "off");

                // Set the button's icon
                $widget.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$widget.data('state')].icon);

                // Update the button's color
                if (isChecked) {
                    $widget.addClass(style + color + ' active');
                    $send_kalite.html((1 * $send_kalite.text() + 1 * $quantity).toFixed(2));
                } else {
                    $widget.removeClass(style + color + ' active');
                    $send_kalite.html((1 * $send_kalite.text() - 1 * $quantity).toFixed(2));
                }
            }

            // Initialization
            function init() {

                if ($widget.data('checked') == true) {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                }

                updateDisplay();

                // Inject the icon if applicable
                if ($widget.find('.state-icon').length == 0) {
                    $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
                }
            }

            init();
        });
    }
    $.fn.clickCheckbox();
    $("#toquv-kalite_send").on("pjax:end", function() {
        $.fn.clickCheckbox();
    });
</script>
<?php \app\widgets\helpers\Script::end()?>
