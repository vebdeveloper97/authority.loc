<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $rawMaterials \app\modules\base\models\ModelOrdersItemsMaterial */
/* @var $list */
/* @var $isModel */
/* @var $pechat \app\modules\base\models\ModelsPechat */
/* @var $naqsh \app\modules\base\models\ModelsNaqsh */
$isModel = $isModel?$isModel:false;

$this->title = $model->article;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
        <div id="modelList" class="models-list-view">
            <?php if(!Yii::$app->request->isAjax):?>
            <div class="row" style="margin: 5px 10px;">
                <div class="pull-right">
                    <?php if (Yii::$app->user->can('models-list/activate')): ?>
                        <?= Html::a(Yii::t('app', 'Tasdiqlash'), ['activate', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('models-list/update')): ?>
                        <?php if ($model->status != $model::STATUS_SAVED || Yii::$app->user->can('models-list/activate')): ?>
                            <?php if(isset($list)): ?>
                                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'list' => $list], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?php else: ?>
                                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('models-list/delete') && Yii::$app->user->can('models-list/activate')): ?>
                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                    <?= Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-default btn-sm']) ?>
                </div>
            </div>
            <?php endif;?>
            <div style="margin: 5px 10px;">
                <?= Tabs::widget([
                    'items' => [
                        [
                            'label' => Yii::t('app','Main Information'),
                            'content' => $this->render('view/_models', [
                                'model' => $model,
                            ]),
                            'active' => true
                        ],
                        [
                            'label' => Yii::t('app','Raw materials and Acsessuars'),
                            'content' => $this->render('view/_materials', [
                                'model' => $model,
                                'rawMaterials' => $rawMaterials,
                                'acs' => $acs
                            ]),
                        ],
                        [
                            'label' => Yii::t('app','Variations'),
                            'content' => $this->render('view/_variations', [
                                'variations' => $model->modelsVariations, 'isModel' => $isModel, 'model' => $model,
                            ]),
                        ],
                        [
                            'label' => Yii::t('app','Qolip'),
                            'content' => $this->render('view/_base_pattern', [
                                'model' => ($model->basePattern) ? $model->basePattern : null,
                            ])
                        ],
                        [
                            'label' => Yii::t('app','Pechats'),
                            'content' => $this->render('view/_pechat', [
                                'pechat' => $pechat,
                            ])
                        ],
                        [
                            'label' => Yii::t('app','Naqsh'),
                            'content' => $this->render('view/_naqsh', [
                                'naqsh' => $naqsh,
                            ])
                        ],
                        [
                            'label' => Yii::t('app','Sketchs'),
                            'content' => $this->render('view/_sketch', [
                                'model' => $model,
                            ]),
                        ],
                        [
                            'label' => Yii::t('app','Measurements'),
                            'content' => $this->render('view/_measurement', [
                                'model' => $model,
                            ]),
                        ],
                        [
                            'label' => Yii::t('app','Others'),
                            'content' => $this->render('view/_others', [
                                'model' => $model,
                            ])
                        ],
                        [
                            'label' => Yii::t('app','Mini postal'),
                            'content' => $this->render('view/_mini_postal', [
                                'model' => $model,
                            ])
                        ],
                    ]
                ]);?>
            </div>
        </div>
<div id="model-variation-form">

</div>
<?php
$css = <<< Css
.file-preview-image {
    font: 20px Impact, Charcoal, sans-serif;
}
.fileinput-remove{
    display:none;
}
Css;
$this->registerCss($css);
