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
                <?= $form->field($model, 'name')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'article')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'brend_id')->widget(\kartik\select2\Select2::className(), [
                    'data' => ModelsList::getAllBrend(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                        'id' => 'brend_id'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4">
                <?= $form->field($model, 'view_id')->dropDownList(ModelsList::getModelView(),['prompt'=>'']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'type_id')->dropDownList(ModelsList::getAllModelTypes(),['prompt'=>'']) ?>
            </div>
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
            <?= $form->field($model, 'status')->dropDownList(ModelOrders::getStatusList(),['prompt'=>'']) ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4" style="margin-top: 25px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
            <?php $url = Url::to(['index'])?>
            <?= Html::a('Filterni bekor qilish', $url, ['class' => 'btn btn-danger']) ?>
        </div>
        <!--<div class="col-md-3">

    </div>-->
    </div>
    <div class="row no-print" style="padding-left: 500px; margin-top:-25px;">
        <form action="<?=\yii\helpers\Url::current()?>" method="GET">
            <div class="">
                <div class="input-group" style="width: 500px;">
                    <label style="float: left; margin-left: 10px; margin-top:2px; "> <?=Yii::t('app','Ro\'yhat miqdori: ')?></label>
                    <input  type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=(isset($_GET['per-page'])?$_GET['per-page']:20)?>">
                    <label style="float: right" >
                        Qolip biriktirilgan
                        <input type="radio" name="qolip" value="1" <?=$model->qolip_true?>>
                    </label>
                    <label style="float:right; margin-right:15px;">
                        Qolip biriktirilmagan
                        <input type="radio" name="qolip" value="2" <?=$model->qolip_false?>>
                    </label>
<!--                    <span class="input-group-btn">-->
<!--                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;">--><?php //=Yii::t('app','Filtrlash')?><!--</button>-->
<!--                    </span>-->
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </form>
    </div>
<?php ActiveForm::end(); ?>