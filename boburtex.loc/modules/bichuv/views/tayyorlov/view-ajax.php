<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyorlovs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tayyorlov-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('tayyorlov/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('tayyorlov/delete')): ?>
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
                'attribute' => 'from_department',
                'label' => Yii::t('app','Qayerdan'),
                'value' => function ($model) {
                    return $model->fromDepartment->name;
                }
            ],
            [
                'attribute' => 'from_employee',
                'label' => Yii::t('app','Javobgar shaxs'),
                'value' => function ($model) {
                    return $model->fromEmployee->user_fio;
                }
            ],
            /*[
                'attribute' => 'nastel_table_no',
                'label' => Yii::t('app', 'Nastel stol raqami'),
                'value' => function ($model) {
                    return $model->nastel_table_no;
                }
            ],*/
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Kimga'),
                'value' => function ($model) {
                    return $model->toDepartment->name;
                }
            ],
            [
                'attribute' => 'to_employee',
                'label' => Yii::t('app','Javobgar shaxs'),
                'value' => function ($model) {
                    return $model->toEmployee->user_fio;
                }
            ],
//            'id',
//            'document_type',
//            'action',
//            'doc_number',
//            'reg_date',
//            'musteri_id',
//            'musteri_responsible',
//            'to_department',
//            'to_employee',
//            'parent_id',
            'add_info:ntext',
            /*[
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\bichuv\models\BichuvDoc::getStatusList($model->status))?app\modules\bichuv\models\BichuvDoc::getStatusList($model->status):$model->status;
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
            ],*/
            /*'payment_method',
            'paid_amount',
            'pb_id',
            'type',
            'size_collection_id',
            'rag',
            'work_weight',
            'toquv_doc_id',
            'slice_weight',
            'total_weight',
            'is_returned',
            'nastel_table_no',
            'nastel_table_worker',
            'service_musteri_id',
            'deadline',
            'is_service',
            'bichuv_mato_orders_id',
            'models_list_id',
            'model_var_id',*/
        ],
    ]) ?>

    <div class="center-text">
        <?php
        $items = $model->getSliceMovingViewOld($model->id);
        ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app',"Model");?></th>
                <th><?= Yii::t('app',"O'lcham");?></th>
                <th><?= Yii::t('app','Soni');?></th>
                <th><?= Yii::t('app',"O'rtacha ish og'irligi (gr)");?></th>
                <th><?= Yii::t('app','Miqdori(kg)');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalRoll = 0;
            $totalKg = 0;
            foreach ($items as $key=> $item):?>
                <tr>
                    <td><?= ($key+1);?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party']  ?>
                    </td>
                    <td><?= $item['model'];?></td>
                    <td><?= $item['name'];?></td>
                    <td><?= number_format($item['quantity'],0);?></td>
                    <td><?= number_format($item['work_weight'],0)?></td>
                    <td><?= $item['quantity']*$item['work_weight']/1000;?></td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
                $totalRoll += $item['quantity']*$item['work_weight']/1000;
            endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td></td>
                <td class="text-bold"><?= $totalRoll?></td>
            </tr>
            </tfoot>
        </table>
    </div>

</div>
