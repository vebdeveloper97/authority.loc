<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Goods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Barcodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="barcode-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('barcode/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('barcode/delete')): ?>
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
            [
                'attribute' => 'barcode',
                'value' => function($model){
                    return "<form target='_blank' action='".Yii::$app->urlManager->createUrl(['base/barcode/barcode-generate','id'=>$model->id])."' method='GET'>"
                        ."<input type='hidden' name='id' value='".$model->id."'><div class='row' style='margin:0'><div class='col-md-9' style='padding:0'><input type='text' class='number form-control customDisabled' name='barcode' value='".$model->barcode."'></div><div class='col-md-1' style='padding:0 10px'><button type='submit' class='btn btn-xs btn-default barcode-dialog'><span class=\"glyphicon glyphicon-barcode\"></span></button></div></div>"
                        ."</form>";
                },
                'headerOptions' => [
                    'style' => 'width:140px'
                ],
                'format' => 'raw'
            ],
            [
                'attribute' => 'barcode1',
                'value' => function($model){
                    $hidden = (!empty($model->barcode1))?'':'hidden';
                    return "<form target='_blank' action='".Yii::$app->urlManager->createUrl(['base/barcode/barcode-generate','id'=>$model->id])."' method='GET'>"
                        ."<input type='hidden' name='id' value='".$model->id."'><div class='row' style='margin:0'><div class='col-md-9' style='padding:0'><input type='text' data-model='{$model->id}' class='number barcodeEdit form-control targetInput' name='barcode' value='{$model->barcode1}'></div><div class='col-md-1 ".$hidden." barcodeDiv' style='padding:0 10px'><button type='submit' class='btn btn-xs btn-default barcode-dialog'><span class=\"glyphicon glyphicon-barcode\"></span></button></div></div>"
                        ."</form>";
                },
                'headerOptions' => [
                    'style' => 'width:140px'
                ],
                'format' => 'raw'
            ],
            'model_no',
            [
                'attribute' => 'model_id',
                'value' => function($model){
                    return $model->name;
                },
            ],
            [
                'attribute' => 'size',
                'value' => function($model){
                    return $model->sizeName->name;
                }
            ],
            [
                'attribute' => 'consist',
                'label' => Yii::t('app','Tarkibi'),
                'value' => function($model){
                    return $model->model->rmConsist;
                }
            ],
            [
                'attribute' => 'color',
                'value' => function($model){
                    return '<div class="btn btn-default" style="margin-right: 5px;width: 100%;">
                <div style=""><span
                            class="badge pull-left"><?=$i?></span><b>'.$model->colorPantone->code.'</b></div>
                <span class="btn" style="background: rgb('.$model->colorPantone['r'].','.$model->colorPantone['g'].','.$model->colorPantone['b'].');width: 90%">
                    <span style="opacity: 0;">
                        <span class="badge">
                            r : '.$model->colorPantone['r'].'
                        </span>
                        <span class="badge">
                            g : '.$model->colorPantone['g'].'
                        </span>
                        <span class="badge">
                            b : '.$model->colorPantone['b'].'
                        </span>
                    </span>
                </span>
            </div>';
                },
                'format' => 'raw'
            ],
            'name',
            //'old_name',
            [
                'attribute' => 'category',
                'value' => function($model){
                    return $model->model->type->name;
                }
            ],
            [
                'attribute' => 'sub_category',
                'value' => function($model){
                    return $model->model->typeChild->name;
                }
            ],
            [
                'attribute' => 'model_type',
                'value' => function($model){
                    return $model->model->view->name;
                }
            ],
            [
                'attribute' => 'season',
                'value' => function($model){
                    return $model->model->modelSeason->name;
                }
            ],
        ],
    ]) ?>

</div>
