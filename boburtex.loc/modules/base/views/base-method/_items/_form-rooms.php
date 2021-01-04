<?php
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethod */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelsRoom \app\modules\base\models\BaseMethodSizeItems */
/* @var $modelHouse \app\modules\base\models\BaseMethodSizeItemsChilds */
/* @var $form \yii\widgets\ActiveForm */
/* @var $baseMethodSeam \app\modules\base\models\BaseMethodSeam */


use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\modules\base\models\BaseMethodSeam;
use kartik\helpers\Html as KHtml;
use yii\helpers\Url;
use app\modules\base\models\BaseMethodSizeItems;


?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-rooms',
    'widgetItem' => '.room-item',
    'limit' => 100,
    'min' => 1,
    'insertButton' => '.add-room',
    'deleteButton' => '.remove-room',
    'model' => $modelsRoom[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'description'
    ],
]); ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?=Yii::t('app', 'Base Method Seam')?></th>
            <th><?=Yii::t('app', 'Time')?></th>
            <?php if($modelHouse['status'] != BaseMethodSizeItems::STATUS_SAVED): ?>
            <th class="text-center">
                <button type="button" class="add-room btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
            </th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody class="container-rooms">
        <?php foreach ($modelsRoom as $indexRoom => $modelRoom): ?>
            <tr class="room-item">
                <td class="vcenter" style="width: 70%">
                    <?php
                    // necessary for update action.
                    if (! $modelRoom->isNewRecord) {
                        echo Html::activeHiddenInput($modelRoom, "[{$indexHouse}][{$indexRoom}{$time}]id");
                    }
                    ?>
                    <?php if($modelHouse['status'] != BaseMethodSizeItems::STATUS_SAVED): ?>
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]base_method_seam_id")->widget(\kartik\select2\Select2::class, [
                        'data' => ArrayHelper::map(BaseMethodSeam::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Base Method Seam'),
                            'class' => 'base-method-seam'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                        'addon' => [
                            'append' => [
                                'content' => KHtml::button(KHtml::icon('plus'), [
                                    'class' => 'showModalButton btn btn-success btn-sm base-method-seam',
                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                    'title' => Yii::t('app', 'Create'),
                                ]),
                                'asButton' => true
                            ]
                        ],
                    ])->label(false) ?>
                    <?php else: ?>
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]base_method_seam_id")->widget(\kartik\select2\Select2::class, [
                            'data' => ArrayHelper::map(BaseMethodSeam::find()->all(), 'id', 'name'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Base Method Seam'),
                                'class' => 'base-method-seam',
                                'readonly' => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label(false) ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($modelHouse['status'] != BaseMethodSizeItems::STATUS_SAVED): ?>
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]time")->label(false) ?>
                    <?php else: ?>
                        <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]time")->textInput(['readonly' => true])->label(false) ?>
                    <?php endif; ?>
                </td>
                <?php if($modelHouse['status'] != BaseMethodSizeItems::STATUS_SAVED): ?>
                    <td class="text-center vcenter" style="width: 90px;">
                        <button type="button" class="remove-room btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php DynamicFormWidget::end(); ?>
<?php
$url = Url::to(['base-method-seam/create']);
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalheader'],
    'options' => [
        'tabindex' => false,
    ],
    'size' => 'modal-lg',
    'id' => 'add_new_item1'
]);
?>
    <div id="modalContent">
        <div class="base-method-seam-form">
            <form class="form-variation">
                <label for="name"><?=Yii::t('app', 'Name')?></label>
                <input type="text" class="form-control name-method"  name="name">
                <hr>
                <span class="clicks btn btn-success btn-sm"><?=Yii::t('app', 'Create')?></span>
            </form>
        </div>
    </div>
<?php
yii\bootstrap\Modal::end();
$required = Yii::t('app', "Chok nomini kiriting");
$url = Url::to(['base-method-seam/create-ajax']);
$js = <<<JS
const modalForm = $('#add_new_item1');
let btn;
$('body').delegate('.showModalButton', 'click', function (e){
    btn = $(this);
    modalForm.modal('show');
});
$('.clicks').on('click', function (e){
    let name = $('.name-method').val();
    if(!name){
        alert("$required");
    }
    else{
        $.ajax({
            url: "$url",
            type: 'GET',
            data: {name: name},
            success: function (res){
                if(res.status){
                    modalForm.modal('hide');
                    let name = res.name==null?"":res.name;
                    let id = res.id==null?'':res.id;
                    let newOptions = new Option(name, id, true, true);
                    btn.parents('span.input-group-btn').prevAll('select.base-method-seam').append(newOptions);
                    $('.name-method').val('');
                    call_pnotify(res.status, res.message);
                }
                else{
                    call_pnotify(res.status, res.message);
                }
            }
        })
    }
})

    function call_pnotify(status,text) {
        switch (status) {
            case true:
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;    
            case false:
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 3000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
    
    $('.dynamicform_wrapper').on('afterInsert', function(e, item){
        $(item).find('.save-and-finish').hide();
    });
    
JS;
$this->registerJs($js, \yii\web\View::POS_END);
