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
$t = Yii::$app->request->get('t',1);
?>
<?php if($t == 1):?>
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
                'data' => $model->getMusteries('SAMO')
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
                <i><b>F8</b> -
                    <small><?= Yii::t('app', 'So\'nggi qatorni o\'chirish') ?></small>
                </i>
            </p>
        </div>
        <div class="col-md-6">
            <?= Html::textInput('barcode', null, ['id' => 'barcodeInput', 'autofocus' => true, 'class' => 'pull-right col-md-6 customCard']) ?>
            <?= Html::label(Yii::t('app', 'Partiya No'), 'barcodeInput', ['class' => 'pull-right mr2 text-primary']) ?>
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
                    'id' => 'footer_entity_id',
                    'value' => Yii::t('app', 'Jami')
                ],
                [
                    'id' => 'footer_model',
                    'value' => null
                ],
                [
                    'id' => 'footer_party',
                    'value' => null
                ],
                [
                    'id' => 'footer_roll_count',
                    'value' => 0
                ],
                [
                    'id' => 'footer_document_quantity',
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
            'addButtonOptions' => [
                'class' => 'hidden',
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'type' => 'hiddenInput',
                    'name' => 'entity_type',
                    'defaultValue' => 2
                ],

                [
                    'type' => 'hiddenInput',
                    'name' => 'rm_id',
                    'options' => [
                        'class' => 'rm-id',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->rm_id;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'ne_id',
                    'options' => [
                        'class' => 'ne-id',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->ne_id;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'pus_fine_id',
                    'options' => [
                        'class' => 'pus-fine-id',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->pus_fine_id;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'thread_id',
                    'options' => [
                        'class' => 'thread-id',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->thread_id;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'c_id',
                    'options' => [
                        'class' => 'c-id',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->c_id;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'en',
                    'options' => [
                        'class' => 'en',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->en;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'gramaj',
                    'options' => [
                        'class' => 'gramaj',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->gramaj;
                    }
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'is_accessory',
                    'defaultValue' => 1,
                    'options' => [
                        'class' => 'is-accessory',
                    ],
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'price_sum',
                    'defaultValue' => 0.05
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'bss_id',
                    'options' => [
                        'class' => 'bss-id',
                    ],
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'price_usd',
                    'defaultValue' => 0.05
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'document_quantity',
                    'defaultValue' => 0,
                    'options' => [
                        'class' => 'document-quantity',
                    ],
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'musteri_party_no',
                    'options' => [
                        'class' => 'musteri-party-no',
                    ],
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'party_no',
                    'options' => [
                        'class' => 'party-no',
                    ],
                ],
                [
                    'name' => 'entity_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    'options' => [
                        'data' => $model->getItems(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Placeholder Select'),
                            'multiple' => false,
                            'class' => 'mato-kirim-select2',
                            'options' => []
                        ],
                        'pluginOptions' => [
                            'minimumInputLength' => 400,
                        ],
                        'pluginEvents' => [
                            "select2:open" => "function() { $('.select2-dropdown').remove(); }",
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 30%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'type' => Select2::className(),
                    'name' => 'model_id',
                    'title' => Yii::t('app', 'Model'),
                    'options' => [
                        'data' => $model->getProductModels(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Placeholder Select'),
                            'multiple' => false,
                            'class' => 'model-id',
                            'options' => []
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 15%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'party',
                    'title' => Yii::t('app', 'Part./Mijoz No'),
                    'options' => [
                        'disabled' => true,
                        'class' => 'rm-party tabular-cell-mato',
                    ],
                    'value' => function($model){
                        return $model->bichuvSubDocItems[0]->party_no.'/'.$model->bichuvSubDocItems[0]->musteri_party_no;
                    },
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'roll_count',
                    'title' => Yii::t('app', 'Rulon soni'),
                    'options' => [
                        'class' => 'roll-count tabular-cell-mato',
                    ],
                    'headerOptions' => [
                        'class' => 'incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'document_qty',
                    'title' => Yii::t('app', 'Miqdori (Hujjat)(kg)'),
                    'options' => [
                        'disabled' => true,
                        'class' => 'doc-qty tabular-cell-mato',
                        'field' => 'document_quantity'
                    ],
                    'value' => function($model){
                        return $model->document_quantity;
                    },
                    'headerOptions' => [
                        'class' => 'summa-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Miqdori (Fakt)(kg)'),
                    'options' => [
                        'class' => 'rm-fact tabular-cell-mato',
                    ],
                    'headerOptions' => [
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
            ]
        ]);
        ?>
    </div>
</div>
<?php endif;?>