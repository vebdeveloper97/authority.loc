<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\toquv\models\ToquvRawMaterials;
use kartik\select2\Select2;
use kartik\helpers\Html as KHtml;
use app\modules\wms\models\WmsDesen;
use app\modules\toquv\models\ToquvPusFine;
use app\components\TabularInput\CustomTabularInput;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\wms\models\ModelOrders */
/* @var $document \app\modules\wms\models\WmsDocument */
/* @var $modelMaterials \app\modules\base\models\ModelOrdersItemsMaterial */
/* @var $documentItems \app\modules\wms\models\WmsDocumentItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wms-items-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <div class="box-title">
                        <?= Yii::t('app', 'Document') ?>
                    </div>
                </div>
                <div class="box-body">
                    <?= $this->render('document', [
                        'form' => $form,
                        'document' => $document,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <?=CustomTabularInput::widget([
        'id' => 'wms_document_items',
        'models' => $modelMaterials,
        'addButtonOptions' => [
            'class' => 'hide',
        ],
        'removeButtonOptions' => [
            'class' => 'hide',
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
                        'readonly' => true
                    ],
                ],
                'title' => Yii::t('app', 'Material'),
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Quantity'),
            ],
        ]
    ])?>

    <div class="row">

        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
