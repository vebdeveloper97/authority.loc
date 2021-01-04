<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 16.03.20 18:29
 */


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $modelList app\modules\base\models\ModelsList */
/* @var $prints app\modules\base\models\ModelVarPrints */
/* @var $form yii\widgets\ActiveForm */
$urlRemain = Yii::$app->urlManager->createUrl('base/models-list/ajax-request');
$urlListBoyoqhona = Yii::$app->urlManager->createUrl(['base/models-list/ajax-request','colorType'=>'boyoq']);
$model_list_id = ($_GET['list']) ? $_GET['list'] : '';

use app\modules\base\models\ModelsList;
use app\modules\base\models\ModelsVariations;
use app\modules\base\models\ModelVarPrints;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm; ?>
<div id="form-variation_<?=time()?>" class="models-variations-form">
    <?php if($modelList){?>
        <?php echo Yii::t('app','Model')?> : <span><?=$modelList['name']?> <b><?=$modelList['article']?></b></span>
    <?php }?>
    <?php
    $form = ActiveForm::begin(['options' => [
        'enableAjaxValidation' => true,
        'class' => 'formVariation',
        'id' => 'formVariation_'.time(),
        'validationUrl' => Yii::$app->urlManager->createUrl('base/models-variations/validate'),
        'saveUrl' => Yii::$app->urlManager->createUrl([
            'base/models-variations/save', 'id' => ($model->isNewRecord)
                ? 0 : $model->id
        ]),
    ]
    ]); ?>
    <div class="row" style="padding-top: 15px;">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'shart form-control']) ?>

            <?= $form->field($model, 'color_pantone_id')->widget(\kartik\select2\Select2::className(),
                [
                    'data' => $model->existsPantone,
                    'options' => [
                        'placeholder' => Yii::t('app', 'Qidirish uchun kamida 3ta belgi yozing'),
                        'id' => 'colorPantoneId_'.time(),
                        'class' => 'colorPantoneId'
                    ],
                    'pluginEvents' => [
                        "select2:unselect" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }"),
                        "select2:select" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression(
                                "function () { return '...'; }"
                            ),
                        ],
                        'ajax' => [
                            'url' => $urlRemain,
                            'dataType' => 'json',
                            'data' => new JsExpression(
                                "function(params) {
                                                var currIndex = 
                                                $(this).parents('tr').attr('data-row-index');
                                                return { 
                                                    q:params.term,index:currIndex
                                                };
                                            }"),
                            'cache' => true
                        ],
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
                ]
            )->label(Yii::t('app', 'Asosiy panton rang kodi')) ?>

            <?= $form->field($model, 'toquv_raw_material_id')->widget(\kartik\select2\Select2::className(),
                [
                    'data' => $model::getMaterialList(null,$model_list_id),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Qidirish uchun kamida 3ta belgi yozing'),
                        'id' => 'toquvRawMaterialId_'.time(),
                        'class' => 'toquvRawMaterialId'
                    ],
                    'pluginEvents' => [
                        "select2:unselect" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }"),
                        "select2:select" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
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
                ]
            )->label(Yii::t('app', 'Asosiy mato')) ?>

            <?= $form->field($model, 'code')->hiddenInput(['value' => 1])->label(false); ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea() ?>
            <?= $form->field($model, 'boyoqhona_color_id')->widget(\kartik\select2\Select2::className(),
                [
                    'data' => $model->existsBoyoqhona,
                    'options' => [
                        'placeholder' => Yii::t('app', 'Qidirish uchun kamida 3ta belgi yozing'),
                        'id' => 'boyoqhonaColorId_'.time(),
                        'class' => 'boyoqhonaColorId'
                    ],
                    'pluginEvents' => [
                        "select2:unselect" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }"),
                        "select2:select" => new JsExpression("function() { $('#makeAllMain').prop('checked', false); }")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression(
                                "function () { return '...'; }"
                            ),
                        ],
                        'ajax' => [
                            'url' => $urlListBoyoqhona,
                            'dataType' => 'json',
                            'data' => new JsExpression(
                                "function(params) {
                                                var currIndex = 
                                                $(this).parents('tr').attr('data-row-index');
                                                return { 
                                                    q:params.term,index:currIndex
                                                };
                                            }"),
                            'cache' => true
                        ],
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
                ]
            )->label(Yii::t('app', 'Asosiy boyoqhona rang kodi')) ?>
            <?= $form->field($model, 'make_all_as_main', ['template' => '<label class="checkbox-transform">{input}
                <span class="checkbox__label">' . Yii::t("app", "Make all main") . '</span>
            </label>',])->checkbox(['class' => 'checkbox__input makeAllMain', 'id' => 'makeAllMain_'.time()], false) ?>
        </div>
    </div>

    <?= $form->field($model, 'model_list_id')->hiddenInput(['value' => $model_list_id])->label(false) ?>
    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Detallar uchun ranglar va matolar'),
                'content' => $this->render('form/_colors', [
                    'colors' => $colors,
                    'form' => $form,
                    'model' => $model,
                    'modelList' => $modelList,
                ]),
                'options' => ['id' => 'color_tab_'.time()],
                'active' => true
            ],
            [
                'label' => Yii::t('app', 'Variation attachments'),
                'content' => $this->render('form/_attachments', [
                    'attachments' => $attachments, 'form' => $form
                ]),
                'options' => ['id' => 'attachment_tab_'.time()],
            ],
            /*[
                'label' => Yii::t('app','Variation prints'),
                'content' => $this->render('form/_prints', [
                    'prints' => $prints, 'all_prints' => \app\modules\base\models\ModelVarPrints::find()->limit(3)->all()
                ]),
            ],*/
        ]
    ]); ?>
    <div class="form-group" style="padding-top: 20px">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success send-variation']) ?>
        <?php if(Yii::$app->request->post('id')!='orders'){?>
            <?= Html::a(Yii::t('app', 'Bekor qilish'), [
                '/base/models-list/update',
                'id' => $_GET['list'], 'active' => 'variation'
            ], [
                'class' => 'btn btn-primary cansel',
                'data' => (!Yii::$app->request->isAjax) ? [
                    'confirm' => Yii::t('app', "Siz rostdan ham ma'lumotlarni saqlamasdan orqaga qaytmoqchimisiz?"),
                ] : [],
            ]) ?>
        <?php }?>
    </div>

    <?php ActiveForm::end(); ?>

</div>