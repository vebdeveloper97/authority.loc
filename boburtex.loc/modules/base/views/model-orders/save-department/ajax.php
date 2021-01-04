<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 28.11.19 19:01
 */

use app\modules\base\models\ModelOrdersPlanning;
use app\modules\base\models\MoiRelDept;
use app\modules\settings\models\CompanyCategories;
use app\modules\settings\models\Unit;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\base\models\Musteri;
use kartik\date\DatePicker;
use yii\helpers\Html;
?>
<table>
    <tbody id="table_new">
        <?php if($is_own==1){?>
            <tr id="row<?=$key?>" class="multiple-input-list__item" data-row-index="<?=$key?>">
                <td class="list-cell__company_categories_id]">
                    <div class="field-moireldept-1-<?=$key?>-company_categories_id] form-group">
                        <?=Html::dropDownList("MoiRelDept[{$key}][company_categories_id]",'',CompanyCategories::getList(),['prompt'=>Yii::t('app', 'Bo\'lim tanlang'),'class'=>'form-control customRequired company_categories_id'])?>
                    </div>
                </td>
                <td class="list-cell__toquv_departments_id">
                    <div class="field-moireldept-1-<?=$key?>-toquv_departments_id form-group">
                        <?=Html::dropDownList("MoiRelDept[{$key}][toquv_departments_id]",'',ToquvDepartments::getList(),['prompt'=>Yii::t('app', 'Departament tanlang'),'options'=>ToquvDepartments::getList('options'),'class'=>'form-control customRequired toquv_departments_id'])?>
                    </div>
                </td>
                <td class="list-cell__type">
                    <div class="field-moireldept-1-<?=$key?>-type form-group">
                        <?=Html::dropDownList("MoiRelDept[{$key}][type]",'',MoiRelDept::getTypeList(),['prompt'=>Yii::t('app', 'Tanlang'),'class'=>'form-control customRequired type',$disabled=>true,'encode'=>false])?>
                    </div>
                </td>
                <td class="list-cell__model_orders_planning_id">
                    <div class="field-moireldept-1-<?=$key?>-model_orders_planning_id form-group">
                        <?=Html::dropDownList("MoiRelDept[{$key}][model_orders_planning_id]",'',ModelOrdersPlanning::getList($id),['prompt'=>Yii::t('app', 'Tanlang'),'class'=>'form-control customRequired model_orders_planning_id','encode'=>false])?>
                    </div>
                </td>
                <td class="list-cell__quantity">
                    <div class="field-moireldept-1-<?=$key?>-quantity form-group">
                        <input type="text" id="moireldept-1-<?=$key?>-quantity" class="form-control customRequired" name="MoiRelDept[<?=$key?>][quantity]" tabindex="1">
                    </div>
                </td>
                <td class="list-cell__unit_id">
                    <div class="field-moireldept-1-<?=$key?>-unit_id form-group">
                        <?=Html::dropDownList("MoiRelDept[{$key}][unit_id]",2,Unit::getUnitList(),['class'=>'form-control'])?>
                    </div>
                </td>
                <td class="list-cell__start_date">
                    <div class="field-moireldept-1-<?=$key?>-start_date form-group">
                        <?php
                        echo DatePicker::widget([
                            'name' => "MoiRelDept[{$key}][start_date]",
                            'options' => [
                                'placeholder' => Yii::t('app','Sana'),
                                'class' => 'start_date customRequired'
                            ],
                            'language' => 'ru',
                            'id' => "moireldept-1-{$key}-start_date",
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
                    <div class="field-moireldept-1-<?=$key?>-end_date form-group">
                        <?php
                        echo DatePicker::widget([
                            'name' => "MoiRelDept[{$key}][end_date]",
                            'options' => [
                                'placeholder' => Yii::t('app','Sana'),
                                'class' => 'end_date customRequired'
                            ],
                            'language' => 'ru',
                            'id' => "moireldept-1-{$key}-end_date",
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
                    <div class="field-moireldept-1-<?=$key?>-add_info form-group">
                        <textarea id="moireldept-1-<?=$key?>-add_info" class="form-control" name="MoiRelDept[<?=$key?>][add_info]" tabindex="1"></textarea>
                    </div>
                </td>

                <td class="list-cell__button">
                    <div class="field-moireldept-1-<?=$key?>-add_info form-group">
                        <div class="field-moireldept-1-<?=$key?>-remove form-group">
                            <span id="moireldept-1-<?=$key?>-remove">
                                <button type="button" class="multiple-input-list__btn js-input-remove btn btn-danger">
                                    <i class="fa fa-close"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        <?php }else{?>
            <tr id="row<?=$key?>" class="multiple-input-list__item" data-row-index="<?=$key?>">
                <td class="list-cell__company_categories_id]">
                    <div class="field-moireldept-2-<?=$key?>-company_categories_id] form-group">
                        <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][company_categories_id]",'',CompanyCategories::getList(),['prompt'=>Yii::t('app', 'Bo\'lim tanlang'),'class'=>'form-control customRequired company_categories_id'])?>
                    </div>
                </td>
                <td class="list-cell__musteri_id">
                    <div class="field-moireldept-2-<?=$key?>-musteri_id form-group">
                        <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][musteri_id]",'',Musteri::getList(),['prompt'=>Yii::t('app', 'Kontragent tanlang'),'class'=>'form-control customRequired musteri_id'])?>
                    </div>
                </td>
                <td class="list-cell__type">
                    <div class="field-moireldept-2-<?=$key?>-type form-group">
                        <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][type]",'',MoiRelDept::getTypeList(),['prompt'=>Yii::t('app', 'Tanlang'),'class'=>'form-control customRequired type','encode'=>false])?>
                    </div>
                </td>
                <td class="list-cell__model_orders_planning_id">
                    <div class="field-moireldept-2-<?=$key?>-model_orders_planning_id form-group">
                        <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][model_orders_planning_id]",'',ModelOrdersPlanning::getList($id),['prompt'=>Yii::t('app', 'Tanlang'),'class'=>'form-control customRequired model_orders_planning_id','encode'=>false])?>
                    </div>
                </td>
                <td class="list-cell__quantity">
                    <div class="field-moireldept-2-<?=$key?>-quantity form-group">
                        <input type="text" id="moireldept-2-<?=$key?>-quantity" class="form-control customRequired" name="MoiRelDeptMusteri[<?=$key?>][quantity]" tabindex="1">
                    </div>
                </td>
                <td class="list-cell__unit_id">
                    <div class="field-moireldept-2-<?=$key?>-unit_id form-group">
                        <?=Html::dropDownList("MoiRelDeptMusteri[{$key}][unit_id]",2,Unit::getUnitList(),['class'=>'form-control'])?>
                    </div>
                </td>
                <td class="list-cell__start_date">
                    <div class="field-moireldept-2-<?=$key?>-start_date form-group">
                        <?php
                        echo DatePicker::widget([
                            'name' => "MoiRelDeptMusteri[{$key}][start_date]",
                            'options' => [
                                'placeholder' => Yii::t('app','Sana'),
                                'class' => 'start_date customRequired'
                            ],
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
                                'class' => 'end_date customRequired'
                            ],
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
                        <textarea id="moireldept-2-<?=$key?>-add_info" class="form-control" name="MoiRelDeptMusteri[<?=$key?>][add_info]" tabindex="1"></textarea>
                    </div>
                </td>

                <td class="list-cell__button">
                    <div class="field-moireldept-2-<?=$key?>-add_info form-group">
                        <div class="field-moireldept-2-<?=$key?>-remove form-group">
                        <span id="moireldept-2-<?=$key?>-remove">
                            <button type="button" class="multiple-input-list__btn js-input-remove btn btn-danger">
                                <i class="fa fa-close"></i>
                            </button>
                        </span>
                        </div>
                    </div>
                </td>
            </tr>
        <?php }?>
    </tbody>
</table>