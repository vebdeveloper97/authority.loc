<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 21.06.20 13:20
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvReportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usluga-report-search">
    <?php
        $url = $url ?? 'index';
        $form = ActiveForm::begin([
            'action' => [$url],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]);
    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-3">
                    <?= $form->field($model, 'musteri_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => $model->getMusteris(),
                        'language' => 'ru',
                        'options' => [
                            'id' => 'musteri_list',
                            'multiple' => true
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'department_id')->dropDownList($model->getDepartmentByToken(['USLUGA','TIKUV_2_FLOOR','TIKUV_3_FLOOR'],true),['class'=>'select3','multiple'=>true,'id'=>'dept_filter']) ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'from_musteri')->dropDownList($model->getMusteries(null,3),['class'=>'select3','multiple'=>true,'id'=>'from_mus_filter','style'=>($model->department_id!==\app\modules\toquv\models\ToquvDepartments::findOne(['token'=>'USLUGA'])['id'])?'block':'none']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'nastel_no')->textarea() ?>
                </div>
                <div class="col-md-3">
                    <?php echo $form->field($model, 'model')->textInput() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'model_var')->label(Yii::t('app', 'Rang kodi'))?>
                </div>
            </div>
        </div>
    </div>

    <?php /* echo $model->scenario */?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-sm btn-primary']) ?>
        <?php $url = $url ?? 'index';?>
        <?= Html::a(Yii::t('app', 'Filtrni tozalash'), [$url],
            ['class' => 'index btn btn-sm btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
