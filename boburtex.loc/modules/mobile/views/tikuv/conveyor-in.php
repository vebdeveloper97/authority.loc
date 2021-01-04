<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\SearchFormViaNastel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{type} (Accept)', ['type' => $this->context->mobileTable['name']]);
$this->params['breadcrumbs'][] = [
    'label' => '<i class="fa fa-2x fa-chevron-circle-left"></i>',
    'url' => ['index', 'slug' => $this->context->slug],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">

    <div class="search-box">
        <div class="row">
            <div class="col-xs-10">
                <?= $this->render('_search', ['model' => $model]);?>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-info" onclick="androidInteractor.openBarCodeScanner()"><span class="fa fa-2x fa-qrcode"></span></button>
            </div>
        </div>
    </div>

    <?php \yii\widgets\Pjax::begin(['id' => 'tikuv_doc_grid_view_pjax_container']) ?>
        <div class="table-responsive">
            <?= GridView::widget([
                'id' => 'gridview_tikuv_doc',
                'dataProvider' => $dataProvider,
                'rowOptions'=>function($model){
                    if($model->status < 3){
                        return ['class' => 'danger each-tikuv-row'];
                    }else{
                        return ['class' => 'success each-tikuv-row'];
                    }
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'nastel_no',
                        'label' => Yii::t('app','Nastel'),
                        'value' => function($model){
                            return $model->getNastelParty('slice');
                        }
                    ],
                    [
                        'attribute' => 'model',
                        'label' => Yii::t('app','Model'),
                        'value' =>  function($model){
                            $modelData = $model->getModelListInfo();
                            return $modelData['model'];
                        },
                        'options' => ['class' => 'text-center'],
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'white-space: normal;width:20%'],
                    ],
                    [
                        'attribute' => 'count_work',
                        'label' => Yii::t('app',"Ish soni"),
                        'value' => function($model){
                            return $model->getWorkCount();
                        },
                        'format' => 'raw'
                    ],
                ],
            ]); ?>
        </div>
    <?php \yii\widgets\Pjax::end() ?>

    <!-- begin accept modal form -->
    <?php yii\bootstrap\Modal::begin([
        'headerOptions' => ['id' => 'modalHeader'],
        'options' => [
            'tabindex' => false,
        ],
        'id' => 'doc_index_modal',
        //    'size' => 'modal-sm',
        //    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]);
    echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end(); ?>
    <!-- end accept modal form -->

    <!-- begin add_info view for modal form -->
    <?php yii\bootstrap\Modal::begin([
        'headerOptions' => ['id' => 'addInfoModalHeader'],
        'options' => [
            'tabindex' => false,
        ],
        'id' => 'add_info_view_modal',
        'size' => 'modal-sm',
        //    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
    ]);
    echo "<div id='addInfoModalContent'></div>";
    yii\bootstrap\Modal::end(); ?>
    <!-- end add_info view for modal form -->

    <?php $this->registerJs("function onScanSuccess(barcodeValue) {
            barcodeValue = utf8_to_str(barcodeValue);
            alert('onScanSuccess');
            //Modal ochilsin
            $('#tikuvNastelNo').val(barcodeValue);
            $('#tikuvSearchNastel').submit();
        }")?>
<?php
$js = <<<JS
    const gridViewTikuvDoc = document.getElementById('tikuv_doc_grid_view_pjax_container');
    const modalForm = $('#doc_index_modal');
    const addInfoModal = $('#add_info_view_modal');
    let formId = 'form_accept_slice_form';
    let inputId = $(this).data('inputName');
    let url = '/uz/mobile/tikuv/{$this->context->slug}/view';
    let acceptUrl = '/uz/mobile/tikuv/{$this->context->slug}/accept';
    PNotify.defaultModules.set(PNotifyMobile, {});
    let notice;

    /** gridview dagi har bir row uchun ishlaydi **/
    gridViewTikuvDoc.addEventListener('click', function (event) {
        let tr = event.target.closest('TR')
        if (!tr) return;
        if (!gridViewTikuvDoc.contains(tr)) return;
        let docId = tr.dataset.key;
        if (!docId) return;


        modalForm.find('#modalContent')
            .load(url+'?id='+docId, function(responseTxt, statusTxt, jqXHR){
                if(statusTxt === "success"){
                    if (!modalForm.data('bs.modal').isShown) {
                        modalForm.modal('show');
                    }
                    
                    // modal ichidagi popoverlarni init qiladi
                    $(modalForm).find('[data-toggle="popover"]').popover({
                        container: $(modalForm),
                        trigger: 'manual',
                        html: true
                    })

                    $('#'+formId).on('beforeSubmit', function (e) {
                        e.preventDefault();
                        const yiiform = $(this);
                        $.ajax({
                                type: yiiform.attr('method'),
                                url: acceptUrl + '?id=' + docId,
                                data: yiiform.serializeArray(),
                            }
                        )
                            .done(function(data) {
                                if(data.success) {
                                    modalForm.modal('hide');
                                    PNotify.success({
                                        title: 'Success!',
                                        text: 'Qabul qilindi',
                                        modules: new Map([
                                            ...PNotify.defaultModules,
                                            [PNotifyMobile, {}]
                                        ])
                                    });
                                    $.pjax.reload('#tikuv_doc_grid_view_pjax_container')
                                } else if (data.validation) {
                                    // server validation failed
                                    yiiform.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                                } else {
                                    PNotify.error({
                                        title: 'Xatolik!',
                                        text: 'Xatolik yuz berdi!',
                                        modules: new Map([
                                            ...PNotify.defaultModules,
                                            [PNotifyMobile, {}]
                                        ])
                                    });
                                }
                            })
                            .fail(function () {
                                // request failed
                            })

                        return false; // prevent default form submission
                    })
                }
                if(statusTxt === "error"){
                    PNotify.notice({
                        title: 'Xatolik yuz berdi',
                        text: responseTxt
                    });
                }
            });
        //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-2x fa-window-close"></i></button>';
    });

    //modal form ichi bosilganda
    modalForm.on('click', function(e) {
        try {
            const target = e.target;
            const hasAcceptButton = target.classList.contains('accept-button');
            
            const hasClickedInfoBtn = target.tagName === 'BUTTON' && target.classList.contains('add_info_btn');
            const hasClickedInnerInfoBtn = target.closest('BUTTON') && target.closest('BUTTON').classList.contains('add_info_btn');
            
            // info tugma bosilganda
            if (hasClickedInfoBtn || hasClickedInnerInfoBtn) {
                
                // sabab qiymatini modalga chiqarish
                let currentAddInfoValue = target.closest('TD').querySelector('textarea[name$="[add_info]"]').value;
                addInfoModal.find('#addInfoModalContent').html(currentAddInfoValue);
                addInfoModal.find('#addInfoModalHeader')
                    .html( 
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-2x fa-window-close"></i></button>'
                        + '<h4 class="modal-title">Sabab</h4>'
                    );
                // add_info uchun popaplarni init qiladi
                let addInfoBtn = target.closest('BUTTON');
                $(addInfoBtn).attr('data-content', currentAddInfoValue);
                $(addInfoBtn).popover('toggle');
                
                return false;
            }
            
            
        
            // agar qabul qilish bosilmasa
            if (!hasAcceptButton) return false;
        } catch (e) {
            console.dir(e);
        }
        
        // formani submit qilish
        $('#' + formId).yiiActiveForm('submitForm');
    });
    
    // sabab modali yopilganda form modalni active qilish
    addInfoModal.on('hidden.bs.modal', function(e) {
        modalForm.modal('handleUpdate');
    });

    /** modal form dagi fact_quantity change bo'lsa ishlaydi **/
    modalForm.on('change', function (e) {
        const factQtyInput = e.target;
        let factQty = parseInt(factQtyInput.value);
        let qty = parseInt(factQtyInput.dataset.quantity);

        // fact quantity equal qty then return
        if (factQty === qty) return;

        if ((typeof notice) === 'object' && notice.close) {
            notice.close();
        }
        notice = PNotify.notice({
            type: 'info',
            title: 'Izoh',
            icon: 'fa fa-info-circle',
            hide: false,
            closer: false,
            sticker: false,
            destroy: true,
            modules: new Map([
                ...PNotify.defaultModules,
                [PNotifyConfirm, {
                    prompt: true,
                    promptMultiLine: true,
                }]
            ])
        });
        notice.on('pnotify:confirm', e => { // agar confirm bo'lsa
            const currentContainerTD = factQtyInput.closest('td');
            if (currentContainerTD !== null) {
                const addInfoInput = currentContainerTD.querySelector('textarea[name$="[add_info]"]');
                addInfoInput.value = e.detail.value;
            }
        });
        notice.on('pnotify:cancel', () => {
            notice.cancelClose();
        });

        /** begin calculate fact_quantity **/
        const totalFactQtyEl = document.getElementById('totalFactQuantity');
        totalFactQtyEl.innerText = getCalculatedInputQty('#doc_index_modal input[name$="[fact_quantity]"]')
        /** end calculate fact_quantity **/
    });

    /** === begin functions list for qr code === **/
    function utf8_to_str(a) {
        let str = unescape(a);
        return str.replace(/\+/g, " ");
    }
    // function onScanSuccess(barcodeValue) {
    //     barcodeValue = utf8_to_str(barcodeValue);
    //     alert('onScanSuccess');
    //     //Modal ochilsin
    //     $('#tikuvNastelNo').val(barcodeValue);
    //     $('#tikuvSearchNastel').submit();
    // }
    /** === end functions list for qr code === **/

    /**
     * css selector yordamida input value lari yig'indisini qaytaradi
     * @param inputsSelector
     * @returns {number}
     */
    function getCalculatedInputQty(inputsSelector) {
        let inputs = document.querySelectorAll(inputsSelector);
        let totalSum = 0;
        inputs.forEach((val, key) => {
            totalSum += +val.value;
        });
        return totalSum;
    }
JS;

$this->registerJs($js);
?>
</div>
