<?php
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;
use kartik\select2\Select2;
?>
<?= CustomTabularInput::widget([
    'id' => 'documentitems_id',
    'form' => $form,
    'models' => $models,
    'theme' => 'bs',
    'showFooter' => true,
    'attributes' => [
        [
            'id' => 'footer_size_id',
            'value' => Yii::t('app', 'Jami')
        ],
        [
            'id' => 'footer_size_name',
            'value' => null
        ],
        [
            'id' => 'footer_remain',
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
        'class' => 'btn btn-success hidden',
    ],
    'cloneButton' => false,
    'columns' => [
        [
            'name' => 'model_id',
            'type' => 'hiddenInput',
            'options' => [
                'class' => 'model-id',
            ],
        ],
        [
            'name' => 'size_id',
            'type' => 'hiddenInput',
            'options' => [
                'class' => 'size-id',
            ],
        ],
        [
            'name' => 'work_weight',
            'type' => 'hiddenInput',
            'options' => [
                'class' => 'work-weight',
            ],
        ],
        [
            'name' => 'bichuv_given_roll_id',
            'type' => 'hiddenInput',
            'options' => [
                'class' => 'given-roll-id',
            ],
        ],
        [
            'name' => 'nastel_party',
            'title' => Yii::t('app', "Nastel Party"),
            'options' => [
                'readonly' => true,
                'class' => 'tabular-cell-mato nastel-party',
            ],
            'value' => function ($models) {
                return $models->nastel_party;
            },
            'headerOptions' => []
        ],
        [
            'name' => 'sizeName',
            'title' => Yii::t('app', 'Size'),
            'options' => [
                'disabled' => true,
                'class' => 'model-size',
            ],
            'value' => function ($models) {
                return $models->size->name;
            },
            'headerOptions' => [
                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
            ]
        ],
        [
            'name' => 'remain',
            'title' => Yii::t('app', 'Qoldiq (dona)'),
            'options' => [
                'class' => 'tabular-cell-mato model-remain',
                'disabled' => true
            ],
            'value' => function ($models) {
                return $models->getRemainSliceQuantityByPandNItemBalance();
            }
        ],
        [
            'name' => 'quantity',
            'title' => Yii::t('app', 'Miqdori (dona)'),
            'options' => [
                'class' => 'tabular-cell-mato model-quantity',
                'type' => 'number'
            ],
            'value' => function ($models) {
                return number_format($models->quantity, 0);
            }
        ],
        [
            'name' => 'invalid_quantity',
            'title' => Yii::t('app', 'Yaroqsiz miqdor (dona)'),
            'options' => [
                'class' => 'invalid-quantity',
                'type' => 'number',
                'min' => 0
            ],
            'value' => function ($models) {
                return number_format($models->invalid_quantity, 0);
            }
        ],
        [
            'name' => 'add_info',
            'title' => Yii::t('app', 'Izoh'),
            'options' => [
                'class' => 'add-info',
                'readonly' => true
            ],
        ],
        [
            'name' => 'model_var_print_id',
            'type' => Select2::class,
            'title' => Yii::t('app', 'Pechat '),
            'options' => [
                'data' => $printList,
                'options' => [
                    'class' => 'model-var-print',
                ],
                'pluginOptions' => [
                    'placeholder' => ''
                ]
            ],
        ],
    ]
]); ?>
