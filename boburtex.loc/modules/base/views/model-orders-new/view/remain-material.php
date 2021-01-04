<?php
/* @var $dataProviderRemainMaterial \yii\data\ActiveDataProvider */
/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrders */

use app\modules\wms\models\WmsMatoInfo;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\assets\Select2Asset;
use yii\helpers\Url;

Select2Asset::register($this);

$heading = '<i class="fa fa-pie-chart"></i> ' .  Yii::t('app', 'Remain');

$exportConfig = [
    GridView::EXCEL => [
        'filename' => 'mato_qoldiq_' . date('d.m.Y')
    ],
    GridView::PDF => [
        'filename' => 'mato_qoldiq_' . date('d.m.Y')
    ],
];
$gridColumns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],
        'width' => '36px',
        'header' => '',
        'headerOptions' => ['class' => 'kartik-sheet-style']
    ],
    [
        'attribute' => 'musteri_id',
        'value' => function ($model) {
            return $model->musteri->name;
        },
        'vAlign' => 'middle',
        'width' => '180px',
    ],
    [
        'attribute' => 'entity_id',
        'value' => function($model) {
            return WmsMatoInfo::getMaterialNameById($model->entity_id);
        },
        'vAlign' => 'middle',
        'width' => '350px',
    ],
    [
        'attribute' => 'musteri_party_no',
        'hAlign' => 'right',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'lot',
        'vAlign' => 'middle',
        'hAlign' => 'right',
    ],
    [
        'label' => Yii::t('app', 'Color'),
        'value' => function($model) {
            return WmsMatoInfo::getMaterialColorById($model->entity_id);
        },
        'vAlign' => 'middle',
    ],
    [
        'label' => Yii::t('app', 'En'),
        'value' => function($model) {
            $wmsMatoInfo = WmsMatoInfo::getList($model->entity_id);
            return !empty($wmsMatoInfo['en']) ? intval($wmsMatoInfo['en']) : Yii::t('app', 'Not specified');
        },
        'vAlign' => 'middle',
    ],
    [
        'label' => Yii::t('app', 'Gramaj'),
        'value' => function($model) {
            $wmsMatoInfo = WmsMatoInfo::getList($model->entity_id);
            return !empty($wmsMatoInfo['gramaj']) ? intval($wmsMatoInfo['gramaj']) : Yii::t('app', 'Not specified');
        },
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'order',
        'label' => Yii::t('app', 'Order / Model / Variant / Load date'),
        'value' => function ($model) {
            return '<div>' . $model->modelOrdersItems->modelOrders->doc_number . '</div>'
                    . '<div>' . $model->modelOrdersItems->modelsList->name . '</div>'
                    . '<div>' . $model->modelOrdersItems->modelVar->name . '</div>'
                    . '<div>' . $model->modelOrdersItems->load_date . '</div>';
        },
        'vAlign' => 'middle',
        'width' => '180px',
        'format' => 'html',
    ],
    [
        'attribute' => 'to_musteri',
        'value' => function ($model) {
            return $model->toMusteri->name;
        },
        'vAlign' => 'middle',
        'width' => '180px',
    ],
    [
        'attribute' => 'dep_area',
        'value' => function($model){
            return $model->depArea->name;
        },
        'hAlign' => 'right',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'inventory',
        'format' => 'raw',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'width' => '180px',
        'pageSummary' => true
    ],
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'rowSelectedClass' => GridView::TYPE_SUCCESS,
    ]
];

$this->title = Yii::t('app',"Report (remain)");
?>

<?= GridView::widget([
    'id' => 'remain_material_gridview',
    'dataProvider' => $dataProviderRemainMaterial,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => [
        'style' => 'overflow: auto',
        'responsive' => true,
    ], // only set when $responsive = false
    'responsiveWrap' => false,
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
        Html::button(Yii::t('app', 'Booking selected fabrics'), ['id' => 'change_model_order','class' => 'btn btn-info']),
        '{export}',
        '{toggleData}',
    ],
    // set export properties
    'export' => [
        'fontAwesome' => true
    ],
    // parameters from the demo form
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => false,
    'hover' => true,
    'showPageSummary' => true,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $heading,
        'headingOptions' => ['class' => 'panel-heading bg-teal'],
        'before' => '<em>Mato Ombori '.date('d.m.Y H:i').' holatiga ombordagi o\'xshash matolar</em>',
    ],

    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
]); ?>

