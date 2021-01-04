<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $items app\modules\bichuv\models\BichuvItemBalanceSearch */

$this->title = Yii::t('app', 'Qoldiq Aksesuar');
?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search', ['model' => $model, 'data' => $data]),
                    'contentOptions' => ['class' => '']
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
            <?= Yii::t('app', "Bichuv Aksesuar Ombori: {from} - {to} sana oralig'idagi ombordagi aksesuar holati", ['from' => $data['from_date'], 'to' => $data['to_date']]) ?>
        </h3>
    </div>
    <form target="print_popup" id="form_table"
          action="<?= Yii::$app->urlManager->createUrl('bichuv/doc/kochirish_acs/create') ?>" method="POST">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfParam ?>">
        <input type="hidden" name="department_id" id="dep_id"
               value="">
        <table class="table table-bordered report-table">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Name') ?></th>
                <th><?= Yii::t('app', 'Qoldiq') ?></th>
                <th><?= Yii::t('app', 'Narx') ?></th>
                <th><?= Yii::t('app', 'Summa (UZS)') ?></th>
                <th><?= Yii::t('app', 'Summa ($)') ?></th>
                <th width="5%"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $totalQty = 0;
            $totalSum = 0;
            $totalDollar = 0;
            $price = [];
            $price['val'] = 0;
            if (!empty($items)) {
                foreach ($items as $item):
                    $priceUSD = $item['price_usd'];
                    $priceUZS = $item['price_uzs'];
                    $totalQty += $item['summa'];
                    $totalSum += ($item['summa'] * $priceUZS);
                    $totalDollar += ($item['summa'] * $priceUSD);
                    $price['val'] = $priceUZS;
                    $price['symbol'] = "So'm";
                    $bgStyle = "background-color: inherit";
                    if (!empty($priceUSD) && $priceUSD > 0) {
                        $price['val'] = $priceUSD;
                        $price['symbol'] = "$";
                        $bgStyle = 'background-color: #f4f4f4;';
                    }
                    ?>
                    <tr style="<?= $bgStyle; ?>">
                        <td><?= $count ?></td>
                        <td class="left-text"><?= $item['sku'] . ' ' . $item['property'] . ' ' . $item['accs'] ?></td>
                        <td class="<?= ($item['min_limit'] >= $item['summa']) ? 'danger' : '' ?>">
                            <b><?= number_format($item['summa'], 2, '.', ' ') ?></b>&nbsp;<small><i><?= $item['unit'] ?></i></small>
                        </td>
                        <td><?= number_format($price['val'], 2, '.', ' ') ?>&nbsp;<small><i><?= $price['symbol'] ?></i></small>
                        </td>
                        <td><?= number_format($item['summa'] * $priceUZS, 2, '.', ' ') ?></td>
                        <td><?= number_format($item['summa'] * $priceUSD, 2, '.', ' ') ?></td>
                        <td style="text-align: left" width="8%">
                            <div>
                                <label class="checkbox-transform"><?= Html::checkbox('bib_id[]', false, ['class' => 'checkbox__input checked_input', 'value' => $item['id'], 'onclick' => '$(this).closest("tr").toggleClass("danger");']) ?>
                                    <span class="checkbox__label"></span>
                                </label>
                                <div class="hidden mato_div">
                                    <div class="row" style="border-bottom: 1px solid;padding: 10px;">
                                        <div class="col-md-4 text-center">
                                            <code><b><?= $item['sku'] . ' ' . $item['property'] . ' ' . $item['accs'] ?></b></code>
                                        </div>
                                        <div class="col-md-4 text-center"><b><?= $item['summa'] ?></b></div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <?php
                    $count++;
                endforeach;
            } else {
                ?>
                <tr>
                    <td colspan="6" class="text-danger ">
                        <?= Yii::t('app', 'Ma\'lumot mavjud emas') ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="2" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
                <th><?= number_format($totalQty, 2, '.', ' '); ?></th>
                <th></th>
                <th><?= number_format($totalSum, 2, '.', ' '); ?>&nbsp;<?= Yii::t('app', 'So\'m') ?></th>
                <th><?= number_format($totalDollar, 2, '.', ' '); ?>&nbsp;$</th>
            </tr>
            </tfoot>
        </table>
    </form>
    <div style="position:fixed;right: 0;bottom: 50%;display: none;" id="sendButton">
        <button type="button" class="btn btn-success btn-lg sendButton"><i class="fa fa-send"></i></button>
    </div>
    <div class="modal fade" id="modal-report" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="modal-send" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center"><code><b><?= Yii::t('app', 'Tanlangan aksessuarlar') ?></b></code></h1>
                    <br>
                    <div class="row">
                        <div class="col-md-4 text-center text-bold"><?= Yii::t('app', 'Aksessuar') ?></div>
                        <div class="col-md-4 text-bold text-center"><?= Yii::t('app', 'Qoldiq(Soni)') ?></div>
                    </div>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <?php if ((Yii::$app->user->can('bichuv/doc/kochirish_acs/index') || Yii::$app->user->can('bichuv/doc/kochirish_acs/index') || Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_mato/index'))) { ?>
                        <div class="input-group" style="width: 200px">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                                   value="<?= Yii::$app->request->csrfToken; ?>"/>
                            <input type="hidden"  name="department_id" value="10">
                            <select id="select_url" class="form-control customHeight">
                                <?php if (Yii::$app->user->can('bichuv/doc/kochirish_acs/create')) { ?>
                                    <option value="<?= Yii::$app->urlManager->createUrl('bichuv/doc/kochirish_acs/create') ?>"><?php echo Yii::t('app', "Ko'chirish") ?></option>
                                <?php }
                                if (Yii::$app->user->can('bichuv/doc/chiqim_acs/create')) { ?>
                                    <option value="<?= Yii::$app->urlManager->createUrl('bichuv/doc/chiqim_acs/create') ?>"><?php echo Yii::t('app', "Chiqim") ?></option>
                                <?php }
                                 ?>
                            </select>
                            <span class="input-group-btn">
                        <button type="button" class="btn btn-success customHeight" id="send_mato_button">Ok</button>
                    </span>
                        </div>
                    <?php } ?>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<?php
$js = <<<JS
 $("body").delegate(".checked_input","click",function() {
    let input = $(".checked_input:checked");
    if(input.length > 0){
        $('#sendButton').show();
    }else{
        $('#sendButton').hide();
    }
});
$("body").delegate(".sendButton","click",function() {
    let selectedDepId =  $("#bichuvitembalancesearch-department_id").val();
    $('#dep_id').val(selectedDepId);
    let mato = $(".checked_input:checked");
    if(mato.length > 0){
        $('#modal-send').modal('show');
        let result = '';
        mato.each(function() {
          result += $(this).parents('td').find('.mato_div').html();
        });
        $('#modal-send').find('.modal-body').html(result);
    }else{
        $('#sendButton').hide();
    }
});

 $("body").delegate("#send_mato_button","click",function(e) {
        let formData = $('#form_table').serialize();
        /*$('#modal-report').find('.modal-body').load(,formData, function(data){
            $('#modal-report').modal('show');                          
        });*/
        /*$.ajax({
            url: $('#select_url').val(),
            type: "POST",
            data: formData,
            success: function( response ){
                $('#modal-report').find('.modal-body').html(response);
                $('#modal-report').modal('show');
                $('#modal-send').modal('hide');
            },
            error: function(error){
                alert(error.responseText);
            }
        });*/
        $('#form_table').submit();
    });
    $("body").delegate("#select_url","change",function() {
        $("this").trigger("change");
        $("#form_table").attr("action",$("#select_url").val());
    });
 $(".report-table tbody tr").on("click",function(e) {
        if(e.target.tagName!='I'&&e.target.tagName!='BUTTON'){
            let _this = $(this);
            let input = $(_this).find(".checked_input");
            if(input.prop("checked") == false){
                input.prop("checked",true);
            }else{
                input.prop("checked",false);
            }
        }
    });
 
 
 
 

JS;
$this->registerJs($js);

$css = <<< CSS
.checkbox__label:before{content:' ';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:' ';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:1px;margin-left:5px;line-height:.75}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}

@keyframes click-wave { 0% { height: 40px; width: 40px; opacity: 0.35; position: relative; } 100% { height: 200px; width: 200px; margin-left: -80px; margin-top: -80px; opacity: 0; } } .option-input { -webkit-appearance: none; -moz-appearance: none; -ms-appearance: none; -o-appearance: none; appearance: none; position: relative; top: 1px; right: 0; bottom: 0; left: -2px;; height: 40px; width: 40px; transition: all 0.15s ease-out 0s; background: #cbd1d8; border: none; color: #fff; cursor: pointer; display: inline-block; margin-right: 0.5rem; outline: none; z-index: 1000; } .option-input:hover { background: #9faab7; } .option-input:checked { background: #40e0d0; } .option-input:checked::before { height: 40px; width: 40px; position: absolute; content: '✔'; display: inline-block; font-size: 26.66667px; text-align: center; line-height: 40px; } .option-input:checked::after { -webkit-animation: click-wave 0.65s; -moz-animation: click-wave 0.65s; animation: click-wave 0.65s; background: #40e0d0; content: ''; display: block; position: relative; z-index: 100; } .option-input.radio { border-radius: 50%; } .option-input.radio::after { border-radius: 50%; } .radio_div label { display: flex; float: left; margin-right: 10px; align-content: center; align-items: center; font-size: 25px; justify-content: center; }
.label_checkbox{display: flex; align-content: center; align-items: end;}
.report-table tbody tr{cursor:pointer}
.customHeight{height:40px;font-size:20px}
.btn2{text-align: center;
    white-space: pre-wrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    padding: 3px 4px;
    font-size: 14px;
    line-height: 1.42857143;}
CSS;
$this->registerCss($css);
