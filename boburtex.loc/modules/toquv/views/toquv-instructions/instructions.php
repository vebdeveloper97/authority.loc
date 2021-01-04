<?php

use app\modules\toquv\models\ToquvDepartments;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvInstructions */
/* @var $models app\modules\toquv\models\ToquvInstructionItems */
/* @var $order app\modules\toquv\models\ToquvOrders */
/* @var $orderId app\modules\toquv\models\ToquvOrders */

$this->title = Yii::t('app',"Ko'rsatma berish");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-instructions-view">
    <h4><?= Yii::t('app',"Olingan buyurtmaga asosan ishlab chiqarishga ko'rsatma berish sahifasi"); ?></h4>
    <?php if($action == "create"):?>
        <div class="toquv-instructions-form" style="border: 1px solid #ccc;margin-top: 15px; padding: 5px;">
        <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_IP_SKLAD'])->asArray()->one(); ?>
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-4">
                <?php $model->reg_date = date('d.m.Y');?>
                <?= $form->field($model, 'reg_date')->widget(DatePicker::className(),[
                    'options' => ['placeholder' => Yii::t('app','Sana')],
                    'language' => 'ru',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]) ?>
                <?= $form->field($model, 'toquv_order_id')->hiddenInput(['value' => $orderId])->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'from_department')->hiddenInput(['value' => $dept['id']])->label(false) ?>
                <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_MATO_SEH'])->asArray()->one(); ?>
                <?= $form->field($model, 'to_department')->dropDownList([$dept['id'] => $dept['name']])?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'priority')->dropDownList($model->getPriorityList()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'responsible_persons')->textarea(['rows' => 2]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <div class="instruction-items-box" style="margin-top: 15px;">
            <table class="table table-bordered table-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?= Yii::t('app','Buyurtmachi')?></th>
                        <th scope="col"><?= Yii::t('app','Ip egasi')?></th>
                        <th scope="col"><?= Yii::t('app','Mato nomi va miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Ip nomi va miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Ip nomi')?></th>
                        <th scope="col"><?= Yii::t('app','Ip miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Izoh')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count = 1;
                        $url = Url::to(['get-belong-to-thread']);
                    ?>
                    <?php foreach ($items as $key => $item):?>
                        <?php
                        if($item['own_qty'] > 0){
                            $isOwn = 1;
                            $Qty = $item['own_qty'];
                            $isOwnLabel = Yii::t('app','O\'zimizniki');
                        }else{
                            $isOwn = 2;
                            $Qty = $item['their_qty'];
                            $isOwnLabel = Yii::t('app','Mijozniki');
                        }
                        ?>
                        <tr>
                            <?= Html::hiddenInput("Items[{$key}][quantity]", $Qty);?>
                            <?= Html::hiddenInput("Items[{$key}][thread_name]", null, ['id' => "instructionItemText{$key}"]);?>
                            <?= Html::hiddenInput("Items[{$key}][is_own]", $isOwn);?>
                            <td><?= $count; ?></td>
                            <td><?= $item['ca']; ?></td>
                            <td><?= $isOwnLabel; ?></td>
                            <td><?= $item['mato']." - ". $item['qty']." kg" ?></td>
                            <td><?= $item['nename']."-".$item['thrname']." - ".$item['own_qty']."kg" ?></td>
                            <td style="width: 350px;">
                                <?= Select2::widget([
                                    'name' => "Items[{$key}][entity_id]",
                                    'data' => [],
                                    'options' => [
                                        'placeholder' => 'Ip nomini qidirish ...',
                                        'multiple' => false,
                                        'data-ne' => $item['neid'],
                                        'data-thread' => $item['ttid']
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => $url,
                                            'dataType' => 'json',
                                            'data' => new JsExpression("function(params) { 
                                                return {
                                                        q:params.term, 
                                                        ne:{$item['neid']},
                                                        thr:{$item['ttid']},
                                                        isOwn:{$isOwn},
                                                        mid :{$item['mid']}
                                                       }; 
                                            }")
                                        ],
                                        'escapeMarkup' => new JsExpression('function (markup) { 
                                                    return markup; 
                                                }'),
                                        'templateResult' => new JsExpression('function(ip) { return ip.text; }'),
                                        'templateSelection' => new JsExpression("function (ip) { 
                                                                                if(ip.id){
                                                                                    $('#instructionItemText{$key}').val(ip.text);
                                                                                }
                                                                                return ip.text;
                                                                         }"),
                                    ],
                                    'pluginEvents' => [

                                    ]
                                ])?>
                            </td>
                            <td>

                                <?= Html::input('text', "Items[{$key}][fact]", $Qty, ['class' => 'form-control','type' =>'number']);?>
                            </td>
                            <td>
                                <?= Html::textarea("Items[{$key}][add_info]")?>
                            </td>
                        </tr>
                    <?php $count++; ?>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <?php
        $this->registerJs("
            $('.quantityMoving').on('keyup', function(e){
                let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                let currentValue = $(this).val();
                if(parseFloat(currentValue) > parseFloat(remainQty)){
                    $(this).val(parseFloat(remainQty));
                }
            });");
        ?>
        <?php
        if(!$model->isNewRecord){
            $this->registerJs("
                 $('#documentitems_id').on('afterInit', function (e, index) {
                       let row = $(this).find('tbody tr');
                       if(row.length){
                            row.each(function(key, val){
                                let select = $(val).find('.list-cell__entity_id select option:selected').attr('data-sum');
                                $(val).find('.list-cell__remain input').val(select);                    
                            });
                       }
                 });
            ");
        }
        ?>
    </div>
    <?php endif;?>
</div>