<form name="bookingMaterial">
    <!-- Modal -->
    <div id="change_order_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Booking selected fabrics') ?></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                    <button type="submit" id="submitButton" class="btn btn-success" data-loading-text="Kuting..."><?= Yii::t('app', 'Booking') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php

$bookMaterialUrl = Url::to(['/base/model-orders-new/book-material']);
$gridViewJs = <<<JS
const bookMaterialUrl = '{$bookMaterialUrl}';
const orderId = '{$model->id}';
const changeModelOrderBtn = document.getElementById('change_model_order');
const materialRemainGridView = $('#remain_material_gridview');
const changeOrderModal = $('#change_order_modal');
const bookingMaterialForm = document.forms.bookingMaterial;

changeModelOrderBtn.addEventListener('click', e => {
    const keysSelectedRows = materialRemainGridView.yiiGridView('getSelectedRows');
    // agar bitta ham row tanlanmagan bo'lsa xabar chiqarish
    if (keysSelectedRows.length === 0 ) {
        call_pnotify('fail', 'Oldin matolarni tanlang!');
        return;
    }
    
    changeOrderModal.modal('show');
    fetchToServer(keysSelectedRows);
});

// submit event for booking material
bookingMaterialForm.onsubmit = async function(e) {    
    e.preventDefault();
    
    
    const submitBtn = $('#submitButton');
    
    const formData = new FormData(bookingMaterialForm);
    
    const validateInfo = validateForm(formData);
    
    if (!validateInfo.success) {
        call_pnotify('fail', validateInfo.message, 3000);
        document.querySelector('[name="' + validateInfo.inputName + '"]').closest('div.form-group').classList.add('has-error');
        return false;
    }
    
    for(let [key, value] of formData) {
        if (key !== validateInfo.inputName) {
            const formGroupDivEl = document.querySelector('[name="' + key + '"]').closest('div.form-group');
            if (formGroupDivEl) {
                formGroupDivEl.classList.remove('has-error');
            }
        }
    }
    
    let isConfirmed = confirm('Tasdiqlash');
    if (!isConfirmed){
        return false;
    }
    
    submitBtn.button('loading');

    formData.append('orderId', orderId);
    let response = await fetch(bookMaterialUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-Token': yii.getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });

    let result = await response.json();
    
    if (result.success) {
        call_pnotify('success', result.message, 4000);
    } else {
        call_pnotify('fail', result.message, 4000);
        submitBtn.button('reset');
    }
    
    return false; 
};

function validateForm(formData) {
    const validateInfo = {
        success: false,
        message: '',
        inputName: ''
    };
    
    for(let [key, value] of formData) {
        if (key.includes('fact_quantity')) {
            let quantity = document.querySelector('[name="'+key+'"]').getAttribute('value');
            // number bo'lishi va quantity >= fact_quantity
            validateInfo.success = isFinite(value) && (+((+quantity).toFixed(3)) >= +((+value).toFixed(3))); 
            if (!validateInfo.success) {
                validateInfo.message =  "Fakt miqdor qiymati noto'g'ri";
                validateInfo.inputName =  key;
            }
        }
        
        if (key.includes('model_orders_items_id')) {
            // number bo'lishi va quantity >= fact_quantity
            validateInfo.success = value && isFinite(value) && Number.isInteger(+value); 
            if (!validateInfo.success) {
                validateInfo.message =  "Model varianti to'ldirish shart";
                validateInfo.inputName =  key;
            }
        }
        
        if (!validateInfo.success){
            break;
        }
    }
    
    return validateInfo;
}

function fetchToServer(keysSelectedRows) {
    let requestData = {
        orderId: orderId,
        keysSelectedRows: keysSelectedRows
    };
    
    fetch('/uz/base/model-orders-new/get-material-data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
            'X-CSRF-Token': yii.getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            
            changeOrderModal.find('.modal-body').empty().append(generateHtmlContentForModal(data));
        } else {
            call_pnotify('fail', 'Xatolik yuz berdi');
        }
    });
}

