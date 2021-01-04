<?php
/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $items array */
/* @var $data array */

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax; ?>

<?php Pjax::begin(['id' => 'reportResultIncoming'])?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_mato', [
                    'model' => $model,
                    'data' => $data,
                    'from_model' => null,
                    'entity_type' => null,
                    'search_mato' => null,
                    'url' => null,
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
<div class="report-ip-title">
    <h3 class="text-center" style="padding-bottom: 25px;">
        <?= Yii::t('app', "{name}: {from} - {to} sana oralig'idagi mato holati", ['from' => $data['from_date'], 'to' => $data['to_date'], 'name' => $data['name']]) ?>
        <br>
        (<b><?=\app\modules\toquv\models\ToquvOrders::getOrderTypeList(\app\modules\toquv\models\ToquvOrders::ORDER_SERVICE)?></b>)
    </h3>
</div>
<div id="print-barcode">
    <form id="form_table" action="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/kochirish_mato/create')?>" method="POST">
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
                    <th><?= Yii::t('app', 'Mato egasi') ?></th>
                    <th><?= Yii::t('app', 'Mato Nomi') ?> / <?= Yii::t('app', 'Turi') ?></th>
                    <th><?= Yii::t('app', 'Pus/Fine') ?></th>
                    <th><?= Yii::t('app', 'Sort') ?></th>
                    <th class="info_td"><?= Yii::t('app', 'Thread Length')." - ".Yii::t('app', 'Finish En').' - '.Yii::t('app', 'Finish Gramaj') ?></th>
                    <?php if($model->group_by_ip){?>
                    <th><?= Yii::t('app', 'Buyurtma miqdori (kg)') ?></th>
                    <?php }?>
                    <th><?= Yii::t('app', 'Qoldiq (kg)') ?></th>
                    <th><?= Yii::t('app', 'Qoldiq (rulon)') ?></th>
                    <?php if($model->group_by_ip){?>
                    <th><?= Yii::t('app', 'Rang') ?></th>
                    <th><?= Yii::t('app', "Rang(Bo'yoqxona)") ?></th>
                    <?php }?>
                </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $totalQty = 0;
            $totalOrderQty = 0;
            $totalRoll = 0;
            if (!empty($items)) {
                foreach ($items as $item):?>
                    <?php
                    $totalQty += $item['qoldiq'];
                    $totalOrderQty += $item['order_quantity'];
                    $totalRoll += $item['roll'];
                    $bgStyle = "background-color: inherit";
                    if (!empty($item['qoldiq']) && $item['qoldiq'] > 0) {
                        $bgStyle = 'background-color: #f1f1f1;';
                    }
                    if($item['sort']=='2-nav'){
                        $bgStyle = 'background-color: #E8EAF6;';
                    }
                    if($item['sort']=='Brak'){
                        $bgStyle = 'background-color: #E0F2F1;';
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
                        <td class="left-center"><b><?= $item['mato']?> / <?=$item['type']?></b></td>
                        <td><?= $item['pus_fine']?></td>
                        <td><?= $item['sort']?></td>
                        <td><?= $item['info']?></td>
                        <?php if($model->group_by_ip){?>
                        <td><b><?= number_format($item['order_quantity'], 2, ',', ' ') ?></b></td>
                        <?php }?>
                        <td><b><?= number_format($item['qoldiq'], 2, ',', ' ') ?></b></td>
                        <td><b><?= number_format($item['roll'], 0, '.', ' ') ?></b></td>
                        <?php if($model->group_by_ip){?>
                        <td><?="<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> ".$item['c_pantone']?></td>
                        <td><?=" <span style='background:{$item['b_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> {$item['color_id']}"?></td>
                        <?php }?>
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
                <th></th>
                <?php if($model->group_by_ip){?>
                <th><?= number_format($totalOrderQty, 3, '.', ' '); ?></th>
                <?php }?>
                <th><?= number_format($totalQty, 3, '.', ' '); ?></th>
                <th><?= $totalRoll; ?></th>
            </tr>
            </tfoot>
        </table>
        <?php if($model->group_by_ip && (Yii::$app->user->can('toquv-documents/chiqim_mato/index') || Yii::$app->user->can('toquv-documents/kochirish_mato/index') || Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_mato/index'))){ ?>
        <div class="input-group" style="width: 200px">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <input type="hidden" name="department_id" value="<?=\app\modules\toquv\models\ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id']?>">
            <select id="select_url" class="form-control customHeight">
                <?php if(Yii::$app->user->can('toquv-documents/kochirish_mato/create')){?>
                <option value="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/kochirish_mato/create')?>"><?php echo Yii::t('app',"Ko'chirish")?></option>
                <?php } if(Yii::$app->user->can('toquv-documents/chiqim_mato/create')){?>
                <option value="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/chiqim_mato/create')?>"><?php echo Yii::t('app',"Chiqim")?></option>
                <?php } if(Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_mato/create')){?>
                <option value="<?=Yii::$app->urlManager->createUrl('toquv/toquv-documents/hisobdan_chiqarish_mato/create')?>"><?php echo Yii::t('app',"Hisobdan chiqarish")?></option>
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
    $(document).ajaxStop(function() {
        $("table").tableExport({
            headers: true,
            footers: true,
            formats: ["xlsx", "csv", "xls"],
            filename: "id",
            bootstrap: true,
            exportButtons: true,
            position: "top",
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: "id",
        });
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