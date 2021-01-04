<?php

use app\modules\base\models\ModelOrdersPlanning;
use app\modules\base\models\MoiRelDept;
use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\settings\models\CompanyCategories;
use app\modules\settings\models\Unit;
use app\modules\base\models\Musteri;
/* @var $this \yii\web\View */
/* @var $models_musteri \app\modules\base\models\MoiRelDept */
/* @var $form \yii\widgets\ActiveForm|static */
\unclead\multipleinput\assets\MultipleInputAsset::register($this);
?>
    <div id="own_id" class="multiple-input">
        <table class="multiple-input-list table table-condensed table-renderer">
            <thead>
            <tr>
                <th class="list-cell__toquv_departments_id">Bo'lim</th>
                <th class="list-cell__toquv_departments_id">Bo'lim</th>
                <th class="list-cell__quantity"><?php echo Yii::t('app','Type')?></th>
                <th class="list-cell__quantity"><?php echo Yii::t('app','Mato')?></th>
                <th class="list-cell__quantity">Miqdori</th>
                <th class="list-cell__unit_id">O'lchov birligi</th>
                <th class="list-cell__start_date" width="110px">Boshlash sanasi</th>
                <th class="list-cell__end_date" width="110px">Tayyor bo'lish sanasi</th>
                <th class="list-cell__add_info">Izoh</th>
                <th class="list-cell__button" style="width: 70px;">
                    <div class="multiple-input-list__btn js-input-plus btn btn-success" is_own="2" num="<?=$id?>"><i class="glyphicon glyphicon-plus"></i></div>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if($models_musteri){ foreach ($models_musteri as $key => $item) {
                $unit = ($item['unit_id'])?$item['unit_id']:2;
                $disabled = ($item['status']==3)?'disabled':'new';
                ?>
                <tr id="row<?=$key?>_2" class="multiple-input-list__item" data-row-index="<?=$key?>">
                    <td class="list-cell__company_categories_id]">
                        <div class="field-moireldept-2-<?=$key?>-company_categories_id] form-group">
                            <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][company_categories_id]",$item['company_categories_id'],CompanyCategories::getList(),['prompt'=>Yii::t('app', 'Bo\'lim tanlang'),'class'=>'form-control company_categories_id',$disabled=>true])?>
                        </div>
                    </td>
                    <td class="list-cell__musteri_id">
                        <div class="field-moireldept-2-<?=$key?>-musteri_id form-group">
                            <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][musteri_id]",$item['musteri_id'],Musteri::getList(),['prompt'=>Yii::t('app', 'Kontragent tanlang'),'class'=>'form-control musteri_id',$disabled=>true])?>
                        </div>
                    </td>
                    <td class="list-cell__type">
                        <div class="field-moireldept-2-<?=$key?>-type form-group">
                            <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][type]",$item['type'],MoiRelDept::getTypeList(),['prompt'=>Yii::t('app', 'Tanlang'),'class'=>'form-control customRequired type',$disabled=>true,'encode'=>false])?>
                        </div>
                    </td>
                    <td class="list-cell__model_orders_planning_id">
                        <div class="field-moireldept-2-<?=$key?>-model_orders_planning_id form-group">
                            <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][model_orders_planning_id]",$item['model_orders_planning_id'],ModelOrdersPlanning::getList($id),['prompt'=>Yii::t('app', 'Tanlang'),'class'=>'form-control customRequired model_orders_planning_id',$disabled=>true,'encode'=>false])?>
                        </div>
                    </td>
                    <td class="list-cell__quantity">
                        <div class="field-moireldept-2-<?=$key?>-quantity form-group">
                            <input type="text" id="moireldept-2-<?=$key?>-quantity" class="form-control" name="MoiRelDeptMusteri[<?=$key?>][quantity]" <?=$disabled?>='' value="<?=$item['quantity']?>">
                        </div>
                    </td>
                    <td class="list-cell__unit_id">
                        <div class="field-moireldept-2-<?=$key?>-unit_id form-group">
                            <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][unit_id]",$unit,Unit::getUnitList(),['class'=>'form-control',$disabled=>true])?>
                        </div>
                    </td>
                    <td class="list-cell__start_date">
                        <div class="field-moireldept-2-<?=$key?>-start_date form-group">
                            <?php
                            echo DatePicker::widget([
                                'name' => "MoiRelDeptMusteri[{$key}][start_date]",
                                'options' => [
                                    'placeholder' => Yii::t('app','Sana'),
                                    'class' => 'start_date',
                                    $disabled=>true
                                ],
                                'value' => $item['start_date'],
                                'language' => 'ru',
                                'id' => "moireldept-2-{$key}-start_date",
                                'pickerButton' => false,
                                'layout' => "{input}<span class='input-group-addon kv-date-remove'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                                'pluginOptions' => [
                                    'format' => 'dd.mm.yyyy',
                                    'autoclose' => true,
                                    'showRemove' =>true,
                                    'startDate' => "0d",
                                    'todayHighlight' => true,
                                ],
                            ]);
                            ?>
                        </div>
                    </td>
                    <td class="list-cell__end_date">
                        <div class="field-moireldept-2-<?=$key?>-end_date form-group">
                            <?php
                            echo DatePicker::widget([
                                'name' => "MoiRelDeptMusteri[{$key}][end_date]",
                                'options' => [
                                    'placeholder' => Yii::t('app','Sana'),
                                    'class' => 'end_date',
                                    $disabled=>true
                                ],
                                'value' => $item['end_date'],
                                'language' => 'ru',
                                'id' => "moireldept-2-{$key}-end_date",
                                'pickerButton' => false,
                                'layout' => "{input}<span class='input-group-addon kv-date-remove'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                                'pluginOptions' => [
                                    'format' => 'dd.mm.yyyy',
                                    'autoclose' => true,
                                    'showRemove' =>true,
                                    'startDate' => "0d",
                                    'todayHighlight' => true,
                                ],
                            ]);
                            ?>
                        </div>
                    </td>
                    <td class="list-cell__add_info">
                        <div class="field-moireldept-2-<?=$key?>-add_info form-group">
                            <textarea id="moireldept-2-<?=$key?>-add_info" class="form-control" name="MoiRelDeptMusteri[<?=$key?>][add_info]" <?=$disabled?>=''><?=$item['add_info']?></textarea>
                        </div>
                    </td>

                    <td class="list-cell__button">
                        <?php if($item->status<3){?>
                            <div class="field-moireldept-2-<?=$key?>-add_info form-group">
                                <div class="field-moireldept-2-<?=$key?>-remove form-group">
                                    <span id="moireldept-2-<?=$key?>-remove">
                                        <button type="button" class="js-input-remove btn btn-danger">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        <?php }?>
                    </td>
                </tr>
            <?php }}?>
            </tbody>
        </table>
    </div>
<?php
$css = <<< CSS
.kv-date-remove{
    font-size:11px;padding: 0!important;background-color: #ccc !important;color: #000!important
}
CSS;
$this->registerCss($css);