function generateHtmlContentForModal(data) {
    const tableEl = document.createElement('table');
    tableEl.className = "table table-bordered";
    
    //thead
    const tHeadEl = document.createElement('thead');
    tableEl.appendChild(tHeadEl);
    
    // tr for thead
    const trForTHead = document.createElement('tr');
    tHeadEl.appendChild(trForTHead);
    
    // th1
    const th1 = document.createElement('th');
    th1.innerText = "Mato";
    trForTHead.appendChild(th1);
    // th2
    const th2 = document.createElement('th');
    th2.innerText = "Qoldiq miqdor (kg)";
    trForTHead.appendChild(th2);
    // th3
    const th3 = document.createElement('th');
    th3.innerText = "Fakt miqdor (kg)";
    trForTHead.appendChild(th3);
    // th4
    const th4 = document.createElement('th');
    th4.innerText = "Model varianti";
    trForTHead.appendChild(th4);
    
    //tbody
    const tBodyEl = document.createElement('tbody');
    tableEl.appendChild(tBodyEl);
    
    let formName = bookingMaterialForm.getAttribute('name');
    for (let i = 0; i < data.materialList.length; i++) {
        let val = data.materialList[i];
        let trEl = document.createElement('tr');
        
        // td1
        let td1El = document.createElement('td');
        td1El.innerHTML = '<b>' + val.rname + '</b> (' + val.ne + ' - ' + val.thread + ') <code>' + val.color_name + ' (' + val.color_code + ')</code>'
            + (val.pus_fine ?  ' ' + val.pus_fine : '' )
            + (val.en ?  ' ' + val.en + ' sm' : '' )
            + (val.gramaj ?  ' / ' + val.gramaj + ' g/m<sup>2</sup>' : '' );
        trEl.appendChild(td1El);
        
        // td2
        let td2El = document.createElement('td');
        td2El.innerText = val.inventory;
        trEl.appendChild(td2El);
        
        // td3
        let td3El = document.createElement('td');
        trEl.appendChild(td3El);
        
        // fact_quantity input
        let div3El = document.createElement('div');
        div3El.className = 'form-group';
        td3El.appendChild(div3El);
        let inventoryInputEl = document.createElement('input');
        inventoryInputEl.name = formName + '[' + i + ']' + '[fact_quantity]';
        inventoryInputEl.type = 'number';
        inventoryInputEl.min = 0.001;
        inventoryInputEl.setAttribute('value', val.inventory);
        inventoryInputEl.step = 'any';
        inventoryInputEl.className = "form-control";
        div3El.appendChild(inventoryInputEl)
        
        // wms_item_balance_id hidden input
        const wibInputEl = document.createElement('input');
        wibInputEl.name = formName + '[' + i + ']' + '[wms_item_balance_id]';
        wibInputEl.setAttribute('value', val.id); 
        wibInputEl.setAttribute('type', 'hidden');
        td3El.appendChild(wibInputEl);
        
        // td4
        let td4El = document.createElement('td');
        let div4El = document.createElement('div');
        div4El.className = 'form-group';
        td4El.appendChild(div4El);
        trEl.appendChild(td4El);
        
        // select order items
        let selectEl = document.createElement('select')
        selectEl.name = formName + '[' + i + ']' + '[model_orders_items_id]';
        selectEl.id = 'select2_order_items_'+val.id;
        selectEl.className = 'form-control select2';
        div4El.appendChild(selectEl);
        $(selectEl).select2({
            allowClear: true,
            width: '300px',
            placeholder: 'Tanlang...',
            debug: true,
            data: data.orderItems
        });
        let optionEl = new Option('Tanlang...', '', true, true);
        selectEl.appendChild(optionEl);
        
        tBodyEl.appendChild(trEl);
    }
    
    return tableEl;
}

function call_pnotify(status,text,time=2000) {
    PNotify.defaults.stack = {
      dir1: 'down',
      dir2: 'right',
      firstpos1: 25,
      firstpos2: 25,
      spacing1: 36,
      spacing2: 36,
      push: "bottom",
      context: window && document.body
    };
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = time;
            PNotify.alert({
                text: text,
                type:'success'
            });
            break;    
        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = time;
            PNotify.alert({
                text: text,
                type:'error'
            });
            break;
    }
}
JS;
$this->registerJs($gridViewJs);

$css = <<<CSS
.has-error .select2-selection {
    border-color: rgb(185, 74, 72) !important;
}
CSS;
$this->registerCss($css);



