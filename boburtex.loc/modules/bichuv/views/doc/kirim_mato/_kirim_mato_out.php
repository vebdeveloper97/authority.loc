<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t', 2);
?>
<?php if($t == 2):?>
<div class="kirim-mato-box">
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INCOMING])->label(false) ?>
            <?= $form->field($model, 'type')->hiddenInput(['value' => $t])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app', 'Sana')],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
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
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(), [
                'data' => $model->getMusteries()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_department')->widget(Select2::className(), [
                'data' => $model->getDepartmentsBelongTo(),
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_responsible')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_employee')->widget(Select2::className(), [
                'data' => $model->getEmployees()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p class="text-yellow">
                <i class="fa fa-info-circle"></i>&nbsp;
                <i><b>F9</b> - <small><?= Yii::t('app','Yangi qator qo\'shish')?></small></i>&nbsp;&nbsp;&nbsp;
                <i><b>F8</b> - <small><?= Yii::t('app','So\'nggi qatorni o\'chirish')?></small></i>
            </p>
        </div>
    </div>
    <div class="document-items">
        <?= CustomTabularInput::widget([
            'id' => 'documentitems_id',
            'form' => $form,
            'models' => $models,
            'theme' => 'bs',
            'min' => 1,
            'showFooter' => true,
            'attributes' => [
                [
                    'id' => 'footer_mijoz_party_no',
                    'value' => null
                ],
                [
                    'id' => 'footer_mato',
                    'value' => null
                ],
                [
                    'id' => 'footer_ne',
                    'value' => null
                ],
                [
                    'id' => 'footer_thread',
                    'value' => null
                ],
                [
                    'id' => 'footer_pus_fine',
                    'value' => null
                ],
                [
                    'id' => 'footer_color',
                    'value' => null
                ],
                [
                    'id' => 'footer_consist',
                    'value' => null
                ],
                [
                    'id' => 'footer_finish_en',
                    'value' => null
                ],
                [
                    'id' => 'footer_finish_gramaj',
                    'value' => null
                ],
                [
                    'id' => 'footer_model',
                    'value' => null
                ],
                [
                    'id' => 'footer_roll_count',
                    'value' => 0
                ],
                [
                    'id' => 'footer_quantity',
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
            'cloneButton' => true,
            'columns' => [
                [
                    'name' => 'mijoz_part',
                    'title' => Yii::t('app','Musteri P/N'),
                    'value' => function($model){
                        return $model->musteri_party_no;
                    },
                    'options' => [
                         'class' => 'musteri-party-no'
                    ]
                ],
                [
                    'name' => 'rm_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    'options' => [
                            'data' => $model->getRMList('mato')
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'ne_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Ne Nomi'),
                    'options' => [
                        'data' => $model->getRMList('ne')
                    ],
                    'headerOptions' => [
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],

                [
                    'name' => 'thread_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Ip turi'),
                    'options' => [
                        'data' => $model->getRMList('thread')
                    ],
                    'headerOptions' => [
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'pus_fine_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Pus/Fine'),
                    'options' => [
                        'data' => $model->getRMList('pf')
                    ],
                    'headerOptions' => [
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'c_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Rangi'),
                    'options' => [
                        'data' => $model->getRMList('color')
                    ],
                    'headerOptions' => [
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'thread_consist',
                    'title' => Yii::t('app','Tarkibi')
                ],
                [
                    'name' => 'en',
                    'title' => Yii::t('app', 'En'),
                    'options' => [
                        'class' => 'doc-qty tabular-cell-mato',
                    ],
                    'headerOptions' => [
                        'class' => 'summa-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'gramaj',
                    'title' => Yii::t('app', 'Gramaj'),
                    'options' => [
                        'class' => 'doc-qty tabular-cell-mato',
                    ],
                    'headerOptions' => [
                        'class' => 'summa-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'model',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Model'),
                    'options' => [
                        'data' => $model->getRMList('model')
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'roll_count',
                    'title' => Yii::t('app', 'Rulon soni'),
                    'options' => [
                        'class' => 'tabular-cell-mato roll-count',
                    ],
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'roll_weight',
                    'title' => Yii::t('app', 'Miqdori(kg)'),
                    'options' => [
                        'class' => 'tabular-cell-mato rm-fact',
                    ],
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],

            ]
        ]);
        ?>
    </div>
</div>
<?php endif;?>
