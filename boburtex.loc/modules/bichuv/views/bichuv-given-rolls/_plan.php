<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use app\widgets\helpers\Script;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
/* @var $modelNastel app\modules\bichuv\models\BichuvNastelDetails */
/* @var $modelBD app\modules\bichuv\models\BichuvDoc */
/* @var $modelNastelItems app\modules\bichuv\models\BichuvNastelDetailItems */
/* @var $searchModel app\modules\bichuv\models\BichuvNastelDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\widgets\ActiveForm */

?>
<?php
$t = Yii::$app->request->get('t', 2);
if ($t == 2):?>
    <?php
    $accessoriesList = $modelBD->getAccessories(null, true, true);
    $detailType = $modelNastel->getDetailTypeList(null, true)
    ?>
    <div class="kirim-mato-box">
        <?php Pjax::begin(['id' => 'nastelDetailItemsGridNew']) ?>
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'nastel_party')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                    'options' => [
                        'placeholder' => Yii::t('app', 'Sana'),
                        'disabled' => true
                    ],
                    'language' => 'ru',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                    ]
                ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 2, 'disabled' => true]) ?>
            </div>
        </div>
        <div class="row">
            <?= $form->field($modelNastel,'token')->hiddenInput(['id' => 'detailToken','value' => $model::TOKEN_MAIN])->label(false);?>
            <div class="col-md-2">
                <?= $form->field($modelNastel, 'detail_type_id')->widget(Select2::className(), [
                    'data' => $detailType['data'],
                    'options' => [
                        'id' => 'detailTypeId',
                        'options' => $detailType['dataAttr'],

                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            var elem = $(this);
                                            let token = $('option:selected', this).attr('data-token');
                                            $('#detailToken').val(token);
                                            if(token == '".$model::TOKEN_ACCESSORY."'){
                                                $('#EntityIdBox').addClass('hidden');
                                                $('#AcsEntityIdBox').removeClass('hidden');
                                            }else{
                                                $('#AcsEntityIdBox').addClass('hidden');
                                                $('#EntityIdBox').removeClass('hidden');
                                            }
                                    }"
                        ),
                    ],
                ]) ?>
            </div>
            <div class="col-md-6">
                <div id="AcsEntityIdBox" class="hidden">
                    <?= $form->field($modelNastel, 'acs_entity_id')->widget(Select2::className(), [
                        'data' => $accessoriesList,
                        'options' => [
                            'id' => 'AcsEntityId',
                        ],
                    ])->label(Yii::t('app', 'Aksessuar Nomi')) ?>
                </div>
                <div id="EntityIdBox">
                    <?= $form->field($modelNastel, 'entity_id')->widget(Select2::className(), [
                        'data' => $model->getRollitems(true),
                        'options' => [
                            'id' => 'EntityId'
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
                    ])->label(Yii::t('app', 'Mato Nomi')) ?>
                </div>
            </div>
            <div class="col-md-2">
                <?= $form->field($modelNastel, 'size_collection_id')->widget(Select2::className(), [
                    'data' => $modelNastel->getSizeCollectionList(),
                    'options' => [
                        'id' => 'sizeCollectionId'
                    ],
                ])->label(Yii::t('app','Size Collection')) ?>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="button" style="margin-top: 25px;" class="form-control btn btn-primary"
                           value="<?= Yii::t('app', 'Detal soni yaratish'); ?>" id="AddSizeCollection">
                </div>
            </div>
        </div>
        <div id="nastelItemBox">
            <table class="table-bordered table table-responsive text-center">
                <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', "O'lcham Nomi"); ?></th>
                    <th><?= Yii::t('app', 'Reja boyicha miqdor'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="3"><?= Yii::t('app', "Ma'lumot mavjud emas!"); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
        </div>
        <?php
        ActiveForm::end();
        Pjax::end();
        ?>
    </div>
    <div class="nastel-items-grid-view">
        <?php Pjax::begin(['id' => 'bichuv-nastel-items_pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $model->cp['dataProvider'],
            'filterRowOptions' => ['class' => 'filters no-print'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'bichuvNastelDetail.detailType.name',
                    'label' => Yii::t('app', "Detail Type ID"),
                ],
                [
                    'label' => Yii::t('app', 'Mato'),
                    'value' => function ($model) {
                        return $model->bichuvNastelDetail->detailName;
                    }
                ],
                [
                    'attribute' => 'size.name',
                    'label' => Yii::t('app', "O'lcham"),
                ],
                [
                    'attribute' => 'required_count',
                    'label' => Yii::t('app', "Reja bo'yicha miqdor"),
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'contentOptions' => ['class' => 'no-print', 'style' => 'width:100px;'],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class' => 'update-dialog btn btn-xs btn-success',
                                'data-form-id' => $model->id,
                            ]);
                        },
//                        'view' => function ($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
//                                'title' => Yii::t('app', 'View'),
//                                'class' => 'btn btn-xs btn-primary view-dialog',
//                                'data-form-id' => $model->id,
//                            ]);
//                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class' => 'btn btn-xs btn-danger delete-dialog',
                                'data-form-id' => $model->id,
                            ]);
                        },

                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <?php
    $url = Url::to(['get-size-collection']);
    Script::begin();
    ?>
    <script>
        $('html').css('zoom', '90%');
        $("#nastelDetailItemsGridNew").on("pjax:end", function () {
            $.pjax.reload({container: "#bichuv-nastel-items_pjax"});
        });
        $('body').delegate('#AddSizeCollection', 'click', function (e) {
            e.preventDefault();
            let sc = $('#sizeCollectionId').val();
            if (!sc) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 4000;
                PNotify.alert({text: '<?= Yii::t('app', "O\'lchamlar to\'plamini tanlang");?>', type: 'error'});
                return false;
            }

            async function doAjax() {
                let result;
                try {
                    result = await $.ajax({
                        url: '<?= $url; ?>?id=' + sc,
                        type: 'GET'
                    });
                    return result;
                } catch (error) {
                    console.error(error);
                }
            }

            doAjax().then((data) => otherDo(data));

            function otherDo(data) {
                if (data.status) {
                    $('#nastelItemBox').html(data.result);
                } else {
                    PNotify.defaults.styling = 'bootstrap4';
                    PNotify.defaults.delay = 4000;
                    PNotify.alert({text: '<?= Yii::t('app', "Xatolik yuz berdi!");?>', type: 'error'});
                    return false;
                }
            }
        })
    </script>
    <?php Script::end(); ?>
<?php endif; ?>
<?php $this->registerCss("
div#nastelItemBox {
    border:2px solid #337ab7;
    padding:5px;
    margin:15px; 
}
.nastel-items-grid-view {
    border:1px solid #CCC;
    margin:25px 0;
}
"); ?>
<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'bichuv-nastel-detail-items',
    'crud_name' => 'bichuv-nastel-items',
    'modal_id' => 'bichuv-nastel-items-modal',
    'modal_header' => '<h3>' . Yii::t('app', 'Bichuv Nastel Items') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-nastel-items_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>

