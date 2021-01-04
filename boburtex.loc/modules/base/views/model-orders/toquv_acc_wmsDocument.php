<?php

use app\modules\bichuv\models\BichuvAcs;
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
/* @var $modelsAcs \app\modules\base\models\ModelOrdersItemsAcs */
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
                'name' => 'toquv_raw_materials_id',
                'type' => Select2::class,
                'title' => Yii::t('app', 'Toquv Aksessuar'),
                'options' => [
                    'data' => $model->getArrayMapModel(ToquvRawMaterials::class, 'id', 'toq_acc'),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Toquv Aksessuar'),
                        'readonly' => true,
                    ],
                ],
                'columnOptions' => [
                    'style' => 'width: 200px;',
                ]
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Quantity')
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
