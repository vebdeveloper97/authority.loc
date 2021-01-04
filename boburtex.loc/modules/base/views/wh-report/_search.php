<?php

    use kartik\date\DatePicker;
    use kartik\depdrop\DepDrop;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\ActiveForm;

    /* @var $this yii\web\View */
    /* @var $model \app\modules\base\models\WhItemBalanceSearch */
    /* @var $form yii\widgets\ActiveForm */
    /* @var $data array */
?>

<div class="bichuv-item-balance-search">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['wh-report/index']),
        'method' => 'get',
        'id' => 'ip-search-form'
    ]); ?>

    <div class="row">
        <div class="col-md-6">

                    <label class="control-label"><?= Yii::t('app', "Sana"); ?></label>
                    <?= DatePicker::widget([
                        'model' => $model,
                        'language' => 'ru',
                        'attribute' => 'date',
                        'options' => ['placeholder' => Yii::t('app', 'End Date'), 'value' => $data['date'] ? $data['date'] : date('d.m.Y')],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND ,
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'autoclose' => true,
                        ]
                    ]);

                    ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'department_id')->dropDownList($model->getDeptList()) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'type_id')->widget(\kartik\select2\Select2::className(),[
                'data' =>  \app\modules\base\models\WhItemTypes::getList(),
                'options' => ['prompt'=>Yii::t('app', 'Tanlang'),'id'=>'wh_item_type'],
                'pluginOptions' => [
                    'allowClear' => true,
                    //'multiple' => true,
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                //'data' => (!$model->isNewRecord)?\app\modules\base\models\WhItemCategory::getList($model->id):'',
                'options'=>['id'=>'wh_item_category'],
                'pluginOptions'=>[
                    'depends'=>['wh_item_type'],
                    'placeholder'=>Yii::t('app', 'Tanlang'),
                    'url'=>\yii\helpers\Url::to('wh-item-category')
                ],
                'select2Options' => [
                        'options' => [
                            'allowClear' => true,
                        ]
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'entity_ids')->widget(Select2::className(),[
                'data' => $model->getEntities(),
                'options' => [
                    'allowClear' => true,
                    'multiple' => true,
                    'prompt' =>Yii::t('app','Barchasi')
                ]
            ])->label(Yii::t('app', 'Maxsulot')) ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12" style="margin-top: 25px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['wh-report/index']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => 1])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
