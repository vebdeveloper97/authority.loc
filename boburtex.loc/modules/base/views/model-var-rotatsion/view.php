<?php

    use app\modules\base\models\ModelVarRotatsion;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarRotatsion */
/* @var $attachments app\modules\base\models\ModelVarRotatsionRelAttach */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Rotatsions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="model-var-rotatsion-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('model-var-rotatsion/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('model-var-rotatsion/delete')): ?>
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
    <?php }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'name',
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                    $status = $model->status ? ModelVarRotatsion::getStatusList($model->status) : $model->status;
                    return $status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    $user = \app\models\Users::findOne($model->created_by);
                    return isset($user) ? $user->user_fio : $model->created_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('d.m.Y H:i',$model->updated_at);
                }
            ],
        ],
    ]) ?>
    <label>
        <?php echo Yii::t('app','Attachments')?>
    </label>
    <div class="multiple-input-list__item">
        <div class="field-modelvar-attachments form-group">
            <?php

                $i = 0;
            if (!empty($attachments)) :
            foreach ($attachments as $image){
                if($image->attachment['path']){?>
                    <img class="imgPreview" src="/web/<?=$image->attachment['path']?>" style="width: auto;height: 10vh;">
                <?php
                }
                $i++;
            }
            endif;

            ?>
        </div>
    </div>
</div>
