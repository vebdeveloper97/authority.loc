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
                    'content' => $this->render('orders_search', ['model' => $model, 'data' => $data]),
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
            <?= Yii::t('app', "Buyurtmalar aksesuar holati", ['from' => $data['from_date'], 'to' => $data['to_date']]) ?>
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
                <th><?= Yii::t('app', 'Model Orders') ?></th>
                <th><?= Yii::t('app', 'Bichuv Acs') ?></th>
                <th><?= Yii::t('app', 'Qty') ?></th>
                <th><?= Yii::t('app', 'Load Date') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $totalSum = 0;
            if (!empty($items)) {
                foreach ($items as $item):
                    $totalQty += $item['qty'];
                    $bgStyle = "background-color: #f4f4f4";
                    ?>
                    <tr style="<?= $bgStyle; ?>">
                        <td><?= $count ?></td>
                        <td class="left-text"><?=$item['doc_number']?></td>
                        <td class="left-text"><?php
                                $str = $item['name'];
                                if(!empty($item['value'])){
                                    foreach ($item['value'] as $m) {
                                        $str = $str .' '.$m;
                                    }
                                }
                                echo $str;
                            ?></td>
                        <td class="danger">
                            <b><?= number_format($item['qty'], 2, '.', ' ') ?></b>&nbsp;<small><i><?= $item['uname'] ?></i></small>
                        </td>
                        <td><?= $item['load_date'] ?></td>

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
                <th colspan="3" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
                <th colspan="1" ><?= number_format($totalQty, 2, '.', ' '); ?></th>
                <th colspan="2"></th>
            </tr>
            </tfoot>
        </table>
    </form>


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
