<?php

use app\modules\base\models\BaseModelDocumentItems;
use app\modules\base\models\ModelsList;
use kartik\widgets\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseModelDocument */
/* @var $sizes \app\modules\base\models\BaseModelSizes */
/* @var $note \app\modules\base\models\BaseModelTikuvNote */
/* @var $pluginOptionsTable */
/* @var $pluginOptionsTikuv */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Model Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="base-model-document-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('base-model-document/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('base-model-document/delete')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'doc_number',
            'date',
            'model_id',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\BaseModelDocument::getStatusList($model->status))?app\modules\base\models\BaseModelDocument::getStatusList($model->status):$model->status;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model->updated_at<(60*60*24))?Yii::$app->formatter->format(date($model->updated_at), 'relativeTime'):date('d.m.Y H:i',$model->updated_at);
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                }
            ],
            'updated_by',
        ],
    ]) ?>

</div>

<div class="base-model-document-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row">
        <div class="col-md-12">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items',
                'widgetItem' => '.house-item',
                'limit' => 100,
                'min' => 1,
                'insertButton' => '.add-house',
                'deleteButton' => '.remove-house',
                'model' => $sizes[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'add_info',
                    'tikuv_file',
                    'table_file',
                ],
            ]); ?>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><?=Yii::t('app', 'Size Select')?></th>
                    <th style="width: 70%;"><?=Yii::t('app', 'Tikuv ta\'limoti izoh')?></th>
                </tr>
                </thead>
                <tbody class="container-items">
                <?php foreach ($sizes as $indexHouse => $modelHouse): ?>
                    <tr class="house-item" style="border-top: 30px solid #3c8dbc; border-left: 3px solid #3c8dbc; border-right: 3px solid #3c8dbc; border-bottom: 5px solid #3c8dbc;
    <?php
        $model = BaseModelDocumentItems::findOne($modelHouse['doc_items_id']);
        if($model && $model->status == BaseModelDocumentItems::STATUS_SAVED){
            echo "background: lightgreen";
        }
    ?>
">
                        <td class="vcenter" style="width: 50%">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($modelHouse, "[{$indexHouse}]size_id")->widget(Select2::className(),[
                                        'data' => ArrayHelper::map(\app\models\Size::find()->all(), 'id', 'name'),
                                        'options' => [
                                            'placeholder' => Yii::t('app', 'Size Select'),
                                            'multiple' => true,
                                            'readonly' => true
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                        'pluginEvents' => [
                                            "change" => "function() { console.log('change'); }",]
                                    ]) ?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($modelHouse, "[{$indexHouse}]add_info")->textarea(['rows' => 1, 'columns' => 5, 'readonly' => true])?>
                                </div>
                                <div class="col-md-12">
                                    <?php
                                    if($pluginOptionsTable && $pluginOptionsTable[$indexHouse]){
                                        echo "<div class='parentDiv'><strong>".Yii::t('app', 'Yuklangan table fayllar: ')."</strong><br>";
                                        foreach ($pluginOptionsTable[$indexHouse] as $key => $item) {
                                            if(is_int($key)){
                                                ?>
                                                <div>
                                                    <span class="text-black"><?=substr($item, '0', '50')?></span>
                                                    <a href="<?=$item; ?>" class="btn btn-success btn-xs eye-open-tikuv" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                </div>
                                                <br>
                                                <?php
                                            }
                                        }
                                        echo "</div>";
                                    }
                                    ?>
                                </div>
                                <div class="col-md-12">
                                    <?php
                                    if($pluginOptionsTikuv && $pluginOptionsTikuv[$indexHouse]){
                                        echo "<div class='parentDiv'><strong>".Yii::t('app', 'Yuklangan tikuv fayllar: ')."</strong><br>";
                                        foreach ($pluginOptionsTikuv[$indexHouse] as $k => $item) {
                                            if(is_int($k)){
                                                ?>
                                                <div>
                                                    <span class="text-black"><?=substr($item, '0', '50')?></span>
                                                    <a href="<?=$item; ?>" class="btn btn-success btn-xs eye-open-tikuv" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
                                                </div>
                                                <br>
                                                <?php
                                            }
                                        }
                                        echo "</div>";
                                    }
                                    ?>
                                </div>
                            </div>

                        </td>
                        <td style="width: 30%">
                            <?= $this->render('_view/_form-rooms', [
                                'form' => $form,
                                'indexHouse' => $indexHouse,
                                'modelsRoom' => $note[$indexHouse],
                                'modelHouse' => $modelHouse,
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
