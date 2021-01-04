<?php
/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocuments */
/* @var $kalite array|false */
/* @var $kalite_all array|false */
/* @var $to_employe array|false */
/* @var $brak string|null */

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_ACS])->label(false) ?>
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
    <h3 style="padding-bottom: 5px;">
        <span>
            <?=Yii::t('app', 'Jo\'natilayotgan miqdor')?> :
        </span>
        <b>
            <span id="send_kalite">0</span> kg
        </b>
    </h3>
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
                            <?=Yii::t('app',"Tayyor bo'lgan soni")?>
                        </td>
                        <th>
                            <?=$kalite['count']?>
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
                            <?=Yii::t('app', 'Uzunligi')." - ".Yii::t('app', "Eni").' - '.Yii::t('app', 'Qavati')?>
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
                            <?=date('d.m.Y H:i',$m['created_at'])?>
                        </span>
                        <span>
                            <?php echo Yii::t('app','Miqdori')?> : <b><?=$m['summa']?></b> kg
                        </span>
                        <span>
                            <?php echo Yii::t('app','Soni')?> : <b><?=$m['count']?></b>
                        </span>
                        <span>
                            <?php echo Yii::t('app','Rulon soni')?> : <b><?=$m['roll']?></b>
                        </span>
                        <span>
                            <?php echo Yii::t('app','To\'quvchi')?> : <b><?=$m['user_fio']?></b>
                        </span>
                        <span>
                            <?php echo Yii::t('app','Sort Name ID')?> : <b><?=$m['sort']?></b>
                        </span>
                        <input type="hidden" class="quantity" value="<?=$m['summa']?>" name="Items[<?=$n?>][quantity]">
                        <input type="hidden" class="count" value="<?=$m['count']?>" name="Items[<?=$n?>][count]">
                        <input type="hidden" class="roll" value="<?=$m['roll']?>" name="Items[<?=$n?>][roll]">
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
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id'=>'saveButton']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
    var isFakeChange = true;
    if(isFakeChange){
        $('#{$toDepId}').on('change', function(e){
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
    
    $('.quantityMoving').on('keyup', function(e){
        let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
        let currentValue = $(this).val();
        if(parseFloat(currentValue) > parseFloat(remainQty)){
            $(this).val(parseFloat(remainQty));
        }
    });
    $('#saveButton').on('click',function(e){
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
CSS;
$this->registerCss($css);
?>
<?php \app\widgets\helpers\Script::begin()?>
<script>
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
                $send_kalite.html(1*$send_kalite.text()+1*$quantity);
            } else {
                $widget.removeClass(style + color + ' active');
                $send_kalite.html(1*$send_kalite.text()-1*$quantity);
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
</script>
<?php \app\widgets\helpers\Script::end()?>
