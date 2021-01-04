<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\modules\hr\models\HrEmployee;
use app\modules\base\models\ModelsList;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use app\modules\base\models\BaseMethodSizeItems;


/* @var $model \app\modules\base\models\BaseMethod */
/* @var $modelItems \app\modules\base\models\BaseMethodSizeItems */
/* @var $modelItemsChilds \app\modules\base\models\BaseMethodSizeItemsChilds */
/* @var $baseMethodSeam \app\modules\base\models\BaseMethodSeam */

?>

<div class="person-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="col-sm-4">
        <?= $form->field($model, 'model_list_id')->widget(Select2::class,[
            'data' => ArrayHelper::map(ModelsList::find()->all(), 'id', 'article'),
            'options' => [
                'placeholder' => Yii::t('app', 'Models List Select'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'planning_hr_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(HrEmployee::find()->all(), 'id', 'fish'),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'model_hr_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(HrEmployee::find()->all(), 'id', 'fish'),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'etyud_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(HrEmployee::find()->all(), 'id', 'fish'),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'master_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(HrEmployee::find()->all(), 'id', 'fish'),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
    </div>

    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 10,
        'min' => 1,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $modelItems[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'description',
        ],
    ]); ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th><?=Yii::t('app', 'Size Select')?></th>
            <th style="width: 70%;"><?=Yii::t('app', 'Base Method Seam')?></th>
            <th class="text-center" style="width: 90px;">
                <button type="button" class="add-house btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($modelItems as $indexHouse => $modelHouse): ?>
            <?php if($modelHouse['status'] != BaseMethodSizeItems::STATUS_SAVED): ?>
                <?php /*if(count($modelHouse) > 0): */?>
                    <tr class="house-item" style="<?php if($modelHouse['status'] == BaseMethodSizeItems::STATUS_SAVED){echo 'background: lightgreen';}?>">
            <td class="vcenter">
                <?php
                // necessary for update action.
                if (! $modelHouse->isNewRecord) {
                    echo Html::activeHiddenInput($modelHouse, "[{$indexHouse}]id");
                }
                ?>
                <?php if($modelHouse['status'] != BaseMethodSizeItems::STATUS_SAVED): ?>
                    <?= $form->field($modelHouse, "[{$indexHouse}]size_id")->widget(Select2::className(),[
                        'data' => ArrayHelper::map(\app\models\Size::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Size Select'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]) ?>
                <?php else: ?>
                    <?= $form->field($modelHouse, "[{$indexHouse}]size_id")->widget(Select2::className(),[
                        'data' => ArrayHelper::map(\app\models\Size::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Size Select'),
                            'readonly' => true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]) ?>
                <?php endif; ?>
            </td>
            <td>
                <?= $this->render('_items/_form-rooms', [
                    'form' => $form,
                    'indexHouse' => $indexHouse,
                    'modelsRoom' => $modelItemsChilds[$indexHouse],
                    'baseMethodSeam' => $baseMethodSeam,
                    'modelHouse' => $modelHouse,
                ]) ?>
            </td>
            <?php if($modelHouse['status'] !== BaseMethodSizeItems::STATUS_SAVED): ?>
                <td class="text-center vcenter" style="width: 90px;">
                    <button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                    <hr>
                    <?php if(!$model->isNewRecord): ?>
                        <input type="submit" value="<?=Yii::t('app', 'Save and finish')?>" formaction="save-and-finish?id=<?=$indexHouse?>" id="pages<?=$indexHouse?>" data-id="<?=$indexHouse?>" class="btn btn-success btn-xs save-and-finish" />
                    <?php endif; ?>
                </td>
            <?php else: ?>
                <td></td>
            <?php endif; ?>
        </tr>
                <?php /*endif; */?>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerCss("
    html{
        zoom: 80%;
    }
    .select2-container .select2-selection--single .select2-selection__clear {
        position: absolute!important;
    }
");
$this->registerJs('
        function initSelect2DropStyle(a,b,c){
            initS2Loading(a,b,c);
        }
        function initSelect2Loading(a,b){
            initS2Loading(a,b);
        }
    ',
    yii\web\View::POS_HEAD
);
?>