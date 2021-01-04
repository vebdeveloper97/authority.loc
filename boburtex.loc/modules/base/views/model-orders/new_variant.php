<?php
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $models app\modules\base\models\ModelOrdersItems[] */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelsSize \app\modules\base\models\ModelOrdersItemsSize */
/* @var $modelsAcs \app\modules\base\models\ModelOrdersItemsAcs */
/* @var $modelsToquvAcs \app\modules\base\models\ModelOrdersItemsToquvAcs*/
/* @var $modelsVar \app\modules\base\models\ModelOrdersItemsVariations*/
/* @var $modelsMaterial \app\modules\base\models\ModelOrdersItemsMaterial */
/* @var $modelsPechat \app\modules\base\models\ModelOrdersItemsPechat */
/* @var $attachmentAllOldImages array */
/* @var $modelsNaqsh \app\modules\base\models\ModelOrdersNaqsh */
/* @var $modelsVariations ModelsVariations */
/* @var $variants \yii\helpers\ArrayHelper*/
/* @var $old_images array */
/* @var $pechat_images \app\modules\base\models\ModelOrdersItemsPechat*/
/* @var $naqsh_images \app\modules\base\models\ModelOrdersNaqsh*/

use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\toquv\models\ToquvPusFine;
use app\modules\toquv\models\ToquvRawMaterials;
use app\modules\wms\models\WmsDesen;
use app\widgets\helpers\Script;
use kartik\helpers\Html as KHtml;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use app\modules\base\models\BaseDetailLists;

