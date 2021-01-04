<?php

use app\widgets\helpers\Script;use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelItems app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t',1);
?>
<div class="kirim-mato-tab">
    <?php if($model->isNewRecord){
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'CRYSTAL COLOR',
                    'active' => ($t == 1),
                    'content' => $this->render('kirim_mato/_kirim_mato_in', ['model' => $model, 'models' => $models, 'form' => $form]),
                    'url' => Url::current(['slug' =>$this->context->slug,'t'=> 1])
                ],
                [
                    'label' => 'BOSHQALAR',
                    'active' => $t == 2,
                    'content' => $this->render('kirim_mato/_kirim_mato_out', [
                        'model' => $model,
                        'models' => $models,
                        'form' => $form
                    ]),
                    'url' => Url::current(['slug' =>$this->context->slug,'t'=> 2])
                ],
                [
                    'label' => 'QOLDIQ',
                    'active' => $t == 3,
                    'content' => $this->render('kirim_mato/_kirim_mato_remain', [
                        'model' => $model,
                        'models' => $models,
                        'form' => $form
                    ]),
                    'url' => Url::current(['slug' =>$this->context->slug,'t'=> 3])
                ],
            ],
        ]);
    }else{
        if($t == 2){
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'BOSHQALAR',
                        'active' => $t == 2,
                        'content' => $this->render('kirim_mato/_kirim_mato_out', [
                            'model' => $model,
                            'models' => $models,
                            'form' => $form
                        ]),
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 2])
                    ],
                ],
            ]);
        }elseif($t == 3){
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'NIL GRANIT',
                        'active' => ($t == 3),
                        'content' => $this->render('kirim_mato/_kirim_mato_remain', [
                            'model' => $model,
                            'models' => $models,
                            'form' => $form]),
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 3])
                    ]]]);
        }else{
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'NIL GRANIT',
                        'active' => ($t == 1),
                        'content' => $this->render('kirim_mato/_kirim_mato_in', ['model' => $model, 'models' => $models, 'form' => $form]),
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 1])
                    ]]]);
        }

    }
 ?>
