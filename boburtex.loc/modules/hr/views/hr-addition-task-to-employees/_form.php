<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;
use unclead\multipleinput\MultipleInput;
use kartik\slider\Slider;
/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrAdditionTaskToEmployees */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="hr-addition-task-to-employees-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'hr_employee_id')->widget(Select2::classname(), [
        'data' => \app\modules\hr\models\HrEmployee::getListMap(),
        'options' => ['placeholder' => Yii::t('app','Select a employee ...')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
    <br>
    <div class="box box-info box-solid">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?= CustomTabularInput::widget([
                'id' => 'hr_addition_task_items_id',
                'form' => $form,
                'models' => $models,

                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'addButtonOptions' => [
                    'class' => 'btn btn-success',
                ],
                'min' => 0,
                'cloneButton' => false,
                'columns' => [
                    [
                        'name' => 'task',
                        'title' => Yii::t('app', 'Assigned tasks'),

                    ],
                    [
                        'name'  => 'rate',
                        'type' => Slider::class,
                        'defaultValue' => 50,
                        'options' => [
                            'handleColor'=>Slider::TYPE_DANGER,
                            'pluginOptions'=>[
                                'handle'=>'triangle',
                                'tooltip'=>'always',
                                'min'=>0,
                                'max'=>100,
                                'step'=>1
                            ]
                        ],
                        'title' => Yii::t('app', "Done") . ' %',
                    ],
                ],

            ]);?>
        </div>
    </div>

    <?/*= $form->field($model, 'task')->textarea(['rows' => 6]) */?><!--

    <?/*= $form->field($model, 'rate')->textInput() */?>

    <?/*= $form->field($model, 'expire_date')->textInput() */?>

    <?/*= $form->field($model, 'remember_date')->textInput() */?>

    <?/*= $form->field($model, 'type')->textInput() */?>

    --><?/*= $form->field($model, 'updated_by')->textInput() */?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerCss("
.slider-horizontal{width: 97%!important}
.slider-selection{background: #9bbdf2}
")
?>