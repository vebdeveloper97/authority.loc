<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $form \yii\widgets\ActiveForm */
/* @var $modelsRoom \app\modules\base\models\BaseModelTikuvNote */
/* @var $indexHouse */
?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-rooms',
    'widgetItem' => '.room-item',
    'limit' => 100,
    'min' => 1,
    'insertButton' => '.add-room',
    'deleteButton' => '.remove-room',
    'model' => $modelsRoom[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'note'
    ],
]); ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?=Yii::t('app', 'Note')?></th>
            <th class="text-center">
                <button type="button" class="add-room btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
            </th>
        </tr>
        </thead>
        <tbody class="container-rooms">
        <?php foreach ($modelsRoom as $indexRoom => $modelRoom): ?>
            <tr class="room-item" style="width: 30%;">
                <td class="vcenter">
                    <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]note")->label(false)->textInput(['maxlength' => true]) ?>
                </td>
                <td class="text-center vcenter">
                    <button type="button" class="remove-room btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php DynamicFormWidget::end(); ?>