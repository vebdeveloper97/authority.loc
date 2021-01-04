<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.04.20 15:38
 */

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelsList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrdersSearch */
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to('index'),
    'method' => 'get',
    'id' => 'ip-search-form',
    'options' => ['data-pjax' => true]
]); ?>
<div class="row">
    <div class="col-md-6">
        <div class="col-md-4">
            <?= $form->field($model, 'artikul')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'pantone')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'doc_number')->textInput() ?>
        </div>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'musteri_id')->widget(\kartik\select2\Select2::className(), [
            'data' => \app\modules\base\models\Musteri::getList(),
            'language' => 'ru',
            'options' => [
                'prompt' => '',
                'id' => 'musteri_list'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'status')->dropDownList(ModelOrders::getStatusList(),['prompt'=>'']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'created_by')->widget(\kartik\select2\Select2::className(), [
            'data' => ModelOrders::getAuthorList(),
            'language' => 'ru',
            'options' => [
                'prompt' => '',
                'id' => 'personal_list'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'updated_by')->widget(\kartik\select2\Select2::className(), [
            'data' => ModelOrders::getAuthorList(),
            'language' => 'ru',
            'options' => [
                'prompt' => '',
                'id' => 'updated_list'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'add_info')->textInput() ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'brend')->dropDownList(ModelsList::getAllBrend(),['prompt'=>'']) ?>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6" style="margin-top: 25px;">
        <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
        <?php $url = Url::to(['index'])?>
        <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
    </div>
    <!--<div class="col-md-3">
        <?/*= $form->field($model, 'per_page')->textInput(['name'=>'per-page','value'=>($_GET['per-page'])?$_GET['per-page']:20]) */?>
    </div>-->
    <div class="col-md-3">

    </div>
</div>
<?php ActiveForm::end(); ?>