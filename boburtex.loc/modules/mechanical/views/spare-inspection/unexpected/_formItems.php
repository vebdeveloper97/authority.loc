<?php
/** @var $model app\modules\mechanical\models\SpareInspectionItems **/

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\helpers\Html as KHtml;
use yii\helpers\Url;
?>
<div class="box box-info box-solid">
    <div class="box-header">
        <?=Yii::t('app', 'Ro\'yhat')?>
    </div>
    <div class="box-body">
        <?= CustomTabularInput::widget([
            'id' => 'spl_id',
            'form' => $form,
            'models' => $models,
            'theme' => 'bs',
            'min' => 0,
            'max' => 100,
            'addButtonPosition' => CustomMultipleInput::POS_HEADER,
            'addButtonOptions' => [
                'class' => 'btn btn-success',
            ],
            'columns' => [
                [
                    'name' => 'spare_item_balance_id',
                    'type' => 'hiddenInput',
                    'options' => [
                            'class' => 'spare-item-balance'
                    ]
                ],
                [
                    'name' => 'spare_item_id',
                    'title' => Yii::t('app', 'Spare Item'),
                    'type' => Select2::class,
                    'options' => [
                        'data' => \app\modules\bichuv\models\SpareItem::getSpareListNotByTypeMap(2),
                        'pluginOptions' => [
                                'placeholder' => Yii::t('app','Select...')
                        ],
                        'options' => [
                            'class' => 'spare-item'
                        ]
                    ]
                ],
                [
                    'name' => 'spare_remain',
                    'title' => Yii::t('app','Remain'),
                    'options' => [
                        'readonly' => true,
                        'class' => 'spare-remain'
                    ],
                    'value' => function($model){
                        return (!empty($model->spare_item_balance_id)) ? number_format($model->spareItemBalance->inventory,0,'','') : "";
                    }
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Quantity'),
                    'options' => [
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'quantity',
                    ],
                    'value' => function($model){
                        return (!empty($model->quantity)) ? number_format($model->quantity,0,'','') : "";
                    }
                ],
                [
                    'name' => 'spare_control_list_id',
                    'type' => Select2::class,
                    'title' => Yii::t('app', 'Tekshiruv turlari'),
                    'options' => [
                       'data' => \app\modules\mechanical\models\SpareControlList::getListMap(),
                       'options' => [
                           'class' => 'spare-control-list',
                       ],
                       'addon' => [
                           'append' => [
                               'content' => KHtml::button(KHtml::icon('plus'), [
                                   'class' => 'showModalButton btn btn-success btn-sm toquv_raw_materials_id',
                                   'style' => 'padding:2px 5px; font-size: 8px',
                                   'title' => Yii::t('app', 'Create'),
                                   'value' => Url::to(['/mechanical/spare-control-list/create']),
                                   'data-toggle' => "modal",
                                   'data-form-id' => 'form_id',
                                   'data-input-name' => 'spareinspectionitems-0-spare_control_list_id'
                               ]),
                               'asButton' => true
                           ],
                       ],
                       'pluginOptions' => [
                           'escapeMarkup' => new JsExpression(
                               "function (markup) { 
                                                    return markup;
                                                }"
                           ),
                           'templateResult' => new JsExpression(
                               "function(data) {
                                                       return data.text;
                                                 }"
                           ),
                           'templateSelection' => new JsExpression(
                               "function (data) { return data.text; }"
                           ),
                       ],
                   ],
                    'headerOptions' => [
                        'width' => '30%'
                    ]
                ],
                [
                    'name' => 'add_info',
                    'title' => Yii::t('app','Add info')
                ],
            ]
        ]);
        ?>
    </div>

</div>

<?php
$css = <<< CSS
.s2-input-group .input-group-btn{
 width: 20px!important;
}
CSS;
$this->registerCss($css);
$url = Url::to(['get-spare-item-remain','slug' => $this->context->slug]);
?>
<?php
$js = <<< JS
    const __body = $('body');
      
    /** Ajax orqali spare item balance remain olib kelish*/ 
    __body.delegate('.spare-item','change',function(e) {
      e.preventDefault();
      const __this = $(this);
      const  __spareItem = __this.val();
      $.ajax({
        url: '{$url}',
        type: 'POST',
        data: {spareItem: __spareItem},
        success: function(response) {
          const __parentTr = __this.parents('tr.multiple-input-list__item');
          if(response.status){
              const __item = response.item;
              __parentTr.find('input.spare-item-balance').val(__item.id);
              __parentTr.find('input.spare-remain').val(__item.inventory);
              call_pnotify('success','Success');
          }else{
               __parentTr.find('input.spare-item-balance').val('');
               __parentTr.find('input.spare-remain').val('');
              call_pnotify('fail','Ma\'lumot topilmadi')
          }
        }
      });
    });
    
    /** Quantity change bo'lganda ombordagi miqdordan ortib ketmasigi uchun*/
    __body.delegate('.quantity','keyup',function() {
      const __this = $(this);
      const __parentTr = __this.parents('tr.multiple-input-list__item');
      let __spareRemain = __parentTr.find('input.spare-remain').val();
      if(parseFloat(__this.val()) > __spareRemain){
          call_pnotify('fail','Omborda bunday miqdorda maxsulot mavjud emas');
          __this.val(__spareRemain);
      }
    });
    
    /** Ekranga xabar chiqarish uchun **/
    function call_pnotify(status,text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;
    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
    
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
?>
