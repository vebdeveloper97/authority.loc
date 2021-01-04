<?php

use kartik\widgets\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethod */
/* @var $modelItems \app\modules\base\models\BaseMethodSizeItems  */
/* @var $modelItemsChild \app\modules\base\models\BaseMethodSizeItemsChilds */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="base-method-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('base-method/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('base-method/delete')): ?>
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
            [
                'attribute' => 'doc_number',
                'value' => function($model){
                    return "<strong>{$model['doc_number']}</strong>";
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'model_list_id',
                'value' => function($model){
                    return $model->modelList['article'].' ('.$model->modelList['name'].')';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'planning_hr_id',
                'value' => function($model){
                    return $model->planningHr['fish'];
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'model_hr_id',
                'value' => function($model){
                    return $model->modelHr['fish'];
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'master_id',
                'value' => function($model){
                    return $model->master['fish'];
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'etyud_id',
                'value' => function($model){
                    return $model->etyud['fish'];
                },
                'format' => 'raw'
            ],
            'date',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\BaseMethod::getStatusList($model->status))?app\modules\base\models\BaseMethod::getStatusList($model->status):$model->status;
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
    <?php
        $form = \yii\widgets\ActiveForm::begin();
    ?>
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
            <th><?=Yii::t('app', 'All Time')?></th>
        </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($modelItems as $indexHouse => $modelHouse): ?>
            <tr class="house-item">
                <td class="vcenter">
                    <?php
                    // necessary for update action.
                    if (! $modelHouse->isNewRecord) {
                        echo Html::activeHiddenInput($modelHouse, "[{$indexHouse}]id");
                    }
                    ?>
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
                </td>
                <td>
                    <?= $this->render('view/_items', [
                        'form' => $form,
                        'indexHouse' => $indexHouse,
                        'modelHouse' => $modelHouse,
                        'modelsRoom' => $modelItemsChild[$indexHouse],
                    ]) ?>
                </td>
                <td>
                    <?php
                    $allTime = 0;
                    foreach ($modelItemsChild[$indexHouse] as $item) {
                        $allTime = $allTime + $item['time'];
                    }?>
                    <input type="text" disabled="true" value="<?=$allTime?>">
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
<?php
$this->registerCss("
    html{
        zoom: 80%;
    }
");
?>
