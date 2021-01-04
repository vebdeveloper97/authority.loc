<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $isModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Variations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$data = Yii::$app->request->get();
$active = ($data['active'])?$data['active']:'colors';
$num = ($data['num'])?$data['num']:'';
\yii\web\YiiAsset::register($this);
?>
<div class="models-variations-view">

    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('models-variations/update')): ?>
            <?php if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('models-variations/delete')): ?>
            <?php if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <h2><?=$model->name?></h2>
    <div id="viewVariation">
    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app','Variation colors'),
                'content' => $this->render('view/_colors', [
                    'colors' => $model->modelsVariationColors,
                ]),
                'active' => ($active=='colors')?true:false,
                'options' =>[
                    'style' => 'padding-top:5px',
                    'id' => 'colors'.$num
                ]
            ],
            [
                'label' => Yii::t('app','Variation attachments'),
                'content' => $this->render('view/_attachments', [
                    'attachments' => $model->modelVarRelAttaches,
                ]),
                'active' => ($active=='attachments')?true:false,
                'options' =>[
                    'id' => 'attachments'.$num,
                ]
            ],
        ],
        'options' =>[
            'style' => (Yii::$app->request->isAjax)?'margin-top:-18px':'padding-top:5px',
            'id' => 'new'
        ]
    ]);?>
    </div>
</div>
