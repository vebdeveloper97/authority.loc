<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->user_fio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-users-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('bichuv-users/update')): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?php if($model->status == \app\models\Users::DELETED){?>
        <h2><?=$model->user_fio?> <b><?=date('d.m.Y', strtotime($model->deleted_time))?></b> da ishdan haydalgan</h2>
        <small>Sababi : <b><?=$model->add_info?></b></small>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'username',
            'user_fio',
            'lavozimi',
            [
                'attribute' => 'user_role',
                'value' => function($model){
                    return $model->userRole['role_name'];
                }
            ],
        ],
    ]) ?>
    <?= DetailView::widget([
        'model' => $info,
        'attributes' => [
            'smena',
            'tabel',
            'razryad',
            'lavozim',
            'tel',
            'adress',
            'rfid_key',
            'add_info:ntext',
        ],
    ]) ?>
    <?php if (Yii::$app->user->can('toquv-users/update')): ?>
        <?= Html::a(Yii::t('app', Yii::t('app', 'Delete')), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger default_button',
                'default-url' => 'delete',
                'data-form-id' => $model->id,
            ]) ?>
    <?php endif; ?>
</div>
