<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\tree\TreeViewInput;
use app\modules\hr\models\HrDepartments;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;
/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareItemRelHrEmployee */
/* @var $form yii\widgets\ActiveForm */
?>

     <?php  $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'hr_employee_id')->widget(Select2::class, [
                        'data' => \app\modules\hr\models\HrEmployee::getListMap(),
                        'options' => ['placeholder' => Yii::t('app','Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'hr_department_id')->widget(TreeViewInput::class, [
                        'query' => HrDepartments::find()->addOrderBy('root, lft'),
                        'headingOptions' => ['label' => Yii::t('app', "Bo'lim tanlang")],
                        'fontAwesome' => true,
                        'asDropdown' => true,
                        'multiple' => false,
                        'rootOptions' => [
                            'label'=>'<i class="fa fa-list"></i>',  // custom root label
                            'class'=>'text-success'
                        ],
                        'dropdownConfig' => [
                            'input' => [
                                'placeholder' => Yii::t('app', 'Select...')
                            ]
                        ]
                    ])?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'spare_item_id')->widget(Select2::class, [
                        'data' => \app\modules\bichuv\models\SpareItem::getSpareListNotByTypeMap(3),
                        'options' => ['placeholder' => Yii::t('app','Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'hr_country_id')->widget(Select2::class, [
                        'data' => \app\modules\hr\models\HrCountry::getListMap(),
                        'options' => ['placeholder' => Yii::t('app','Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>

                <div class="col-lg-4">
                    <?=$form->field($model, 'manufacture_date')->widget(DatePicker::class, [
                        'options' => [
                            'autocomplete' => 'off',
                        ],
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?=$form->field($model, 'installed_date')->widget(DatePicker::class, [
                        'options' => [
                            'autocomplete' => 'off',
                        ],
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'inv_number')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
                </div>
            </div>
            <br>

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
                    'name' => 'spare_control_id',
                    'type' => Select2::class,
                    'title' => Yii::t('app', 'Tekshiruv turlari'),
                    'options' => [
                        'data' => \app\modules\mechanical\models\SpareControlList::getListMap(),
                         'options' => [
                             'placeholder' => Yii::t('app', 'Select...'),
                         ],
                    ],
                    'headerOptions' => [
                            'width' => '50%'
                    ]
                ],
                [
                    'name' => 'interval_control_date',
                    'title' => Yii::t('app', 'Qancha vaqt oralig\'ida'),
                    'value' => function($model){
                        return (!empty($model->interval_control_date)) ? number_format($model->interval_control_date,0,'',''): '';
                    },
                    'options' => [
                        'type' => 'number',
                    ],
                ],
                [
                    'name' => 'control_date_type',
                    'type' => 'radioList',
                    'items' => $model->getDateTypeList(),
                    'title' => Yii::t('app', 'Turi'),
                    'options' => [
                            'class' => 'custom-radio-list'
                    ],
                ],
            ]
        ]);
        ?>
    </div>

</div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>
<?php
$css = <<< CSS
.custom-radio-list{
    width:100%;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
}
CSS;

$this->registerCss($css);
?>
