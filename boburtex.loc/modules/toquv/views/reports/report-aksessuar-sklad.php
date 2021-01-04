<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 17.12.19 13:37
 */

/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $items  */
/* @var $data array */

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax; ?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_mato', [
                        'model' => $model,
                    'data' => $data,
                    'from_model' => null,
                    'entity_type' => $entity_type,
                    'url' => Url::to(['report-aksessuar-sklad']),
                    'search_mato' => null,
                ]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
</div>
<div class="no-print pull-right">
    <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
</div>
<?php Pjax::begin(['id' => 'reportResultIncoming'])?>
<div class="report-ip-title">
    <h3 class="text-center" style="padding-bottom: 25px;">
        <?= Yii::t('app', "{name}: {from} - {to} sana oralig'idagi aksessuar holati", ['from' => $data['from_date'], 'to' => $data['to_date'], 'name' => $data['name']]) ?>
    </h3>
</div>
<div id="print-barcode">
    <form id="form_table" action="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/kochirish_aksessuar/create')?>" method="POST">
        <table class="table table-bordered report-table">
            <thead>
            <tr>
                <?php if($model->group_by_ip){?>
                <th>
                    <label class="checkbox-transform">
                        <input type="checkbox" id="checked_all" class="checkbox__input">
                        <span class="checkbox__label"></span>
                    </label>
                </th>
                <?php }?>
                <th>№</th>
                <th><?= Yii::t('app', 'Aksessuar egasi') ?></th>
                <th><?= Yii::t('app', 'Aksessuar Nomi') ?></th>
                <th scope="col"><?= Yii::t('app', 'Uzunligi') ?> - <?= Yii::t('app', "Eni") ?> - <?= Yii::t('app', 'Qavati') ?></th>
                <th><?= Yii::t('app', 'Pus/Fine') ?></th>
                <th><?= Yii::t('app', 'Qoldiq (kg)') ?></th>
                <th><?= Yii::t('app', 'Qoldiq (dona)') ?></th>
                <th><?= Yii::t('app', 'Qoldiq (rulon)') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $totalQty = 0;
            $totalRoll = 0;
            $totalSoni = 0;

            if (!empty($items)) {
                foreach ($items as $item):?>
                    <?php
                    $totalQty += $item['qoldiq'];
                    $totalRoll += $item['roll'];
                    $totalSoni += $item['soni'];
                    $bgStyle = "background-color: inherit";
                    if (!empty($item['qoldiq']) && $item['qoldiq'] > 0) {
                        $bgStyle = 'background-color: #f1f1f1;';
                    }
                    ?>
                    <tr style="<?= $bgStyle; ?>">
                        <?php if($model->group_by_ip){?>
                        <td>
                            <label class="checkbox-transform">
                                <input type="checkbox" class="checkbox__input checked_input" name="tib_id[]" value="<?=$item['item_id']?>">
                                <span class="checkbox__label"></span>
                            </label>
                        </td>
                        <?php }?>
                        <td><?= $count ?></td>
                        <td><?= $item['musteri'] ?></td>
                        <td><?= $item['mato_color']?> <b><?= $item['mato']?></b></td>
                        <td><?= $item['info']?></td>
                        <td><?= $item['pus_fine']?></td>
                        <td><b><?= number_format($item['qoldiq'], 2, '.', ' ') ?></b></td>
                        <td><b><?= $item['soni'] ?></b></td>
                        <td><b><?= number_format($item['roll'], 0, '.', ' ') ?></b></td>
                    </tr>
                    <?php
                    $count++;
                endforeach;
            } else { ?>
                <tr>
                    <td class="text-danger" colspan="7">
                        <?= Yii::t('app', 'Ma\'lumot mavjud emas') ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
                <th></th>
                <?php if($model->group_by_ip){?>
                    <th></th>
                <?php }?>
                <th></th>
                <th><?= number_format($totalQty, 3, '.', ' '); ?></th>
                <th><?= $totalSoni; ?></th>
                <th><?= $totalRoll; ?></th>
            </tr>
            </tfoot>
        </table>
        <?php if($model->group_by_ip && (Yii::$app->user->can('toquv-documents/chiqim_aksessuar/index') || Yii::$app->user->can('toquv-documents/kochirish_aksessuar/index') || Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_aksessuar/index'))){ ?>
            <div class="input-group" style="width: 200px">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <input type="hidden" id="report_department_id" name="department_id" value="<?=Yii::$app->request->get('ToquvDocumentBalanceSearch')['department_id'] ?? \app\modules\toquv\models\ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SKLAD'])['id']?>">
                <select id="select_url" class="form-control customHeight">
                    <?php if(Yii::$app->user->can('toquv-documents/kochirish_aksessuar/create')){?>
                        <option value="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/kochirish_aksessuar/create')?>"><?php echo Yii::t('app',"Ko'chirish")?></option>
                    <?php } if(Yii::$app->user->can('toquv-documents/chiqim_aksessuar/create')){?>
                        <option value="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/chiqim_aksessuar/create')?>"><?php echo Yii::t('app',"Chiqim")?></option>
                    <?php } if(Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_aksessuar/create')){?>
                        <option value="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/hisobdan_chiqarish_aksessuar/create')?>"><?php echo Yii::t('app',"Hisobdan chiqarish")?></option>
                    <?php }?>
                </select>
                <span class="input-group-btn">
                <button type="submit" class="btn btn-success customHeight">Ok</button>
            </span>
            </div>
        <?php }?>
    </form>
</div>
<?php Pjax::end()?>
<?php
$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/bichuv-acs-barcode.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$js = <<< JS
    $("table").tableExport({
        headers: true,
        footers: true,
        formats: ["xlsx", "csv", "xls"],
        filename: 'excel-table',
        bootstrap: true,
        exportButtons: true,
        position: "top",
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: "id",
        defaultFileName: "myDodwdwdwnload"
    });
    $("body").delegate("#checked_all","click",function() {
        let input = $(".checked_input");
        if($(this).prop("checked") == true){
            input.prop("checked",true);
        }else{
            input.prop("checked",false);
        }
    });
    $("body").delegate(".report-table tbody tr","click",function() {
        let _this = $(this);
        let input = $(_this).find(".checked_input");
        if(input.prop("checked") == false){
            input.prop("checked",true);
        }else{
            input.prop("checked",false);
        }
    });
    $("body").delegate("#select_url","change",function() {
        $("this").trigger("change");
        $("#form_table").attr("actions.js",$("#select_url").val());
    });
    $("body").delegate("#toquvdocumentbalancesearch-department_id","change",function() {
        $('#reportResultIncoming table tbody').html("");
        $('#reportResultIncoming table tfoot').html("");
        $("#report_department_id").val($(this).val());
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerCss('
.checkbox__label:before{content:\' \';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:\' \';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:1px;margin-left:5px;line-height:.75}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}

@keyframes click-wave { 0% { height: 40px; width: 40px; opacity: 0.35; position: relative; } 100% { height: 200px; width: 200px; margin-left: -80px; margin-top: -80px; opacity: 0; } } .option-input { -webkit-appearance: none; -moz-appearance: none; -ms-appearance: none; -o-appearance: none; appearance: none; position: relative; top: 1px; right: 0; bottom: 0; left: -2px;; height: 40px; width: 40px; transition: all 0.15s ease-out 0s; background: #cbd1d8; border: none; color: #fff; cursor: pointer; display: inline-block; margin-right: 0.5rem; outline: none; z-index: 1000; } .option-input:hover { background: #9faab7; } .option-input:checked { background: #40e0d0; } .option-input:checked::before { height: 40px; width: 40px; position: absolute; content: \'✔\'; display: inline-block; font-size: 26.66667px; text-align: center; line-height: 40px; } .option-input:checked::after { -webkit-animation: click-wave 0.65s; -moz-animation: click-wave 0.65s; animation: click-wave 0.65s; background: #40e0d0; content: \'\'; display: block; position: relative; z-index: 100; } .option-input.radio { border-radius: 50%; } .option-input.radio::after { border-radius: 50%; } .radio_div label { display: flex; float: left; margin-right: 10px; align-content: center; align-items: center; font-size: 25px; justify-content: center; }
.label_checkbox{display: flex; align-content: center; align-items: end;}
.report-table tbody tr{cursor:pointer}
.customHeight{height:40px;font-size:20px}
');