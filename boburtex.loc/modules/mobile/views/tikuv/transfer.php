<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\hr\models\HrDepartments;
use app\modules\tikuv\models\TikuvDocItems;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\tikuv\models\TikuvDoc */
/* @var $models TikuvDocItems */
/* @var $nextProcess \app\modules\mobile\models\MobileProcess */
$this->title = Yii::t('app', '{type}', ['type' => $this->context->mobileTable['name']]);
$this->params['breadcrumbs'][] = [
    'label' => '<i class="fa fa-2x fa-chevron-circle-left"></i>',
    'url' => ['/mobile/tikuv/conveyor-out', 'slug' => $this->context->slug],
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mobile-tikuv-transfer">

    <h4><strong><?= '№' . Html::encode($model->doc_number) . "($model->reg_date)"; ?></strong></h4>
    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','From department')?></strong>: <?= $model->fromHrDepartment->name ?></td>
            <td><strong><?= Yii::t('app','To department')?></strong>: <?= $model->toHrDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Responsible person')?></strong>: <?= $model->fromHrEmployee->fish ?></td>
            <td><strong><?= Yii::t('app','Responsible person')?></strong>: <?= $model->toHrEmployee->fish ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Process')?></strong>: </td>
            <td><?= $model->mobileProcess->name; ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Tikuv Konveyer')?></strong>: </td>
            <td><?= $model->mobileTable->name; ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Next process')?></strong>: </td>
            <td><?= $nextProcess['name']; ?></td>
        </tr>
    </table>

    <?php $form = ActiveForm::begin([
        'id' => 'tikuv_doc_form'
    ])?>
    <?= $form->field($model, 'party_no')->hiddenInput()->label(false) ?>

    <div class="box">
        <div class="box-body">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control" id="nastelNo" placeholder="<?= Yii::t('app', 'Nastel No') ?>">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="searchBtn"><i class="fa fa-search"></i></button>
                    <button class="btn btn-info" type="button"><i class="fa fa-qrcode"></i></button>
                </span>
            </div>
        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <?= CustomTabularInput::widget([
                'id' => 'tikuv_doc_items',
                'models' => [],
                'form' => $form,
                'modelClass' => TikuvDocItems::class,
                'allowEmptyList' => true,
                'addButtonOptions' => [
                    'class' => 'hide'
                ],
                'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                'columns' => [
                    [
                        'name' => 'entity_type',
                        'type' => 'hiddenInput',
                    ],
                    [
                        'name' => 'size_id',
                        'type' => 'hiddenInput',
                    ],
                    [
                        'name' => 'nastel_party_no',
                        'title' => Yii::t('app', 'Nastel No'),
                        'options' => [
                            'readonly' => true,
//                            'style' => 'width: 4em'
                        ],
                    ],
                    [
                        'name' => 'size_name',
                        'options' => [
                            'readonly' => true,
                        ],
                        'title' => Yii::t('app', 'Size'),
                    ],
                    [
                        'name' => 'quantity',
                        'title' => Yii::t('app', 'Quantity')
                    ],
                ]
            ])?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?=Html::submitButton(Yii::t('app', 'Save'), [
                'class' => 'btn btn-lg btn-success btn-block',
            ])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
const nastelSearchInput = document.getElementById('nastelNo');
const nastelSearchBtn = document.getElementById('searchBtn');
const tikuvDocItemsTabInput = document.getElementById('tikuv_doc_items');
const tikuvDocForm = document.getElementById('tikuv_doc_form');
const searchUrl = '/uz/mobile/tikuv/{$this->context->slug}/search-remain';

nastelSearchBtn.addEventListener('click', nastelSearchBtnHandler);
$(tikuvDocForm).on('beforeSubmit', beforeSubmitHandler);

function nastelSearchBtnHandler(e) {
    let nastelNoValue = nastelSearchInput.value;
    
    // bo'sh bo'lsa qidirishni bekor qilish
    if (!nastelNoValue) {
        return;
    }    
    
    fetch(searchUrl + '?nastelNo=' + nastelNoValue, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
            'X-CSRF-Token': yii.getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
      .then(response => response.json())
      .then(json => {
          console.log('-----------------RESPONSE------------------')
          console.dir(json);
          
          if (json.success) {
            PNotify.success({
                title: json.message
            });
            const tikuvDocPartyNoInput = document.getElementById('tikuvdoc-party_no');
            tikuvDocPartyNoInput.value = nastelNoValue;
            // items larni to'ldirish
            $(tikuvDocItemsTabInput).multipleInput('clear');
            for (let item of json.items) {
                $(tikuvDocItemsTabInput).multipleInput('add', {
                    entity_type: item.entity_type,
                    size_id: item.size_id,
                    first: item.nastel_no,
                    second: item.size.name,
                    third: parseInt(item.inventory)
                })
            }
            
          } else {
                PNotify.error({
                  title: json.message
                });
          }
      });
}

function beforeSubmitHandler(e) {
    let flag = false;
    
    // oldin itemslar borligini tekshirish
//    let countItems = tikuvDocItemsTabInput.querySelectorAll('table body tr').length
//    console.log(countItems)
//    if (countItems === 0) {
//        PNotify.notice({/*
//          title: 'Regular Notice',*/
//          text: "Oldin jo'natadigan mahsulotlarni tanlang",        
//          addClass: 'notice-style',
//          delay: 2000,
//          mouseReset: false,
//          closerHover: false,
//            modules: new Map([
//                ...PNotify.defaultModules,
//                [PNotifyMobile, {}]
//            ])
//        });
//        return false;
//    }
    
    flag = confirm('Siz rostdan ham shu mahsulotlarni jo\'natmoqchimisiz?');
    
    return flag;
}
JS;

$this->registerJs($js);

