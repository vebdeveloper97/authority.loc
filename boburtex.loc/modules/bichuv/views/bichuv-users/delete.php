<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 18.01.20 13:39
 */



/* @var $this \yii\web\View */
/* @var $model \app\models\Users */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = Yii::t('app', 'Delete');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_fio, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Delete');
?>
<?php $form = ActiveForm::begin(['options' => [
    'data-pjax' => true,
    'class'=> 'customAjaxForm'
]]); ?>
<div class="row">
    <div class="col-md-12 text-center">
        <h2>
            <?=$model->user_fio?>
        </h2>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <?= $form->field($model, 'add_info')->textarea(['rows' => 2,'value'=>''])->label(Yii::t('app', "O'chirish sababini yozing")) ?>
        <?= Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
