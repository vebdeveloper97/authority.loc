<?php
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethod */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelHouse \app\modules\base\models\BaseMethodSizeItems */
/* @var $modelsRoom \app\modules\base\models\BaseMethodSizeItemsChilds */
/* @var $form \yii\widgets\ActiveForm */

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use app\modules\base\models\BaseMethodSeam;
use kartik\helpers\Html as KHtml;

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
        'description'
    ],
]); ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><?=Yii::t('app', 'Base Method Seam')?></th>
            <th><?=Yii::t('app', 'Time')?></th>
        </tr>
        </thead>
        <tbody class="container-rooms">
        <?php foreach ($modelsRoom as $indexRoom => $modelRoom): ?>
            <tr class="room-item" style="<?php if($modelHouse['status'] == \app\modules\base\models\BaseMethod::STATUS_SAVED) echo 'background: lightgreen; ';?>">
                <td class="vcenter" style="width: 70%">
                    <?php
                    // necessary for update action.
                    if (! $modelRoom->isNewRecord) {
                        echo Html::activeHiddenInput($modelRoom, "[{$indexHouse}][{$indexRoom}{$time}]id");
                    }
                    ?>
                    <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]base_method_seam_id")->widget(\kartik\select2\Select2::class, [
                        'data' => ArrayHelper::map(BaseMethodSeam::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Base Method Seam'),
                            'class' => 'base-method-seam',
                            'readonly' => true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(false) ?>
                </td>
                <td>
                    <?= $form->field($modelRoom, "[{$indexHouse}][{$indexRoom}]time")->textInput(['readonly' => true])->label(false) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php DynamicFormWidget::end(); ?>


