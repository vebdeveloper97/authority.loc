<?php

use app\components\KCFinderInputWidgetCustom;
use app\modules\toquv\models\SortName;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseQcDocument */
/* @var $attachment app\modules\base\models\BaseQcAttachment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-qc-document-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'nastel_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'sort_id')->widget(Select2::class,[
                'data' => SortName::getSortListMap()
            ]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::class, [
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
    </div>
    <br>
    <?=$this->render('_docItems',[
        'form'=>$form,
        'models'=>$models
    ])?>

    <?=$form->field($attachment,'path')->widget(KCFinderInputWidgetCustom::class,[
        'buttonLabel' => Yii::t('app',"Fayl qo'shish"),
        'kcfBrowseOptions' => [
            'langCode' => 'ru'
        ],
        'multiple' => true,
        'indexTabular' => '{multiple_index_mini_postal}',
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
    ])->label(false)?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
