<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\base\models\BasePatterns;
use app\components\PermissionHelper as P;
/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\BasePatterns|\yii\db\ActiveRecord */

?>
<div class="base-patterns-view">
    <?php if (!Yii::$app->request->isAjax ) { ?>
        <div class="pull-right" style="margin-bottom: 15px;">
            <a href="<?=\yii\helpers\Url::to(['orders-pattern', 'id' => $model->id])?>" data-target="#orders_patterns" data-toggle="modal" class="btn btn-success btn-sm"><?=Yii::t('app', 'Buyurtmaga biriktirish')?></a>
            <?php if ($model->status != $model::STATUS_SAVED):?>

                <?php if (P::can('base-patterns/update')): ?>
                     <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Save and finish'), ['save-and-finish', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if (P::can('base-patterns/delete')): ?>
                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                <?php endif; ?>

            <?php endif;?>
        </div>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'constructor_id',
                'value' => function ($model) {
                    return $model->constructor_id ? $model->constructor->fish : '';
                }
            ],
            [
                'attribute' => 'brend_id',
                'value' => function ($model) {
                    return $model->getEntityList(\app\modules\base\models\Brend::className(), $model->brend_id);
                }
            ],
            [
                'attribute' => 'model_type_id',
                'value' => function ($model) {
                    return $model->getEntityList(\app\modules\base\models\ModelTypes::className(), $model->model_type_id);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $stts = app\modules\base\models\BasePatterns::getStatusList($model->status);
                    return $stts ? $stts : $model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return (\app\models\Users::findOne($model->created_by)) ? \app\models\Users::findOne($model->created_by)->user_fio : $model->created_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return (time() - $model->created_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->created_at), 'relativeTime') : date('d.m.Y H:i', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return (time() - $model->updated_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->updated_at), 'relativeTime') : date('d.m.Y H:i', $model->updated_at);
                }
            ],
        ],
    ]) ?>

    <h4><?= Yii::t('app','Qolip rasmlari');?></h4>

    <?php
        $attachments = $model->basePatternRelAttachments;
        $img = $model->getImages($attachments);
    ;?>

    <?php if(!empty($img)):?>
    <div class="row">
        <?php foreach ($img as $attachment):?>
            <div class="col-md-3">
                <div class="thumbnail">
                    <img src="<?= $attachment['path']?>" alt="IMG" class="thumbnail imgPreview round"/>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>

    <?php $postals = $model->basePatternMiniPostal;?>
    <?php if(!empty($postals)):?>
    <fieldset><h4><?= Yii::t('app','Mini postal');?></h4></fieldset>
    <div class="parentDiv">
        <?php
        if(!empty($postals)):?>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>
                            <span><?php echo Yii::t('app',"O'lchamlar")?></span>
                        </th>
                        <th>
                            <span><?php echo Yii::t('app',"Fayl")?></span>
                        </th>
                    </tr>
                </thead>
                <?php foreach ($postals as $key => $postal) :
                    ?>
                <tr>
                    <th>
                        <?php if(!empty($postal->basePatternMiniPostalSizes)){
                            foreach ($postal->basePatternMiniPostalSizes as $basePatternMiniPostalSize) {?>
                                <code><?=$basePatternMiniPostalSize->size->name?></code>
                            <?php }
                        }?>
                    </th>
                    <th>
                        <?php if($postal['extension']=='image/jpeg' || $postal['extension']=='image/png' || $postal['extension']=='image/jpg'){
                            echo Html::img('/web/'.$postal['path'],['style'=>'width:40px','class'=>'imgPreview']);
                        }else {
                            echo "<a href='/".$postal['path']."' target='_blank'>".$postal['name']."</a>";
                        }?>
                    </th>
                </tr>
                <?php endforeach;?>
            </table>
        <?php endif;?>
    </div>
    <?php endif;?>

    <fieldset><h4><?= Yii::t('app','Qolip fayllari');?></h4></fieldset>
    <?php $attachments = $model->basePatternRelFiles;?>
    <div class="panel panel-default">
        <div class="panel-body" style="margin-bottom: 2px;padding: 0;">
    <?php if(!empty($attachments)):?>
        <div class="row">
            <?php foreach ($attachments as $attachment):?>
                <div class="col-md-3">
                    <div class="thumbnail">
                        <a href="/<?= $attachment['path']?>" class="img-responsive"> <?=$attachment->attachment->name?> </a>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    <?php endif;?>
        </div>
    </div>




</div>
<?php
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);