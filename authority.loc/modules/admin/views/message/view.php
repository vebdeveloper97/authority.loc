<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\MessageUz */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Message Uzs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="message-uz-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'content:ntext',
            'author',
            [
                'attribute' => 'date',
                'value' => function($model){
                    return date('m.d.Y', strtotime($model->date));
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){

                }
            ],
            [
                'attribute' => 'images',
                'value' => function($model){
                    $imgs = \app\modules\admin\models\MessageAttachmentsUz::find()->where(['message_id' => $model->id])->all();
                    if($imgs){
                        $str = "<div>";
                        foreach ($imgs as $img) {
                            $path = $img->attachments->path;
                             $str .= "<img width='50px' height='50px' src='".$path."'> ";
                        }
                        $str .= "</div>";
                        return $str;
                    }
                },
                'format' => 'html'
            ],
            'status',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return DateFormatter($model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return DateFormatter($model->updated_at);
                }
            ],
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