$urlRemain = Url::to('ajax-models');
$url = Url::to('get-size-ajax');
$url_size = Url::to(['model-orders/size']);
?>
    <div class="model-orders-form">
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'musteri_id')->widget(Select2::classname(),
                [
                    'data' => $model->musteriList,
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Kontragent tanlang'),
                        ''
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'size' => Select2::SIZE_SMALL,
                    'addon' => [
                        'append' => [
                            'content' => KHtml::button(KHtml::icon('plus'), [
                                'class' => 'showModalButton3 btn btn-success btn-sm musteri',
                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                'title' => Yii::t('app', 'Create'),
                                'value' => Url::to(['musteri/create', 'id' => $models->models_list_id]),
                                'data-toggle' => "modal",
                                'data-form-id' => 'musteriForm',
                                'data-input-name' => 'modelorders-musteri_id'
                            ]),
                            'asButton' => true
                        ]
                    ],
                ])->label(Yii::t('app', 'Buyurtmachi')); ?>
            <?= $form->field($model, 'doc_number')->hiddenInput(['maxlength' => true])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'responsible')->widget(Select2::classname(), ['data' => $model->usersList, 'language' => 'ru', 'options' => [
                'prompt' => Yii::t('app', 'Mas\'ul shaxslarni tanlang'),
                'multiple' => true,
            ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 3]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-responsive" >
                <tbody class="blocks_plan">
                <tr>
                    <td colspan="16">
                        <?= $form->field($models,'files')->widget(\app\components\KCFinderInputWidgetCustom::className(),[
                            'multiple' => true,
                            'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
                            'isMultipleValue' => true,
                            'id' => 'attachedImage',
                            'kcfBrowseOptions' => [
                                'langCode' => 'ru'
                            ],
                            'kcfOptions' => [
                                'uploadURL' =>  '/uploads',
                                'cookieDomain' => $_SERVER['SERVER_NAME'],
                                'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                'access' => [
                                    'files' => [
                                        'upload' => true,
                                        'delete' => true,
                                        'copy' => true,
                                        'move' => true,
                                        'rename' => true,
                                    ],
                                    'dirs' => [
                                        'create' => true,
                                        'delete' => true,
                                        'rename' => true,
                                    ],
                                ],
                                'thumbsDir' => 'thumbs',
                                'thumbWidth' => 150,
                                'thumbHeight' => 150,
                            ]
                        ])->label(false);?>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        <?=$form
                            ->field($models, 'models_list_id')
                            ->widget(Select2::class,
                                [
                                    'data' => $model->getArrayMapModel(ModelsList::class, 'id', 'model_items'),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Select...')
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ]
                                ]
                            )
                            ->label(Yii::t('app', 'Aynan shu model'))?>
                    </td>
                    <td colspan="8">
                        <?=$form->field($models, 'models_list_info')->textarea(['rows' => 1])?>
                    </td>
                </tr>
                </tbody>
                <tfoot class="blocks_plan">
                <tr>
                    <td colspan="16">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma variantlari")?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?=
                        $form->field($models,'model_var_id')
                            ->widget(
                                Select2::class,
                                [
                                    'data' => $modelsVariations,
                                    'toggleAllSettings' => [
                                        'unselectOptions' => ['class' => 'text-danger'],
                                    ],
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Select a models variations ...'),
                                    ],
                                    'pluginOptions' => [
                                        'width' => '230px',
                                        'allowClear' => true
                                    ],
                                    'size' => Select2::SIZE_SMALL,
                                    'addon' => [
                                        'append' => [
                                            'content' => KHtml::button(KHtml::icon('plus'), [
                                                'class' => 'showModalButton3 btn btn-success btn-sm model-var-id',
                                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                                'title' => Yii::t('app', 'Create'),
                                                'value' => Url::to(['models-variations/create']),
                                                'data-toggle' => "modal",
                                                'data-form-id' => 'models_variations_form',
                                                'data-input-name' => 'modelordersitems-model_var_id'
                                            ]),
                                            'asButton' => true
                                        ]
                                    ],
                                ]
                            );
                        ?>
                    </td>
                    <td colspan="3">
                        <?=$form->field($models, 'model_var_info')->textarea(['rows' => 1])?>
                    </td>
                    <td colspan="2">
                        <?=$form->field($models, 'load_date')->widget(\kartik\date\DatePicker::class,[
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'mm.dd.yyyy'
                            ]
                        ]);?>
                    </td>
                    <td colspan="2">
                        <?=$form->field($models, 'add_info')->textarea(['rows' =>1]);?>
                    </td>
                    <td colspan="2">
                        <?=$form->field($models, 'price')->label(Yii::t('app', 'Price').'<span style="color: orange; "> $</span>'); ?>
                    </td>
                    <td colspan="3">
                        <?=$form->field($models, 'price_add_info')?>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma miqdori")?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="16" style="margin: 0px;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="col-md-12">
                                    <?= $form->field($models, 'sum_item_qty',['template'=>"<div><div class='col-md-6 text-right'>{label}</div><div class='col-md-6 noPaddingLeft'>{input}</div></div>"])->textInput(['id'=>'sum_item_qty'])->label(Yii::t('app', 'Umumiy ish'));?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($models, 'size_collections_id',['template'=>"<div><div class='col-md-6 text-right'>{label}</div><div class='col-md-6 noPaddingLeft'>{input}</div></div>"])->widget(Select2::className(), [
                                        'data' => $model->sizeCollectionList,
                                        'language' => 'ru',
                                        'options' => [
                                            'class' => 'rm_size',
                                            'prompt' => Yii::t('app', 'Check size type'),
                                            'id' => 'size_collections_id',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'escapeMarkup' => new JsExpression(
                                                "function (markup) { 
                                                        return markup;
                                                    }"
                                            ),
                                        ],
                                    ])->label(Yii::t('app', 'Size Collection'));?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($models, 'assorti_count',['template'=>"<div><div class='col-md-6 text-right'>{label}</div><div class='col-md-6 noPaddingLeft'>{input}</div></div>"])->textInput(['id'=>'assorti_count'])->label(Yii::t('app', 'Assorti soni'));?>
                                </div>
                            </div>
                            <div class="col-md-8" id="size_item">
                                <div>
                                    <div style="width: 150px;padding-right: 3px;float: left;">
                                        <div class="form-group field-model_orders_size">
                                            <label>&nbsp</label>
                                            <span class="form-control text-center" value="Assorti soni"><?php echo Yii::t('app','Assorti soni')?></span>
                                            <hr style="margin: 0;">
                                            <span class="form-control text-center" value="O'lchamlar soni"><?php echo Yii::t('app','Olchamlar soni')?></span>
                                        </div>
                                    </div>
                                    <?php
                                    $sizes = $models->childSizeItem;
                                    if($sizes):
                                        $summ_size = 0;
                                        $summ_assorti = 0;
                                        foreach ($sizes as $key => $size):
                                            ?>
                                            <div style="width: 70px;padding-right: 3px;float: left;">
                                                <div class="form-group field-model_orders_size">
                                                    <label class="control-label text-center" style="width: 100%" for="model_orders_size_<?=$size['size_id']?>">
                                                        <?=$size['size']['name']?>
                                                    </label>
                                                    <input type="text" class="form-control number numberFormat input_assorti input_size_all" id="model_orders_size_<?=$size['size_id']?>" tabindex="1" data-input="input_size_<?=$size['size_id']?>" name="ModelOrdersItemsSize[<?=$key?>][assorti_count]" style="padding-left: 2px;" value="<?=$size['assorti_count']?>">
                                                    <hr style="margin: 0;">
                                                    <input type="text" class="form-control number numberFormat input_size input_size_all" tabindex="2" id="input_size_<?=$size['size_id']?>" name="ModelOrdersItemsSize[<?=$key?>][count]" style="padding-left: 2px;" value="<?=$size['count']?>">
                                                    <input type="hidden" name="ModelOrdersItemsSize[<?=$key?>][size_id]" style="padding-left: 2px;" value="<?=$size['size_id']?>">
                                                </div>
                                            </div>
                                            <?php
                                            $summ_assorti += $size['assorti_count'];
                                            $summ_size += $size['count'];
                                        endforeach;
                                        ?>
                                        <div style="width: 100px; padding-right: 3px; float: left;text-align:center;">
                                            <div class="form-group field-model_orders_size">
                                                <label><?php echo Yii::t('app','Jami')?></label>
                                                <span class="form-control text-center summ_input_assorti"><?=$summ_assorti?></span>
                                                <hr style="margin: 0;" />
                                                <span class="form-control text-center summ_input_size"><?=$summ_size?></span>
                                            </div>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma matosi")?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <?=CustomTabularInput::widget([
                            'id' => 'material_inputs',
                            'models' => $modelsMaterial,
                            'addButtonOptions' => [
                                'class' => 'btn-success btn',
                            ],
                            'removeButtonOptions' => [
                                'class' => 'btn-danger btn',
                            ],
                            'columns' => [
                                [
                                    'name'  => 'toquv_raw_materials_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => ToquvRawMaterials::getMaterialList(ToquvRawMaterials::MATO)['list'],
                                        'size' => Select2::SIZE_TINY,
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Material'),
                                            'class' => 'toquv_raw_materials'
                                        ],
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm toquv_raw_materials_id',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/toquv/toquv-raw-materials/create']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'toquv_raw_materials_form',
                                                    'data-input-name' => 'modelordersitemsmaterial-0-toquv_raw_materials_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'debug' => true,
                                            'width' => '300px',
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
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Material'),
                                ],
                                [
                                    'name'  => 'wms_color_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => \app\modules\wms\models\WmsColor::getMapList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Material color'),
                                            'class' => 'wms_color_id'
                                        ],
                                        'size' => Select2::SIZE_SMALL,
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm wms-color-id',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'wms_color_form',
                                                    'data-input-name' => 'modelordersitemsmaterial-0-wms_color_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'width' => '200px',
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Material color'),
                                ],
                                [
                                    'name' => 'wms_desen_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => WmsDesen::getMapList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Print'),
                                            'class' => 'wms_desen_id'
                                        ],
                                        'size' => Select2::SIZE_SMALL,
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm wms-desen-id',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/wms/wms-desen/create', 'type' => 'other_modal']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'wms_desen_form',
                                                    'data-input-name' => 'modelordersitemsmaterial-0-wms_desen_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'width' => '200px',
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Print'),
                                ],
                                [
                                    'name'  => 'pus_fine_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => ToquvPusFine::getList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Pus/Fine'),
                                            'class' => 'pus_fine'
                                        ],
                                    ],
                                    'title' => Yii::t('app', 'Pus/Fine'),
                                ],
                                [
                                    'name' => 'en',
                                    'title' => Yii::t('app', 'En'),
                                    'options' => [
                                        'style' => 'width: 10em',
                                    ],
                                ],
                                [
                                    'name' => 'gramaj',
                                    'title' => Yii::t('app', 'Gramaj'),
                                    'options' => [
                                        'style' => 'width: 10em',
                                    ],
                                ],
                                [
                                    'name' => 'add_info',
                                    'title' => Yii::t('app', 'Add Info'),
                                    'type' => 'textarea',
                                    'options' => [
                                        'rows' => 1
                                    ]
                                ]
                            ]
                        ])?>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma aksesuarlar royxati")?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <?=CustomTabularInput::widget([
                            'id' => 'ace_inputs',
                            'models' => $modelsAcs,
                            'addButtonOptions' => [
                                'class' => 'btn-success btn',
                            ],
                            'removeButtonOptions' => [
                                'class' => 'btn-danger btn',
                            ],
                            'columns' => [
                                [
                                    'name' => 'bichuv_acs_id',
                                    'type' => Select2::className(),
                                    'title' => Yii::t('app', 'Bichuv Acs'),
                                    'options' => [
                                        'pluginOptions' => [
                                            'width' => '250px',
                                            'allowClear' => true
                                        ],
                                        'data' => $model->getArrayMapModel(BichuvAcs::class, 'id', 'bichuv'),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Bichuv Aksessuar'),
                                            'class' => 'bichuv_acs',
                                        ],
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm bichuv_acs_id',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/bichuv/bichuv-acs/data-save']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'bichuv_acs_id_form',
                                                    'data-input-name' => 'modelordersitemsacs-0-bichuv_acs_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                    ],
                                    'columnOptions' => [
                                        'style' => 'width: 200px;',
                                    ]
                                ],
                                [
                                    'name' => 'qty',
                                    'title' => Yii::t('app', 'Qty')
                                ],
                                [
                                    'name' => 'add_info',
                                    'type' => 'textarea',
                                    'title' => Yii::t('app', 'Add Info'),
                                    'options' => [
                                        'rows' => 1,
                                    ]
                                ]
                            ]
                        ])?>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma to'quv aksessuarlari")?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="16">
                        <?=CustomTabularInput::widget([
                            'id' => 'toquv_acs_inputs',
                            'models' => $modelsToquvAcs,
                            'addButtonOptions' => [
                                'class' => 'btn-success btn',
                            ],
                            'removeButtonOptions' => [
                                'class' => 'btn-danger btn',
                            ],
                            'columns' => [
                                [
                                    'name' => 'toquv_raw_materials_id',
                                    'type' => Select2::className(),
                                    'title' => Yii::t('app', 'Toquv Aksessuar'),
                                    'headerOptions' => [
                                        'style' => 'width: 250px'
                                    ],
                                    'options' => [
                                        'data' => $model->getArrayMapModel(ToquvRawMaterials::class, 'id', 'toq_acc'),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Toquv Aksessuar'),
                                            'class' => 'toquv_acs'
                                        ],
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm model-toquv-acs',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/toquv/toquv-aksessuar/create']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'toquv_raw_materials_id_form',
                                                    'data-input-name' => 'modelordersitemstoquvacs-0-toquv_raw_materials_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                    ],
                                ],
                                [
                                    'name'  => 'wms_color_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => \app\modules\wms\models\WmsColor::getMapList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Material color'),
                                            'class' => 'wms_color_id'
                                        ],
                                        'size' => Select2::SIZE_SMALL,
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm wms-color-id',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'wms_color_form',
                                                    'data-input-name' => 'modelordersitemstoquvacs-0-wms_color_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'width' => '200px',
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Aksessuar rangi'),
                                ],
                                [
                                    'name' => 'wms_desen_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => WmsDesen::getMapList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Print'),
                                            'class' => 'wms_desen_id'
                                        ],
                                        'size' => Select2::SIZE_SMALL,
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm wms-desen-id',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['/wms/wms-desen/create', 'type' => 'other_modal']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'wms_desen_form',
                                                    'data-input-name' => 'modelordersitemstoquvacs-0-wms_desen_id'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'width' => '200px',
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Print'),
                                ],
                                [
                                    'name'  => 'pus_fine_id',
                                    'type' => Select2::className(),
                                    'options' => [
                                        'data' => ToquvPusFine::getList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Pus/Fine'),
                                            'class' => 'pus_fine',
                                            'width' => '200px'
                                        ],
                                    ],
                                    'title' => Yii::t('app', 'Pus/Fine'),
                                ],
                                [
                                    'name' => 'en',
                                    'title' => Yii::t('app', 'En'),
                                    'options' => [
                                        'style' => 'width: 10em',
                                    ],
                                ],
                                [
                                    'name' => 'gramaj',
                                    'title' => Yii::t('app', 'Gramaj'),
                                    'options' => [
                                        'style' => 'width: 10em',
                                    ],
                                ],
                                [
                                    'name' => 'count',
                                    'title' => Yii::t('app', 'Qty'),
                                    'options' => [
                                        'style' => 'width: 100px'
                                    ]
                                ],
                            ]
                        ])?>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma pechat")?></p>
                        <?=CustomTabularInput::widget([
                            'models' => $modelsPechat,
                            'addButtonOptions' => [
                                'class' => 'btn-success btn',
                            ],
                            'removeButtonOptions' => [
                                'class' => 'btn-danger btn',
                            ],
                            'columns' => [
                                [
                                    'name' => 'attachment_id',
                                    'type' => \app\components\KCFinderInputWidgetCustom::class,
                                    'title' => Yii::t('app', 'Add file'),
                                    'headerOptions' => [
                                        'style' => 'width: 100%'
                                    ],
                                    'options' => [
                                        'multiple' => true,
                                        'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
//                                                'isMultipleValue' => true,
//                                                'id' => 'attachedImage',
                                        'kcfBrowseOptions' => [
                                            'langCode' => 'ru'
                                        ],
                                        'kcfOptions' => [
                                            'uploadURL' =>  '/uploads',
                                            'cookieDomain' => $_SERVER['SERVER_NAME'],
                                            'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                            'access' => [
                                                'files' => [
                                                    'upload' => true,
                                                    'delete' => true,
                                                    'copy' => true,
                                                    'move' => true,
                                                    'rename' => true,
                                                ],
                                                'dirs' => [
                                                    'create' => true,
                                                    'delete' => true,
                                                    'rename' => true,
                                                ],
                                            ],
                                            'thumbsDir' => 'thumbs',
                                            'thumbWidth' => 150,
                                            'thumbHeight' => 150,
                                        ]
                                    ],
                                    'headerOptions' => [
                                        'width' => '40px',
                                    ],
                                ],
                                [
                                    'name' => 'name',
                                    'type' => Select2::class,
                                    'options' => [
                                        'data' => BaseDetailLists::getArrayList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', "Qo'llash qismi"),
                                            'class' => 'name_pechat'
                                        ],
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm details',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['base-detail-lists/create']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'detailsLists',
                                                    'data-input-name' => 'modelordersitemspechat-0-name'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'width' => '200px',
                                            'allowClear' => true,
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Qo\'llash qismi'),
                                ],
                                [
                                    'name' => 'width',
                                    'title' => Yii::t('app', 'Eni(sm)'),
                                    'options' => [
                                        'class' => 'width'
                                    ],
                                ],
                                [
                                    'name' => 'height',
                                    'title' => Yii::t('app', "Bo'yi(sm)"),
                                    'options' => [
                                        'class' => 'height'
                                    ],
                                ],
                            ]
                        ])?>
                    </td>
                    <td colspan="8">
                        <p class="block_head"><?=Yii::t('app',"Model buyurtma naqsh")?></p>
                        <?=CustomTabularInput::widget([
                            'models' => $modelsNaqsh,
                            'addButtonOptions' => [
                                'class' => 'btn-success btn',
                            ],
                            'removeButtonOptions' => [
                                'class' => 'btn-danger btn',
                            ],
                            'columns' => [
                                [
                                    'name' => 'attachment_id',
                                    'type' => \app\components\KCFinderInputWidgetCustom::class,
                                    'title' => Yii::t('app', 'Add file'),
                                    'options' => [
                                        'multiple' => true,
                                        'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
//                                                'isMultipleValue' => true,
//                                                'id' => 'attachedImage',
                                        'kcfBrowseOptions' => [
                                            'langCode' => 'ru'
                                        ],
                                        'kcfOptions' => [
                                            'uploadURL' =>  '/uploads',
                                            'cookieDomain' => $_SERVER['SERVER_NAME'],
                                            'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                            'access' => [
                                                'files' => [
                                                    'upload' => true,
                                                    'delete' => true,
                                                    'copy' => true,
                                                    'move' => true,
                                                    'rename' => true,
                                                ],
                                                'dirs' => [
                                                    'create' => true,
                                                    'delete' => true,
                                                    'rename' => true,
                                                ],
                                            ],
                                            'thumbsDir' => 'thumbs',
                                            'thumbWidth' => 150,
                                            'thumbHeight' => 150,
                                        ]
                                    ],
                                    'headerOptions' => [
                                        'width' => '40px',
                                    ],
                                ],
                                [
                                    'name' => 'name',
                                    'type' => Select2::class,
                                    'options' => [
                                        'data' => BaseDetailLists::getArrayList(),
                                        'options' => [
                                            'placeholder' => Yii::t('app', "Qo'llash qismi"),
                                            'class' => 'name_naqsh'
                                        ],
                                        'addon' => [
                                            'append' => [
                                                'content' => KHtml::button(KHtml::icon('plus'), [
                                                    'class' => 'showModalButton3 btn btn-success btn-sm details',
                                                    'style' => 'width:15px; padding:2px; font-size: 8px',
                                                    'title' => Yii::t('app', 'Create'),
                                                    'value' => Url::to(['base-detail-lists/create']),
                                                    'data-toggle' => "modal",
                                                    'data-form-id' => 'detailsLists',
                                                    'data-input-name' => 'modelordersnaqsh-0-name'
                                                ]),
                                                'asButton' => true
                                            ]
                                        ],
                                        'pluginOptions' => [
                                            'width' => '200px',
                                            'allowClear' => true,
                                        ]
                                    ],
                                    'title' => Yii::t('app', 'Qo\'llash qismi'),
                                ],

                                [
                                    'name' => 'width',
                                    'title' => Yii::t('app', 'Eni(sm)'),
                                    'options' => [
                                        'class' => 'width'
                                    ]
                                ],
                                [
                                    'name' => 'height',
                                    'title' => Yii::t('app', "Bo'yi(sm)"),
                                    'options' => [
                                        'class' => 'height'
                                    ],
                                ],
                            ]
                        ])?>
                    </td>
                </tr></tfoot>
            </table>

        </div>
        <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-sm removedSubmitButton'])?>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$this->registerCss("
        .select2-container .select2-selection--single .select2-selection__clear {
           position: auto!important;
         }
        .field-modelordersitems-model_var_id{
            width: 150px!important;
        }
        .blocks_plan{
        border-top: 25px solid #3c8dbc; border-left: 5px solid #3c8dbc;border-right: 5px solid #3c8dbc; border-bottom: 3px solid #3c8dbc;
 `        padding: 25px 10px; 
        min-height: 800px;
        border-collapse: separate;
        }
        .blocks_plan_small{
            border-top: 10px solid #3c8dbc; border-left: 2px solid #3c8dbc;border-right: 2px solid #3c8dbc; border-bottom: 2px solid #3c8dbc;
            padding: 10px 25px; 
            text-align:center;
        }
        html{
            zoom: 80%;
        }
      
           .block_head{
        background: #3c8dbc; margin: 0px; padding: 5px; font-weight: bold; color:white; text-align: center;
       }
    ")
?>

<?php

yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'size' => 'modal-lg',
    'id' => 'add_new_item_modal',
//    'size' => 'modal-sm',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
//    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();
$model_url = Url::to(['model-list-ajax']);
$variation_url = Url::to(['model-var-ajax']);
$infoError = Yii::t('app', 'The model option is not available');
$success = Yii::t('app', 'The model was selected');
$errorInfo = Yii::t('app','Model tanlanmadi!');
$errorMessage = Yii::t('app','Variant kelmadi');
$successMessage = Yii::t('app','Variant yaratildi');
$infoErrorRaw = Yii::t('app', 'Asosiy mato tanlanishi lozim');
$infoConfirm = Yii::t('app', 'Siz rostdan ham barcha andoza detallarini asosiy mato va asosiy rangalarga o\'zgartirmoqchimisiz?');
$model_variation = Url::to(['model-variation']);

?>
    <!-- Model tanlansa tabular formani to'ldirib berish -->
<?php Script::begin(); ?>
    <script>
        var pechat = 'create-pechat';
        var naqsh = 'create-naqsh';
        var NullVariant = 'ajax-variant-all-data';
        let lists = '';
        /** Update uchun ishlatiladi */
        if($('#modelordersitems-models_list_id').val().length != 0){
            window.onload = function(){
                let modelsListId = $('#modelordersitems-models_list_id').val();
                $('.model-var-id').attr('data-id', modelsListId);
                let btnValue = lists+'?list='+modelsListId;
                $('.model-var-id').attr('value', btnValue);

                /** Bichuv Acc uchun readonly qilish */
                $('.bichuv_acs').attr('readonly', true);
                $('.bichuv_acs_id').hide();
                $('#ace_inputs').find('.list-cell__button').hide();

                /** Toquv Acc uchun readonly qilish */
                $('.toquv_acs').attr('readonly', true);
                $('.model-toquv-acs').hide();
                $('#toquv_acs_inputs').find('.list-cell__button').hide();

                /** Materials uchun */
                $(".toquv_raw_materials").attr('readonly', 'true');
                $(".toquv_raw_materials_id").hide();
                $('#material_inputs').find('.list-cell__button').hide();
                /** Wms_Color */
                $('.wms_color_id').attr('readonly', true);
                $('button.wms-color-id').hide();

                /** wms Desen */
                $('select.wms_desen_id').attr('readonly', true);
                $('button.wms-desen-id').hide();
                $('#loading').hide();

                /** begin Pechat uchun */
                $('#w4').find('select').attr('readonly', true);
                $('#w4').find('input').attr('readonly', true);
                $('#w4').find('button').hide();
                $('#w4').find('.list-cell__button').hide();
                /** end Pechat uchun */

                /** begin Naqsh uchun */
                $('#w5').find('select').attr('readonly', true);
                $('#w5').find('input').attr('readonly', true);
                $('#w5').find('button').hide();
                $('#w5').find('.list-cell__button').hide();
                /** end Naqsh uchun */
            }
        }
        /** Modellar variantida barchasini tanlashda ishlatiladi */
        $('body').delegate('.makeAllMain', 'change', function (e) {
            let checkbox = $(this).is(':checked');

            let form = $(this).parents('.formVariation');
            let raw = form.find('#modelsvariations-toquv_raw_material_id').val();
            let color = form.find('#modelsvariations-wms_color_id').val();
            let boyoq = form.find('#modelsvariations-wms_desen_id').val();

            let colorTxt = form.find('#modelsvariations-wms_color_id option:selected').text();
            let rawTxt = form.find('.toquvRawMaterialId option:selected').text();
            let boyoqTxt = form.find('#modelsvariations-wms_desen_id option:selected').text();

            if (!raw && checkbox) {
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: "<?= $infoErrorRaw; ?>", type: 'error'});
                $(this).prop("checked", false);
                return false;
            }

            if (checkbox) {
                let confirm = window.confirm("<?=$infoConfirm?>");
                if (confirm) {
                    let objCVB = form.find('.colorVariationBox');
                    let vcp = objCVB.find('.wms_color_id_variations');
                    let vrm = objCVB.find('.variation-raw-material');
                    let bcp = objCVB.find('.wms_desen_variations');

                    if (vcp) {
                        vcp.each(function (key, val) {
                            let newOption = new Option(colorTxt, color, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(color).trigger('change');
                        });
                    }
                    if (vrm) {
                        vrm.each(function (key, val) {
                            let newOption = new Option(rawTxt, raw, true, true);
                            let checkOption = vrm.find('option[value="'+raw+'"]');
                            if(checkOption.length==0) {
                                $(val).append(newOption).trigger('change');
                            }
                            $(val).val(raw).trigger('change');
                        });
                    }
                    if (bcp) {
                        bcp.each(function (key, val) {
                            let newOption = new Option(boyoqTxt, boyoq, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(boyoq).trigger('change');
                        });
                    }
                }
            }
        });
        /** Modellar variantini mavjud yoki mavjud emasga tekshiradi */
        $('.model-var-id').click(function (e){
            let val = $(this).data('id');
            if(!val){
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:'<?=$errorInfo?>',type:'error'});
                $('#add_new_item_modal').modal('hide');
            }
        });
        /** Modellar ro'yxatini tanlashda ishatiladi */
        $('#modelordersitems-models_list_id').change(function (e) {
            /** model_list select tanlansa qiymatini ozlashtirib olish*/
            let model_list_id = $(this).val();
            $('.model-var-id').attr('data-id',model_list_id);
            let btnValue = lists+'?list='+model_list_id;
            $('.model-var-id').attr('value',btnValue);
            /** model_variation*/
            let models_variations = $('#modelordersitems-model_var_id');
            /** Ajax bilan malumotlarni olish uchun select larni olish */
            var lastSelectToquvAcc = $('#toquv_acs_inputs table tbody tr.multiple-input-list__item:first').find('select.toquv_acs');
            var lastSelectMaterials = $("#material_inputs table tbody tr.multiple-input-list__item:first").find('select.toquv_raw_materials');
            var lastSelectBichuvAcc = $("#ace_inputs table tbody tr.multiple-input-list__item:first").find('select.bichuv_acs');

            if(!model_list_id){
                $(models_variations).empty();
                $('#ace_inputs').find('.list-cell__button').show();
                $('#toquv_acs_inputs').find('.list-cell__button').show();
                $('#material_inputs').find('.list-cell__button').show();
                $('#w4').find('.list-cell__button').show();
                $('#w5').find('.list-cell__button').show();

                /** Bichuv Acc uchun readonly olib tashlash */
                $('#ace_inputs table tbody tr.multiple-input-list__item:first').find('select').val('').trigger('change');
                $('#ace_inputs').multipleInput('clear');
                $('#ace_inputs').find('select').attr('readonly', false);
                $('#ace_inputs').find('input').attr('readonly', false);
                $('#ace_inputs').find('textarea').attr('readonly', false);
                $('#ace_inputs').find('button').show();
                /** Bichuv Acc uchun readonly olib tashlash Yakunlandi */

                /** Toquv acc uchun */
                $('#toquv_acs_inputs table tbody tr:first').find('select').val('').trigger('change');
                $('#toquv_acs_inputs').multipleInput('clear')
                $('#toquv_acs_inputs').find('select').attr('readonly', false);
                $('#toquv_acs_inputs').find('input').attr('readonly', false);
                $('#toquv_acs_inputs').find('button').show();
                /** /Toquv Acs Yakunlandi */

                /** Materiallar uchun   */
                $('#material_inputs table tbody tr:first').find('select').val('').trigger('change');
                $('#material_inputs').multipleInput('clear')
                $("#material_inputs").find('select').attr('readonly', false);
                $("#material_inputs").find('input').attr('readonly', false);
                $("#material_inputs").find('textarea').attr('readonly', false);
                $("#material_inputs").find('button').show();
                /** Materiallar uchun Yakunlandi   */

                /** Pechat uchun */
                $('#w4').multipleInput('clear');
                $('#w4').find('input').val('').trigger('change');
                $('#w4').find('select').val('').trigger('change');
                $('#w4').find('input').attr('readonly', false);
                $('#w4').find('select').attr('readonly', false);
                $('#w4').find('.list-cell__button').show();
                $('#w4').find('button').show();
                /** Yakunlandi Pechat uchun */

                /** Naqsh uchun */
                $('#w5').multipleInput('clear');
                $('#w5').find('input').val('').trigger('change');
                $('#w5').find('select').val('').trigger('change');
                $('#w5').find('input').attr('readonly', false);
                $('#w5').find('select').attr('readonly', false);
                $('#w5').find('.list-cell__button').show();
                $('#w5').find('button').show();
                /** Yakunlandi Naqsh uchun */
            }
            else{
                $.ajax({
                    data: {model_list_id: model_list_id},
                    url: '<?=$model_url?>',
                    type: 'GET',
                    success: function (res){
                        if(res.status){
                            let model_var_obj = res.results;
                            let toquvAcs = res.toquvAcs;
                            if(toquvAcs){
                                $('#toquv_acs_inputs').find('.list-cell__button').hide();
                                $('#toquv_acs_inputs').multipleInput('clear');
                                $('#toquv_acs_inputs').find('select').attr('readonly', false);
                                $('#toquv_acs_inputs').find('input').attr('readonly', false);
                                $('#toquv_acs_inputs table tbody tr:first').find('select').val('');

                                for(let i in toquvAcs){
                                    let items = toquvAcs[i];

                                    if(lastSelectToquvAcc.val())
                                        $('#toquv_acs_inputs').multipleInput('add');
                                    let toquvAcsOption = new Option(items.name==null?'':items.name+' '+items.code==null?'':items.code, items.toquv_raw_materials_id, true, true);
                                    let toquvAcsDesenOption = new Option(items.wdname==null?'':items.wdname, items.wd_id==null?'':items.wd_id, true, true);
                                    let color = items.cpcode==null?'':items.cpcode+'('+items.cpname==null?'':items.cpname+')';
                                    let colors = color?color:items.color_code+'('+items.color_name==null?'':items.color_name+')';
                                    let toquvAcsColorOption = new Option(colors, items.wms_color_id==null?'':items.wms_color_id, true, true);
                                    let toquvAcsPusFineOption = new Option(items.pfname, items.pf_id==null?'':items.pf_id,true, true);
                                    let lastObj = $('#toquv_acs_inputs table tbody tr:last');
                                    lastObj.find('select.toquv_acs').append(toquvAcsOption).trigger('change');
                                    lastObj.find('select.wms_color_id').append(toquvAcsColorOption).trigger('change');
                                    lastObj.find('select.wms_desen_id').append(toquvAcsDesenOption).trigger('change');
                                    lastObj.find('select.pus_fine').append(toquvAcsPusFineOption).trigger('change');
                                }
                                /** Toquv Acc uchun readonly qilish */
                                $('#toquv_acs_inputs').find('button').hide();
                                $('#toquv_acs_inputs').find('.list-cell__button').hide();
                                $('#toquv_acs_inputs').find('select').attr('readonly', true);
                            }
                            else{
                                /** Toquv acc uchun */
                                $('#toquv_acs_inputs').multipleInput('clear');
                                $('#toquv_acs_inputs').find('select').val('').trigger('change');

                                $('#toquv_acs_inputs').find('button').hide();
                                $('#toquv_acs_inputs').find('select').attr('readonly', true);
                                $('#toquv_acs_inputs').find('input').attr('readonly', true);
                                $('#toquv_acs_inputs').find('.list-cell__button').hide();
                            }

                            $(models_variations).empty();
                            if(model_var_obj){
                                for(let i in model_var_obj){
                                    let items = model_var_obj[i];
                                    let code = items.code?items.code:items.color_code;
                                    let n = items.name?items.name:items.color_name;
                                    let name = code+'('+n+')';
                                    let model_var_option = new Option(name, items.mv_id, false, false);
                                    models_variations.append(model_var_option).trigger('change');
                                }
                                PNotify.defaults.styling = "bootstrap4";
                                PNotify.defaults.delay = 2000;
                                PNotify.alert({text:'<?=$success?>',type:'success'});
                            }
                        }
                        else{
                            if(res.bichuvAcs){
                                /** Qayta yuklaganda inputlarni tozalash uchun ishlaydi */
                                $('#ace_inputs table tbody tr.multiple-input-list__item:first').find('select').val('').trigger('change');
                                $('#ace_inputs table tbody tr:first').multipleInput('clear');
                                /** Bichuv Acc da kelgan malumotlarni foreachga solish */
                                for(let i in res.bichuvAcs){
                                    let items = res.bichuvAcs[i];

                                    if(lastSelectBichuvAcc.val())
                                        $('#ace_inputs').multipleInput('add');
                                    let name = items.name==null?'':items.name;
                                    let sku = items.sku==null?'':items.sku;
                                    let id = items.bichuv_acs_id==null?'':items.bichuv_acs_id;
                                    let bichuvAcsOption = new Option(name+' '+sku, id, true, true);
                                    let lastObj = $('#ace_inputs table tbody tr.multiple-input-list__item:last');
                                    lastObj.find('select.bichuv_acs').append(bichuvAcsOption).trigger('change');
                                }
                                /** Bichuv Acc uchun readonly qilish */
                                $('#ace_inputs').find('select').attr('readonly', true);
                                $('#ace_inputs').find('select.application_part').attr('readonly', false);
                                $('#ace_inputs').find('input').attr('readonly', false);
                                $('#ace_inputs').find('textarea').attr('readonly', false);
                                $('#ace_inputs').find('button').hide();
                                $('#ace_inputs').find('button.details').show();
                                $('#ace_inputs').find('.list-cell__button').hide();
                            }
                            else{
                                $('#ace_inputs').multipleInput('clear');
                                $('#ace_inputs table').find('.bichuv_acs').val('').trigger('change');
                                $('#ace_inputs table').find('select').attr('readonly', false);
                                $('#ace_inputs table').find('input').attr('readonly', false);
                                $('#ace_inputs table').find('textarea').attr('readonly', false);
                                $('#ace_inputs table').find('button').show();
                                $('#ace_inputs table').find('.list-cell__button').show();

                            }

                            if(res.materials){
                                $('#material_inputs').multipleInput('clear');
                                $("#material_inputs table tbody tr.multiple-input-list__item:first").find('select').val('').trigger('change');
                                $('#material_inputs').find('input').val('');
                                $('#material_inputs').find('textarea').val('');

                                for(let i in res.materials){
                                    let items = res.materials[i];
                                    if(lastSelectMaterials.val())
                                        $('#material_inputs').multipleInput('add');

                                    let trmname = items.trm_name==null?'':items.trm_name;
                                    let trmcode = items.trmcode==null?'':items.trmcode;
                                    let rtmtname = items.rmtname==null?'':items.rmtname;
                                    let name = trmname+'  '+rtmtname+'  '+trmcode;
                                    let wms_color_code = items.cpcode?items.cpcode:items.color_code;
                                    wms_color_code = wms_color_code==null?'':wms_color_code;
                                    let wms_color_name = items.cpname?items.cpname:items.color_name;
                                    wms_color_name = wms_color_name==null?'':wms_color_name;
                                    let all_name = wms_color_code+'('+wms_color_name+')';
                                    let wms_desen = items.wd_name==null?'':items.wd_name+'('+ items.wdcode==null?'':items.wdcode +')' + items.wbtname==null?'':items.wbtname;
                                    let materialsOptions = new Option(name, items.toquv_raw_materials_id==null?'':items.toquv_raw_materials_id, true, true);
                                    let wmsColorOptions = new Option(all_name, items.wms_color_id==null?'':items.wms_color_id, true, true);
                                    let wmsDesenOptions = new Option(wms_desen, items.wms_desen_id==null?'':items.wms_desen_id, true, true);
                                    let lastObj = $('#material_inputs table tbody tr:last');
                                    lastObj.find('select.toquv_raw_materials').append(materialsOptions).trigger('change');
                                    lastObj.find('select.wms_color_id').append(wmsColorOptions).trigger('change');
                                    lastObj.find('select.wms_desen_id').append(wmsDesenOptions).trigger('change');
                                }

                                /** Materials uchun */
                                $('#material_inputs').find('select').attr('readonly', true);
                                $('#material_inputs').find('input').attr('readonly', false);
                                $('#material_inputs').find('textarea').attr('readonly', false);
                                $('#material_inputs').find('button').hide();
                                $('#material_inputs').find('.list-cell__button').hide();
                            }
                            else{
                                /** Materiallar uchun   */
                                $('#material_inputs table tbody tr:first').find('select').val('').trigger('change');
                                $('#material_inputs').multipleInput('clear')
                                $("#material_inputs").find('select').attr('readonly', false);
                                $("#material_inputs").find('input').attr('readonly', false);
                                $("#material_inputs").find('textarea').attr('readonly', false);
                                $("#material_inputs").find('button').show();
                                /** Materiallar uchun Yakunlandi   */
                            }

                            if(res.toquvAcs){
                                let toquvAcs = res.toquvAcs;
                                $('#toquv_acs_inputs').find('.list-cell__button').hide();
                                $('#toquv_acs_inputs').multipleInput('clear');
                                $('#toquv_acs_inputs').find('select').attr('readonly', false);
                                $('#toquv_acs_inputs').find('input').attr('readonly', false);
                                $('#toquv_acs_inputs table tbody tr:first').find('select').val('');

                                for(let i in toquvAcs){
                                    let items = toquvAcs[i];

                                    if(lastSelectToquvAcc.val())
                                        $('#toquv_acs_inputs').multipleInput('add');
                                    let toquvAcsOption = new Option(items.name==null?'':items.name+' '+items.code==null?'':items.code, items.toquv_raw_materials_id, true, true);
                                    let toquvAcsDesenOption = new Option(items.wdname==null?'':items.wdname, items.wd_id==null?'':items.wd_id, true, true);
                                    let color = items.cpcode==null?'':items.cpcode+'('+items.cpname==null?'':items.cpname+')';
                                    let colors = color?color:items.color_code+'('+items.color_name==null?'':items.color_name+')';
                                    let toquvAcsColorOption = new Option(colors, items.wms_color_id==null?'':items.wms_color_id, true, true);
                                    let toquvAcsPusFineOption = new Option(items.pfname, items.pf_id==null?'':items.pf_id,true, true);
                                    let lastObj = $('#toquv_acs_inputs table tbody tr:last');
                                    lastObj.find('select.toquv_acs').append(toquvAcsOption).trigger('change');
                                    lastObj.find('select.wms_color_id').append(toquvAcsColorOption).trigger('change');
                                    lastObj.find('select.wms_desen_id').append(toquvAcsDesenOption).trigger('change');
                                    lastObj.find('select.pus_fine').append(toquvAcsPusFineOption).trigger('change');
                                }
                                /** Toquv Acc uchun readonly qilish */
                                $('#toquv_acs_inputs').find('button').hide();
                                $('#toquv_acs_inputs').find('.list-cell__button').hide();
                                $('#toquv_acs_inputs').find('select').attr('readonly', true);
                            }
                            else{
                                /** Toquv acc uchun */
                                $('#toquv_acs_inputs table tbody tr:first').find('select').val('').trigger('change');
                                $('#toquv_acs_inputs').multipleInput('clear')
                                $('#toquv_acs_inputs').find('select').attr('readonly', false);
                                $('#toquv_acs_inputs').find('input').attr('readonly', false);
                                $('#toquv_acs_inputs').find('button').show();
                                $('#toquv_acs_inputs').find('.list-cell__button').show();
                                /** /Toquv Acs Yakunlandi */
                            }

                            /** Pechat uchun */
                            $('#w4').multipleInput('clear');
                            $('#w4').find('input').val('').trigger('change');
                            $('#w4').find('select').val('').trigger('change');
                            $('#w4').find('input').attr('readonly', false);
                            $('#w4').find('select').attr('readonly', false);
                            $('#w4').find('.list-cell__button').show();
                            $('#w4').find('button').show();
                            /** Yakunlandi Pechat uchun */

                            /** Naqsh uchun */
                            $('#w5').multipleInput('clear');
                            $('#w5').find('input').val('').trigger('change');
                            $('#w5').find('select').val('').trigger('change');
                            $('#w5').find('input').attr('readonly', false);
                            $('#w5').find('select').attr('readonly', false);
                            $('#w5').find('.list-cell__button').show();
                            $('#w5').find('button').show();
                            /** Yakunlandi Naqsh uchun */

                            $(models_variations).empty();
                            PNotify.defaults.styling = "bootstrap4";
                            PNotify.defaults.delay = 2000;
                            PNotify.alert({text:'<?=$infoError?>', type:'error'});
                        }
                    },
                    error: function (e) {
                        console.log('Ajax Query Error!');
                    }
                });
            }
        });
        /** Modellar variantini tanlashda ishatiladi */
        $('#modelordersitems-model_var_id').change(function (e){
            var lastSelectMaterials = $("#material_inputs table tbody tr.multiple-input-list__item:first").find('select.toquv_raw_materials');
            var lastSelectBichuvAcc = $("#ace_inputs table tbody tr.multiple-input-list__item:first").find('select.bichuv_acs');
            var lastSelectBasePechat = $('#w4 table tbody tr.multiple-input-list:first').find('.name_pechat');
            var lastSelectBaseNaqsh = $('#w5 table tbody tr.multiple-input-list:first').find('.name_naqsh');

            var modelsVar = $(this).val();
            if(modelsVar){
                $.ajax({
                    data: {var_id: modelsVar},
                    url: '<?=$variation_url?>',
                    type: 'GET',
                    success: function(res){
                        if(res.status){
                            let materials = res.data;
                            let bichuvAcs = res.acc;
                            let modelsPechatNaqsh = res.modelsPechatNaqsh;

                            if(modelsPechatNaqsh){
                                if(modelsPechatNaqsh.hasOwnProperty('pechat')){
                                    let item = modelsPechatNaqsh.pechat;
                                    for(let i in item){
                                        let items = item[i];
                                        if(items.name!=null && items.width!=null && items.height!=null){
                                            if(lastSelectBasePechat.val())
                                                $('#w4').multipleInput('add');
                                            let lastPechat = $('#w4 table tbody tr:last');

                                            if(items.image!=null){
                                                let pechats= "<ul id='modelordersitemspechat-"+i+"-attachment_id-thumbs' class='kcf-thumbs ui-sortable'><li class='sortable'><div class='remove'><span class='fa fa-trash'></span></div><img width='150px' src='"+items.image+"'><input type='hidden' name='ModelOrdersItemsPechat[attachment_id][]' value='"+items.image+"'></li></ul>";
                                                $('#modelordersitemspechat-'+i+'-attachment_id-thumbs').html(pechats);
                                            }

                                            let nwidth = items.width==null?'':items.width+'(mm)';
                                            let nheight = items.height==null?'':items.height+'(mm)';
                                            let n = items.name==null?'':items.name;
                                            let bdlname = n+'('+nwidth+' '+nheight+')';
                                            let bdlid = items.bdlid==null?'':items.bdlid;
                                            let optionsPechat = new Option(bdlname,bdlid,true,true);
                                            lastPechat.find('.name_pechat').append(optionsPechat);
                                            lastPechat.find('.name_pechat').attr('readonly', true);
                                            $('#w4 button').css('display', 'none');

                                            let width = items.width==null?'':items.width;
                                            let height = items.height==null?'':items.height;
                                            $('#w4 .width').val(width);
                                            $('#w4 .width').attr('readonly', true);
                                            $('#w4 .height').val(height);
                                            $('#w4 .height').attr('readonly', true);
                                            $('#w4 .list-cell__button').css('display', 'none');
                                        }
                                    }
                                }
                                else{
                                    $('#w4 .list-cell__button').css('display', 'block');
                                    $('#w4 button').css('display', 'block');
                                    $('#w4 table tbody tr').find('.name_pechat').attr('readonly', true);
                                }

                                if(modelsPechatNaqsh.hasOwnProperty('naqsh')){
                                    let item = modelsPechatNaqsh.naqsh;
                                    for(let i in item){
                                        let items = item[i];
                                        if(items.bdlname!=null && items.width != null && items.height != null){
                                            if(lastSelectBaseNaqsh.val())
                                                $('#w5').multipleInput('add');
                                            let lastPechat = $('#w5 table tbody tr:last');
                                            if(items.image!=null){
                                                let naqsh= "<ul id='modelordersnaqsh-"+i+"-attachment_id-thumbs' class='kcf-thumbs ui-sortable'><li class='sortable'><div class='remove'><span class='fa fa-trash'></span></div><img src='"+items.image+"' width='150px'><input type='hidden' name='ModelOrdersNaqsh[attachment_id][]' value='"+items.image+"'></li></ul>";
                                                $('#modelordersnaqsh-'+i+'-attachment_id-thumbs').html(naqsh);
                                            }
                                            let nwidth = items.width==null?'':items.width+'(mm)';
                                            let nheight = items.height==null?'':items.height+'(mm)';
                                            let n = items.name==null?'':items.name;
                                            let bdlname = n+'('+nwidth+' '+nheight+')';
                                            let bdlid = items.bdlid==null?'':items.bdlid;
                                            let optionsPechat = new Option(bdlname,bdlid,true,true);
                                            lastPechat.find('.name_naqsh').append(optionsPechat);
                                            $('#w5 button').css('display', 'none');
                                            lastPechat.find('.name_naqsh').attr('readonly', true);

                                            let width = items.width==null?'':items.width;
                                            let height = items.height==null?'':items.height;
                                            $('#w5 .width').val(width);
                                            $('#w5 .width').attr('readonly', true);
                                            $('#w5 .height').val(height);
                                            $('#w5 .height').attr('readonly', true);
                                            $('#w5 .list-cell__button').css('display', 'none');
                                        }
                                    }
                                }
                                else{
                                    $('#w5 .list-cell__button').css('display', 'block');
                                    $('#w5 button').css('display', 'block');
                                    $('#w5 table tbody tr').find('.name_naqsh').attr('readonly', true);
                                }
                            }
                            if(bichuvAcs){
                                /** Qayta yuklaganda inputlarni tozalash uchun ishlaydi */
                                $('#ace_inputs').find('.list-cell__button').show();
                                $('#ace_inputs table tbody tr.multiple-input-list__item:first').find('select').val('');
                                $('#ace_inputs table tbody tr:first').nextAll().remove();
                                /** Bichuv Acc da kelgan malumotlarni foreachga solish */
                                for(let i in bichuvAcs){
                                    let items = bichuvAcs[i];

                                    if(lastSelectBichuvAcc.val())
                                        $('#ace_inputs').multipleInput('add');
                                    let name = items.baname==null?'':items.baname;
                                    let sku = items.value==null?'':items.value;
                                    let id = items.baid==null?'':items.baid;
                                    let bichuvAcsOption = new Option(name+' '+sku, id, true, true);
                                    let lastObj = $('#ace_inputs table tbody tr.multiple-input-list__item:last');
                                    lastObj.find('select.bichuv_acs').append(bichuvAcsOption);
                                }
                                /** Bichuv Acc uchun readonly qilish */
                                $('#ace_inputs').find('select').attr('readonly', true);
                                $('#ace_inputs').find('input').attr('readonly', true);
                                $('#ace_inputs').find('textarea').attr('readonly', true);
                                $('#ace_inputs').find('button').hide();
                                $('#ace_inputs').find('.list-cell__button').hide();
                            }
                            else{
                                $('#ace_inputs').multipleInput('clear');
                                $('#ace_inputs table').find('.bichuv_acs').val('').trigger('change');
                                $('#ace_inputs table').find('select').attr('readonly', true);
                                $('#ace_inputs table').find('input').attr('readonly', true);
                                $('#ace_inputs table').find('textarea').attr('readonly', true);
                                $('#ace_inputs table').find('button').css('display', 'none');
                                $('#ace_inputs table').find('.list-cell__button').hide();

                            }

                            if(materials){
                                $('#material_inputs table tbody tr:first').find('select.toquv_raw_materials').val('');
                                $('#material_inputs table tbody tr:first').nextAll().remove();
                                for(let i in materials){
                                    let items = materials[i];

                                    if(lastSelectMaterials.val())
                                        $('#material_inputs').multipleInput('add');

                                    let trmname = items.trmname==null?'':items.trmname;
                                    let rtmtname = items.rtmtname==null?'':items.rtmtname;
                                    let trmcode = items.trmcode==null?'':items.trmcode;
                                    let name = trmname+' '+rtmtname+' '+trmcode;
                                    let wms_color_code = items.cpcode?items.cpcode:items.color_code;
                                    wms_color_code = wms_color_code==null?'':wms_color_code;
                                    let wms_color_name = items.cpname?items.cpname:items.color_name;
                                    wms_color_name = wms_color_name==null?'':wms_color_name;
                                    let all_name = wms_color_code+'('+wms_color_name+')';
                                    let wms_desen = items.wdname==null?'':items.wdname+'('+ items.wdcode==null?'':items.wdcode +')' + items.wbtname==null?'':items.wbtname;
                                    let materialsOptions = new Option(name, items.toquv_raw_material_id==null?'':items.toquv_raw_material_id, true, true);
                                    let wmsColorOptions = new Option(all_name, items.wms_color_id==null?'':items.wms_color_id, true, true);
                                    let wmsDesenOptions = new Option(wms_desen, items.wms_desen_id==null?'':items.wms_desen_id, true, true);
                                    let lastObj = $('#material_inputs table tbody tr:last');
                                    lastObj.find('select.toquv_raw_materials').append(materialsOptions);
                                    lastObj.find('select.wms_color_id').append(wmsColorOptions);
                                    lastObj.find('select.wms_desen_id').append(wmsDesenOptions);
                                }

                                /** Materials uchun */
                                $(".toquv_raw_materials").attr('readonly', 'true');
                                $(".toquv_raw_materials_id").hide();
                                $('#material_inputs').find('.list-cell__button').hide();
                                /** Wms_Color */
                                $('.wms_color_id').attr('readonly', true);
                                $('.wms-color-id').hide();
                                /** wms Desen */
                                $('select.wms_desen_id').attr('readonly', true);
                                $('button.wms-desen-id').hide();
                            }
                        }
                    },
                    error: function (e) {
                        console.log('Ajax Query Error!');
                    }
                })
            }
            else{
                /** BaseAcslarini olib kelish */
                var modelsListId = $('#modelordersitems-models_list_id').val();
                if(modelsListId){
                    $.ajax({
                        type: 'GET',
                        data: {id: modelsListId},
                        url: NullVariant,
                        success: function(res){
                            if(res.status){
                                let bichuvAcs = res.data;
                                if(bichuvAcs){
                                    /** Qayta yuklaganda inputlarni tozalash uchun ishlaydi */
                                    $('#ace_inputs').find('.list-cell__button').show();
                                    $('#ace_inputs table tbody tr.multiple-input-list__item:first').find('select').val('');
                                    $('#ace_inputs table tbody tr:first').nextAll().remove();
                                    /** Bichuv Acc da kelgan malumotlarni foreachga solish */
                                    for(let i in bichuvAcs){
                                        let items = bichuvAcs[i];

                                        if(lastSelectBichuvAcc.val())
                                            $('#ace_inputs').multipleInput('add');
                                        let name = items.baname==null?'':items.baname;
                                        let sku = items.value==null?'':items.value;
                                        let id = items.id==null?'':items.id;
                                        let bichuvAcsOption = new Option(name+' '+sku, id, true, true);
                                        let lastObj = $('#ace_inputs table tbody tr.multiple-input-list__item:last');
                                        lastObj.find('select.bichuv_acs').append(bichuvAcsOption);
                                    }
                                    /** Bichuv Acc uchun readonly qilish */
                                    $('#ace_inputs').find('select').attr('readonly', true);
                                    $('#ace_inputs').find('input').attr('readonly', true);
                                    $('#ace_inputs').find('textarea').attr('readonly', true);
                                    $('#ace_inputs').find('button').hide();
                                    $('#ace_inputs').find('.list-cell__button').hide();
                                }
                                else{
                                    $('#ace_inputs').multipleInput('clear');
                                    $('#ace_inputs table').find('.bichuv_acs').val('').trigger('change');
                                    $('#ace_inputs table').find('select').attr('readonly', true);
                                    $('#ace_inputs table').find('input').attr('readonly', true);
                                    $('#ace_inputs table').find('textarea').attr('readonly', true);
                                    $('#ace_inputs table').find('button').show();
                                    $('#ace_inputs table').find('.list-cell__button').show();
                                }
                            }
                            else{
                                $('#ace_inputs').multipleInput('clear');
                                $('#ace_inputs table').find('select').val('').trigger('change');
                                $('#ace_inputs table').find('input').val('');
                                $('#ace_inputs table').find('textarea').val('');
                                $('#ace_inputs table').find('select').attr('readonly', false);
                                $('#ace_inputs table').find('input').attr('readonly', false);
                                $('#ace_inputs table').find('textarea').attr('readonly', false);
                                $('#ace_inputs table').find('button').show();
                                $('#ace_inputs table').find('.list-cell__button').show();
                            }
                        }
                    })
                }
                /** Pechat uchun */
                $('#w4').multipleInput('clear');
                $('#w4').find('input').val('').trigger('change');
                $('#w4').find('select').val('').trigger('change');
                $('#w4').find('input').attr('readonly', false);
                $('#w4').find('select').attr('readonly', false);
                $('#w4').find('button').show();
                /** Yakunlandi Pechat uchun */

                /** Naqsh uchun */
                $('#w5').multipleInput('clear');
                $('#w5').find('input').val('').trigger('change');
                $('#w5').find('select').val('').trigger('change');
                $('#w5').find('input').attr('readonly', false);
                $('#w5').find('select').attr('readonly', false);
                $('#w5').find('button').show();
                /** Yakunlandi Naqsh uchun */
            }
        });
        /** Model Varianti uchun alohida yuklash */
        $("body").delegate('.formVariation', 'submit', function(event) {
            event.preventDefault(); // stopping submitting
            let required = $(".shart");
            let n = 0;
            $(required).each(function (index, value){
                if($(this).val()==""){
                    $(this).css("border-color","red");
                    if($(this).parent().find(".help-block").length>0){
                        $(this).parent().find(".help-block").css("color","red").html("{$required}");
                    }else{
                        $(this).parent().append("<div class='help-block'></div>");
                        $(this).parent().find(".help-block").css("color","red").html("{$required}");
                    }
                    n++;
                }
            });
            if(n>0){
                let infoError = $("#infoErrorForm");
                if(infoError.length==0){
                    $(this).after("<div id='infoErrorForm' style='color:#ff0000'>{$infoError}</div>");
                }else{
                    infoError.html(errorInfo(n));
                }
            }
            else{
                var data = $(this).serializeArray();
                var url = $(this).attr('saveUrl');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data
                }).done(function(response) {
                    if (response.status) {
                        $("#model-variation-form").hide().html('');
                        let model = response.model;
                        let options = new Option(response.full_name,model.id, true, true);
                        $('#modelordersitems-model_var_id').append(options).trigger('change');
                        $('#add_new_item_modal').modal('hide');
                    }else{
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text:"Error",type:'error'});
                    }
                })
                    .fail(function() {
                        console.log(false);
                    });
            }
        });
        $('body').delegate('.create-pechat', 'click', function (e){
            e.preventDefault();
            var data = $('#pechat_form').serializeArray();
            let name;
            let title;
            let content;
            let attachments;
            let base_details;
            for(let i in data){
                let item = data[i];
                if(item.name == 'ModelVarPrints[width]'){
                    title = item.value;
                }
                if(item.name == 'ModelVarPrints[name]'){
                    name = item.value;
                }
                if(item.name == 'ModelVarPrints[height]'){
                    content = item.value;
                }
                if(item.name == 'ModelVarPrints[image]'){
                    attachments = item.value;
                }
                if(item.name == 'ModelVarPrints[base_details_list_id]'){
                    base_details = item.value;
                }
            }
            $.ajax({
                type: 'GET',
                data: {name: name, title: title, content: content, base_details: base_details, attachments: attachments, id: "$modelsId"},
                url: pechat,
                success: function (res){
                    if(res.status){
                        if(res.data){
                            let items = res.data;
                            let w = items.width==null?'':items.width+'(sm)';
                            let h = items.height==null?'':items.height+'(sm)';
                            let n = items.name==null?'':items.name;
                            let all_name = n+'('+w+' '+h+')';
                            let selectOptions = new Option(all_name, items.id==null?'':items.id, true, true);
                            $('#modelsvariations-model_var_prints_id').append(selectOptions);
                            $('#pechat_modal').modal('hide');
                        }
                    }
                    else{

                    }
                }
            });
        });
        $('body').delegate('.create-naqsh', 'click', function (e){
            e.preventDefault();
            var data = $('#naqsh_form').serializeArray();
            let name;
            let title;
            let content;
            let attachments;
            let base_details;
            for(let i in data){
                let item = data[i];
                if(item.name == 'ModelVarStone[width]'){
                    title = item.value;
                }
                if(item.name == 'ModelVarStone[name]'){
                    name = item.value;
                }
                if(item.name == 'ModelVarStone[height]'){
                    content = item.value;
                }
                if(item.name == 'ModelVarStone[image]'){
                    attachments = item.value;
                }
                if(item.name == 'ModelVarStone[base_details_list_id]'){
                    base_details = item.value;
                }
            }
            $.ajax({
                type: 'GET',
                data: {name: name, title: title, content: content, base_details: base_details, attachments: attachments, id: "$modelsId"},
                url: naqsh,
                success: function (res){
                    if(res.status){
                        if(res.data){
                            let items = res.data;
                            let w = items.width==null?'':items.width+'(sm)';
                            let h = items.height==null?'':items.height+'(sm)';
                            let n = items.name==null?'':items.name;
                            let all_name = n+'('+w+' '+h+')';
                            let selectOptions = new Option(all_name, items.id==null?'':items.id, true, true);
                            $('#modelsvariations-model_var_stone_id').append(selectOptions);
                            $('#naqsh_modal').modal('hide');
                        }
                    }
                    else{

                    }
                }
            });
        });
    </script>
<?php Script::end(); ?>
    <!-- / Model tanlansa tabular formani to'ldirib berish -->

<?php
$js = <<<JS
let formEl;
let url;
let formId;
let inputId;
const modalForm = $('#add_new_item_modal');
        
$(document).on('click', '.showModalButton3', function(){
    formId = $(this).data('formId');
    inputId = $(this).data('inputName');
    url = $(this).attr('value');
    if (modalForm.data('bs.modal').isShown) {
        modalForm.find('#modalContent')
                .load($(this).attr('value'));
        //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
    } else {
        //if modal isn't open; open it and load content
        modalForm.modal('show')
                .find('#modalContent')
                .load($(this).attr('value'), function(responseTxt, statusTxt, jqXHR){
            if(statusTxt === "success"){
                formProcess();
                initJs();
            }
            if(statusTxt === "error"){
                alert("Error: " + jqXHR.status + " " + jqXHR.statusText);
            }
        });
         //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' 
        +'<h4>' + $(this).attr('title') + '</h4>';
    }
});

function formProcess() {
    formEl = document.getElementById(formId);
    
    $('#'+formId).on('beforeSubmit', function () {
        const yiiForm = $(this);
        $.ajax({
                type: yiiForm.attr('method'),
                url: yiiForm.attr('action'),
                data: yiiForm.serializeArray()
                })
                .done(function(data) {
                    if(data.success) {
                        const response = data;
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text:"Success",type:'success'});
                        
                        modalForm.modal('hide');    
                        let newOption = new Option(response.title, response.selected_id, true, true);
                        $('#'+inputId).append(newOption).trigger('change');
                    
                    } else if (data.validation) {
                        // server validation failed
                        yiiForm.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 3000;
                        PNotify.alert({text:'Error',type:'error'});
                    } else {
                        // incorrect server response
                    }
                })
                .fail(function () {
                    // request failed
                });
        
            return false; // prevent default form submission
    });
}

function initJs() {
  if (url.indexOf('wms-color') != -1){
    const colorPantoneSelectEl = document.getElementById('wmscolor-color_pantone_id');
    const fieldsetAnotherColorEl = document.getElementById('fieldset_another_color');
    const isAnotherColorCheckboxEl = document.getElementById('wmscolor-is_another_color');
 
    isAnotherColorCheckboxEl.addEventListener('change', anotherColorListener);
        
    function anotherColorListener() {
        if (this.checked) {
            colorPantoneSelectEl.disabled = true;
            fieldsetAnotherColorEl.disabled = false;
        } else {
            fieldsetAnotherColorEl.disabled = true;
            colorPantoneSelectEl.disabled = false;
        }
    }true
        
    anotherColorListener();
  }
}

// multiple input events
jQuery('#material_inputs').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.list-cell__wms_color_id .wms-color-id')
        .data('inputName', 'modelordersitemsmaterial-'+currentIndex+'-wms_color_id');
    row.find('.list-cell__wms_desen_id .wms-desen-id')
        .data('inputName', 'modelordersitemsmaterial-'+currentIndex+'-wms_desen_id');
    row.find('.list-cell__toquv_raw_materials_id .toquv_raw_materials_id')
        .data('inputName', 'modelordersitemsmaterial-'+currentIndex+'-toquv_raw_materials_id');
});

// multiple input events
jQuery('#toquv_acs_inputs').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.list-cell__toquv_raw_materials_id .model-toquv-acs')
        .data('inputName', 'modelordersitemstoquvacs-'+currentIndex+'-toquv_raw_materials_id');
    
});

jQuery('#ace_inputs').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.list-cell__bichuv_acs_id .bichuv_acs_id')
        .data('inputName', 'modelordersitemsacs-'+currentIndex+'-bichuv_acs_id');
    row.find('.list-cell__application_part .application_part')
        .data('inputName', 'modelordersitemsacs-'+currentIndex+'-application_part');
    row.find('.list-cell__application_part .details')
        .data('inputName', 'modelordersitemsacs-'+currentIndex+'-application_part');    
});

jQuery('#w4').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.list-cell__name .name_pechat')
        .data('inputName', 'modelordersitemspechat-'+currentIndex+'-name');
    row.find('.list-cell__name .details').data('inputName', 'modelordersitemspechat-'+currentIndex+'-name');
});

jQuery('#w5').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.list-cell__name .name_naqsh')
        .data('inputName', 'modelordersnaqsh-'+currentIndex+'-name');
    row.find('.list-cell__name .details').data('inputName', 'modelordersnaqsh-'+currentIndex+'-name');
});

    jQuery('body').delegate('.rm_size','change', function(e) { 
        let t = $(this);
        let val_id = t.val();
        let size_item_div = $('#size_item');
        $.ajax({
            url: '{$url_size}?num=' + val_id,
            success: function(response) {
                let div = '<div style="width: 150px;padding-right: 3px;float: left;">'+
                        '<div class="form-group field-model_orders_size">'+
                            '<label>&nbsp</label>'+
                            '<span class="form-control text-center">Assorti soni</span>'+
                            '<hr style="margin: 0;">'+
                            '<span class="form-control text-center">O\'lchamlar soni</span>'+
                        '</div>'+
                    '</div>';
                if(response.status == 1){
                    let sizeList = response.size;
                    sizeList.map(function(index,key) {
                        div += '<div style="width: 70px;padding-right: 3px;float: left;">'+
                                    '<div class="form-group field-model_orders_size">'+
                                        '<label class="control-label text-center" style="width: 100%" for="model_orders_size_'+index.id+'"> '+index.name+' </label>'+
                                        '<input type="text" class="form-control number numberFormat input_assorti input_size_all" id="model_orders_size_'+index.id+'" tabindex="1" data-input="input_size_'+index.id+'" name="ModelOrdersItemsSize['+key+'][assorti_count]" style="padding-left: 2px;">'+
                                        '<hr style="margin: 0;">'+
                                        '<input type="text" class="form-control number numberFormat input_size input_size_all" tabindex="2" id="input_size_'+index.id+'" name="ModelOrdersItemsSize['+key+'][count]" style="padding-left: 2px;">'+
                                        '<input type="hidden" name="ModelOrdersItemsSize['+key+'][size_id]" style="padding-left: 2px;" value="'+index.id+'">'+
                                    '</div>'+
                                '</div>';
                    });
                    div += '<div style="width: 100px;padding-right: 3px;float: left;">'+
                        '<div class="form-group field-model_orders_size">'+
                            '<label>Jami</label>'+
                            '<span class="form-control text-center summ_input_assorti"></span>'+
                            '<hr style="margin: 0;">'+
                            '<span class="form-control text-center summ_input_size"></span>'+
                        '</div>'+
                    '</div>';
                }
                size_item_div.html(div);
            }
        });
    });
    $('body').delegate('.input_assorti', 'focus', function(e){
        $(this).attr('data-old-val',$(this).val());
        $(this).attr('focused','true');
    });
    $('body').delegate('.input_assorti', 'blur', function(e){
        $(this).removeAttr('focused');
    });
    $('body').delegate('.input_assorti', 'keyup', function(e){
        let t = $(this);
        let old_val = t.attr('data-old-val');
        let assorti_count = $('#assorti_count');
        let all_count = $('#sum_item_qty');
        let size_input = $('#'+t.attr('data-input'));
        if(all_count.val()=='' || all_count.val()==0 || assorti_count.val()=='' || assorti_count.val()==0){
            if(all_count.val()=='' || all_count.val()==0){
            call_pnotify('fail','Umumiy ish soni kiritilishi shart!');
            all_count.focus();
                t.val(old_val);
            }
            if(assorti_count.val()=='' || assorti_count.val()==0){
                call_pnotify('fail','Umumiy assorti soni kiritilishi shart!');
                assorti_count.focus();
                t.val(old_val);
            }
        }else{
            let summ_assorti = 0;
            $('.input_assorti').each(function() {
                if($(this).val()!=0 && $(this).val()!=''){
                    summ_assorti += 1*$(this).val();
                }
            });
            if(summ_assorti > assorti_count.val()){
                let different = (1*summ_assorti-(1*assorti_count.val()));
                call_pnotify('fail','Kiritilgan qiymatlar umumiy assorti sonidan '+ different+' ta ortib ketdi!');
                t.blur();
                t.val(1*$(this).val()-1*different).trigger('change');
            }else{
                let input_value = 1*all_count.val()*t.val()/assorti_count.val();
                size_input.val(input_value.toFixed(0));
                $('.summ_input_assorti').html(summ_assorti.toFixed(0));
                let input_size_sum = 0;
                $('.input_size').each(function() {
                    if($(this).val()!=0 && $(this).val()!=''){
                        input_size_sum += 1*$(this).val();
                    }
                });
                $('.summ_input_size').html(input_size_sum.toFixed(0));
            }
        }
    });
    $('body').delegate('.input_assorti', 'change', function(e){
        let t = $(this);
        if(t.attr('focused')!='true'){
            let old_val = t.attr('data-old-val');
            let assorti_count = $('#assorti_count');
            let all_count = $('#sum_item_qty');
            let size_input = $('#'+t.attr('data-input'));
            if(all_count.val()=='' || all_count.val()==0 || assorti_count.val()=='' || assorti_count.val()==0){
                if(all_count.val()=='' || all_count.val()==0){
                    call_pnotify('fail','Umumiy ish soni kiritilishi shart!');
                    all_count.focus();
                    t.val(old_val);
                }
                if(assorti_count.val()=='' || assorti_count.val()==0){
                    call_pnotify('fail','Umumiy assorti soni kiritilishi shart!');
                    assorti_count.focus();
                    t.val(old_val);
                }
            }else{
                let summ_assorti = 0;
                $('.input_assorti').each(function() {
                    if($(this).val()!=0 && $(this).val()!=''){
                        summ_assorti += 1*$(this).val();
                    }
                });
                if(summ_assorti > assorti_count.val()){
                    call_pnotify('fail','Kiritilgan qiymatlar umumiy assorti sonidan ortib ketdi!');
                    t.val(0);
                }else{
                    let input_value = 1*all_count.val()*t.val()/assorti_count.val();
                    size_input.val(input_value.toFixed(0));
                    $('.summ_input_assorti').html(summ_assorti.toFixed(0));
                    let input_size_sum = 0;
                    $('.input_size').each(function() {
                        if($(this).val()!=0 && $(this).val()!=''){
                            input_size_sum += 1*$(this).val();
                        }
                    });
                    $('.summ_input_size').html(input_size_sum.toFixed(0));
                    console.log('changed');
                }
            }
        }
    });
    $('body').delegate('#assorti_count', 'change', function(e){
        if($(this).val()==''||$(this).val()==0){
            $('.input_size_all').each(function() {
                $(this).val('');
            });
            $('.summ_input_size').html('');
            $('.summ_input_assorti').html('');
        }else{
            let all_count = $('#sum_item_qty');
            if(all_count.val()!='' && all_count.val()!=0){
                $('.input_assorti').each(function() {
                    $(this).trigger('change');
                });
            }
        }
    });
    $('body').delegate('#sum_item_qty', 'change', function(e){
        if($(this).val()==''||$(this).val()==0){
            $('.input_size_all').each(function() {
                $(this).val('');
            });
            $('.summ_input_size').html('');
            $('.summ_input_assorti').html('');
        }else{
            let assorti_count = $('#assorti_count');
            if(assorti_count.val()!='' && assorti_count.val()!=0){
                $('.input_assorti').each(function() {
                    $(this).trigger('change');
                });
            }
        }
    });
    function call_pnotify(status,text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 3000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
JS;

$this->registerJs($js);
$this->registerJsFile('/js/model_order_items.js',['depends'=>\yii\web\JqueryAsset::className()]);