</div>
<?php
$slug = Yii::$app->request->get('slug');
$formId = $form->getId();
$urlGetMato = Url::to(['get-rm-info', 'slug' => $slug, 't' => $t]);
$saveBssId = "";
if(!$model->isNewRecord){
    $saveBssId = $model->getDIEntityIds();
}
$urlMusteriParty = Url::to(['check-musteri-party','slug' => $slug]);
$musteriPartytext = Yii::t('app','Bunday mujoz partiya raqami oldin kiritilgan');
$musteriText = Yii::t('app','Iltimos olding kontragentni tanlang');
Script::begin();
?>
<script>
    $('#<?= $formId; ?>').keypress(function (e) {
        if (e.which == 13) {
            return false;
        }
    });
    $('body').delegate('.musteri-party-no', 'blur', function(e){
       let mId = $('#bichuvdoc-musteri_id').val();
        let self = $(this);
        let mPn = self.val();
        if(mId){
            $.ajax({
                url:'<?= $urlMusteriParty?>?musteriParty='+mPn+'&musteriId='+mId,
                success: function (response) {
                    if(response.status){
                        PNotify.defaults.styling = 'bootstrap4';
                        PNotify.defaults.delay = 5000;
                        PNotify.alert({text: response.message, type: 'error'});
                        self.val('');
                        self.focus();
                        return false;
                    }
                }
            });
       }else{
           PNotify.defaults.styling = 'bootstrap4';
           PNotify.defaults.delay = 5000;
           PNotify.alert({text: '<?= $musteriText; ?>', type: 'error'});
           self.val('');
           return false;
       }
    });
    function calculateSum(id, className){
            let rmParty = $('#documentitems_id table tbody tr').find(className);
            if(rmParty){
                let totalRMParty = 0;
                rmParty.each(function (key, item) {
                    if($(item).val()){
                        totalRMParty += parseFloat($(item).val());
                    }
                });
                $(id).html(totalRMParty.toFixed(2));
            }
    }
    $('#documentitems_id').on('afterInit', function (e, index) {
        calculateSum('#footer_roll_count','.roll-count');
        calculateSum('#footer_document_quantity','.doc-qty');
        calculateSum('#footer_quantity','.rm-fact');
    });
    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
        if (index == 1) {
            $('#documentitems_id').multipleInput('add');
        }
        calculateSum('#footer_roll_count','.roll-count');
        calculateSum('#footer_document_quantity','.doc-qty');
        calculateSum('#footer_quantity','.rm-fact');
    });
    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
        calculateSum('#footer_roll_count','.roll-count');
        calculateSum('#footer_document_quantity','.doc-qty');
        calculateSum('#footer_quantity','.rm-fact');

        $('#bichuvsubdocitems-'+index+'-mato').trigger('change');
        $('#bichuvsubdocitems-'+index+'-ne').trigger('change');
        $('#bichuvsubdocitems-'+index+'-thread').trigger('change');
        $('#bichuvsubdocitems-'+index+'-pus_fine').trigger('change');
        $('#bichuvsubdocitems-'+index+'-model').trigger('change');
    });
    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
        calculateSum('#footer_roll_count','.roll-count');
        calculateSum('#footer_document_quantity','.doc-qty');
        calculateSum('#footer_quantity','.rm-fact');
    });

    $('body').delegate('#barcodeInput','keyup', function (e) {
        let barcode = $(this).val();

        async function doAjax(args) {
            let result;
            try {
                result = await $.ajax({
                    url: '<?= $urlGetMato; ?>',
                    type: 'POST',
                    data: args
                });
                return result;
            } catch (error) {
                console.error(error);
            }
        }
        if (e.which == 13) {
            if (!barcode) return false;
            $(this).val('').focus();
            let selectObj = $('#documentitems_id table tbody tr:last').find('select.mato-kirim-select2');
            let existParties = $('#documentitems_id').find('.mato-kirim-select2');
            let args = {};
            if (existParties) {
                args.party = {};
                existParties.each(function (key, val) {
                    let partyId = $(val).val();
                    if(partyId){
                        args.party[partyId] = partyId;
                    }
                });
            }
            args.saved = "<?= $saveBssId; ?>";
            args.barcode = barcode;
            args.type = 1;
            doAjax(args).then((data) => otherDo(data));

            function otherDo(data) {
                if (data.status == 1) {
                    for (let i in data.response) {
                        let item = data.response;
                        if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                        let newOption = new Option(item[i].name, item[i].id, true, true);
                        let lastObj = $('#documentitems_id table tbody tr:last');
                        lastObj.attr('data-bss-id', item[i].id);
                        lastObj.find('select.mato-kirim-select2').append(newOption).trigger('change');
                        lastObj.find('.rm-party').val(item[i].party+'/'+item[i].musteri_party);
                        lastObj.find('.bss-id').val(item[i].id);
                        lastObj.find('.doc-qty').val((item[i].qty*1).toFixed(3));
                        lastObj.find('.document-quantity').val((item[i].qty*1).toFixed(3));
                        lastObj.find('.rm-fact').val((item[i].qty*1).toFixed(3));
                        lastObj.find('.is-accessory').val(item[i].is_accessory);
                        lastObj.find('.roll-count').val((item[i].count*1).toFixed(2));
                        lastObj.find('.party-no').val(item[i].party);
                        lastObj.find('.musteri-party-no').val(item[i].musteri_party);

                        lastObj.find('.model-id').val(item[i].model_id).trigger('change');
                        lastObj.find('.en').val(item[i].en);
                        lastObj.find('.gramaj').val(item[i].gramaj);
                        lastObj.find('.ne-id').val(item[i].ne_id);
                        lastObj.find('.pus-fine-id').val(item[i].pus_fine_id);
                        lastObj.find('.rm-id').val(item[i].rm_id);
                        lastObj.find('.thread-id').val(item[i].thread_id);
                        lastObj.find('.c-id').val(item[i].c_id);
                    }
                    calculateSum('#footer_roll_count','.roll-count');
                    calculateSum('#footer_document_quantity','.doc-qty');
                    calculateSum('#footer_quantity','.rm-fact');
                } else if (data.status == 2) {
                    PNotify.defaults.styling = 'bootstrap4';
                    PNotify.defaults.delay = 5000;
                    PNotify.alert({text: data.message, type: 'error'});
                    return false;
                } else {
                    PNotify.defaults.styling = 'bootstrap4';
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text: data.message, type: 'error'});
                    return false;
                }
            }
        }
    });
</script>
<?php Script::end();?>



