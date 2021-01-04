<?php

use app\modules\admin\models\UsersHrDepartments;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use kartik\tree\TreeViewInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvSliceItems */

$this->title = Yii::t('app', 'Update') . ': ' . Yii::t('app','№{number} - {date}',[
        'number' => $model->doc_number,
        'date' => date('d.m.Y', strtotime($model->reg_date)),
    ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Preparation') . ' (' . Yii::t('app', 'Accept slice') . ')', 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = \yii\widgets\ActiveForm::begin() ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput([
                'maxlength' => true,
                'disabled' => true
            ]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INSIDE])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => [
                    'placeholder' => Yii::t('app', 'Sana'),
                    'disabled' => true
                ],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'language' => 'ru',
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'from_hr_department')->widget(TreeViewInput::class, [
//                'id' => 'tree-to_department',
                'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::OWN_DEPARTMENT_TYPE),
                'headingOptions' => ['label' => Yii::t('app', "From department")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => ['disabled' => true],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...'),
                    ]
                ]
            ]);?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_hr_department')->widget(TreeViewInput::class, [
//                'id' => 'tree-to_department',
                'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE),
                'headingOptions' => ['label' => Yii::t('app', "To department")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => ['disabled' => true],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...'),
                    ]
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'from_hr_employee')->widget(Select2::className(), [
                'disabled' => true,
                'data' => HrEmployee::getListMap()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_hr_employee')->widget(Select2::className(), [
                    'disabled' => true,
                    'data' => HrEmployee::getListMap()
                ]);?>
        </div>
    </div>

<?php
// document items lari uchun model ni olish uchun
$sliceModels = \app\components\ViewHelper::indexBy($model->getSliceMovingViewOld($model->id), 'id');
?>

    <div class="document-items">
    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $models,
        'theme' => 'bs',
        'showFooter' => true,
        'attributes' => [
            [
                'id' => 'footer_entity_id',
                'value' => Yii::t('app', 'Jami')
            ],
            [
                'id' => 'footer_model_id',
                'value' => null
            ],
            [
                'id' => 'footer_nastel_no',
                'value' => null
            ],
            [
                'id' => 'footer_quantity',
                'value' => 0
            ],
            [
                'id' => 'footer_fact_quantity',
                'value' => 0
            ],
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 100,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success hidden',
        ],
        'removeButtonOptions' => [
            'class' => 'btn btn-danger hidden',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'name' => 'id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'nastel_no',
                'title' => Yii::t('app', "Nastel Party"),
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell-mato nastel-party',
                ],
                'value' => function ($model) {
                    return $model->nastel_party;
                },
                'headerOptions' => []
            ],
            [
                'name' => 'model_name',
                'title' => Yii::t('app', 'Model'),
                'options' => [
                    'disabled' => true,
                    'class' => 'model-name',
                ],
                'value' => function ($model) use ($sliceModels){
                    return $sliceModels[$model->id]['model'];
                }
            ],
            [
                'name' => 'sizeName',
                'title' => Yii::t('app', 'Size'),
                'options' => [
                    'disabled' => true,
                    'class' => 'roll-size',
                ],
                'value' => function ($model) {
                    return $model->size->name;
                },
                'headerOptions' => [
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Quantity (piece)'),
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell-mato roll-fact',
                ],
                'value' => function ($model) {
                    return number_format($model->quantity, 0,'.','');
                }
            ],
            [
                'name' => 'fact_quantity',
                'title' => Yii::t('app', 'Fact quantity (piece)'),
                'options' => [
                    'class' => 'tabular-cell-fact-quantity fact-quantity',
                ],
                'value' => function ($model) {
                    return empty($model->fact_quantity) ? number_format($model->quantity, 0,'.','') : $model->fact_quantity;
                },
            ],
            [
                'name' => 'add_info',
                'title' => Yii::t('app', 'Add info'),
            ],
        ]
    ]); ?>
</div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
            </div>
        </div>
    </div>

<?php \yii\widgets\ActiveForm::end() ?>
<?php

$js = <<<JS
    // fact_quantity o'zgarishga listener
    $('body').delegate('.tabular-cell-fact-quantity', 'keyup blur change', function (e) {
        console.log('ok');
        calculateSum('#footer_fact_quantity', '.tabular-cell-fact-quantity');
    });

    function calculateSum(id, className) {
        let rmParty = $('#documentitems_id table tbody tr').find(className);
        console.dir(rmParty)
        if (rmParty) {
            let totalFactQuantity = 0;
            rmParty.each(function (key, item) {
                if ($(item).val()) {
                    totalFactQuantity += parseInt($(item).val());
                }
            });
            $(id).html(totalFactQuantity);
            $(id).attr('data-total', totalFactQuantity);
        }
    }
JS;

$this->registerJs($js);


$css = <<< CSS
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}
CSS;
$this->registerCss($css);