<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\MatoInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mato-info-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'musteri_id',
                'value' => function($m){
                    return $m->musteri->name;
                },
                'headerOptions' => [
                    'style' => 'min-width:150px'
                ]
            ],
            [
                'attribute' => 'entity_id',
                'value' => function($m){
                    return $m->entity->name;
                },
                'label' => Yii::t('app', 'Aksesuar'),
                'headerOptions' => [
                    'style' => 'min-width:200px'
                ]
            ],
//            'entity_type',
            [
                'attribute' => 'pus_fine_id',
                'value' => function($m){
                    return $m->pusFine->name;
                },
            ],
            [
                'attribute' => 'thread_length',
                "label" => Yii::t('app', 'Uzunligi'),
                'value' => function($m){
                    return $m->thread_length;
                },
            ],
            [
                'attribute' => 'finish_en',
                "label" => Yii::t('app', "Eni"),
                'value' => function($m){
                    return $m->finish_en;
                },
            ],
            [
                'attribute' => 'finish_gramaj',
                "label" => Yii::t('app', 'Qavati'),
                'value' => function($m){
                    return $m->finish_gramaj;
                },
            ],
            /*'thread_length',
            'finish_en',
            'finish_gramaj',
            [
                'attribute' => 'type_weaving',
                'value' => function($m){
                    return $m->typeWeaving->name;
                },
            ],*/
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\toquv\models\MatoInfo::getStatusList($model->status))?app\modules\toquv\models\MatoInfo::getStatusList($model->status):$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
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
        ],
    ]) ?>

</div>
