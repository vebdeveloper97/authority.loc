<?php

use app\modules\bichuv\models\BichuvDoc;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */

$this->title = Yii::t('app','№{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Preparation') . ' (' . Yii::t('app', 'Accept slice') .')' , 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tayyorlov-view">
    <?php if(!Yii::$app->request->isAjax){?>
        <div class="row">
            <div class="col-sm-12">
                <div class="pull-right" style="margin-bottom: 15px;">
                    <?php if (Yii::$app->user->can("tayyorlov/{$this->context->slug}/save-and-finish")): ?>
                        <?php  if ($model->status < $model::STATUS_SAVED): ?>
                            <?= Html::a(
                                Yii::t(
                                    'app',
                                    '{message}',
                                    ['message' => $model->document_type == BichuvDoc::DOC_TYPE_ACCEPTED ? 'Qabul qilish' : 'Saqlash va tugatish']),
                                ['save-and-finish', 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-success']
                            ); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($model->status != $model::STATUS_SAVED && Yii::$app->user->can('tayyorlov/qabul_kesim/update')): ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-primary btn-flat']) ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('tayyorlov/delete')): ?>
                        <?php  if ($model->status != $model::STATUS_SAVED): ?>
                            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 'slug' => $this->context->slug], [
                                'class' => 'btn btn-danger btn-flat',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?=  Html::a(Yii::t('app', 'Back'), ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info btn-flat']) ?>
                </div>
            </div>
        </div>
    <?php }?>

    <!--  begin Document  -->
    <div class="box box-info">
        <div class="box-body">
            <table class="table table-bordered table-responsive">
                <tr>
                    <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromHrDepartment->name ?></td>
                    <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->toHrDepartment->name ?></td>
                </tr>
                <tr>
                    <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromHrEmployee->fish ?></td>
                    <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toHrEmployee->fish ?></td>
                </tr>
                <tr>
                    <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
                    <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
                </tr>
                <tr>
                    <td colspan="2"><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
                </tr>
            </table>
        </div>
    </div>
    <!--  end Document  -->


    <div class="center-text">
        <?php
        $items = $model->getSliceMovingViewOld($model->id);
        ?>

        <!--  begin Slices  -->
        <div class="box box-info">
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th><?= Yii::t('app','Nastel Party');?></th>
                        <th><?= Yii::t('app',"Model");?></th>
                        <th><?= Yii::t('app',"Variant");?></th>
                        <th><?= Yii::t('app',"O'lcham");?></th>
                        <th><?= Yii::t('app','Quantity (piece)');?></th>
                        <th><?= Yii::t('app','Fact quantity (piece)');?></th>
                        <?php if (false): ?>
                            <th><?= Yii::t('app',"O'rtacha ish og'irligi (gr)");?></th>
                            <th><?= Yii::t('app','Miqdori(kg)');?></th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalRoll = 0;
                    $totalKg = 0;
                    $totalFactKg = 0;
                    foreach ($items as $key=> $item):?>
                        <tr>
                            <td><?= ($key+1);?></td>
                            <td class="expand-party">
                                <?= $item['nastel_party'] ?>
                            </td>
                            <td><?= $item['model'];?></td>
                            <td><?= $item['variation'];?></td>
                            <td><?= $item['name'];?></td>
                            <td><?= number_format($item['quantity'],0);?></td>
                            <td><?= number_format($item['fact_quantity'],0);?></td>
                            <?php if (false): ?>
                                <td><?= number_format($item['work_weight'],0)?></td>
                                <td><?= $item['quantity']*$item['work_weight']/1000;?></td>
                            <?php endif; ?>
                        </tr>
                        <?php
                        $totalKg += $item['quantity'];
                        $totalFactKg += $item['fact_quantity'];
                        $totalRoll += $item['quantity']*$item['work_weight']/1000;
                    endforeach;?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                        <td class="text-bold"><?= $totalKg; ?></td>
                        <td class="text-bold"><?= $totalFactKg; ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!--  end Slices  -->
    </div>

    <!--  begin Accessories  -->
    <div class="box box-info">
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th style="text-align: left"><?= Yii::t('app', 'Aksessuar'); ?></th>
                    <th><?= Yii::t('app', 'Miqdori'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $totalKg = 0;
                $aks = $model->aks;
                ?>
                <?php if ($aks): ?>
                    <?php foreach ($aks as $key => $item):?>
                        <tr>
                            <td><?= ($key + 1); ?></td>
                            <td style="text-align: left">
                                <?= $item['aks']; ?>
                            </td>
                            <td contenteditable="true"><?=number_format($item['qty'], 0) ?> (<?=$item['unit_name']?>)</td>
                        </tr>
                        <?php
                        $totalKg += $item['qty'];?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
                <!--<tfoot>
                        <tr>
                            <td></td>
                            <td class="left-text text-bold" style="text-align: left"> </td>
                            <td class="text-bold"> </td>
                        </tr>
                    </tfoot>-->
            </table>
        </div>
    </div>
    <!--  end Accessories  -->

    <?php if ($model->status == BichuvDoc::STATUS_SAVED && !Yii::$app->getRequest()->isAjax): ?>
        <?php if (!$model->childDoc): ?>
            <?= Html::a(
                "Aksessuar so'rovi",
                ['create', 'id' => $model->id, 'parent_id' => 1, 'slug' => 'query_acs'],
                [
                    'class' => 'btn bg-purple btn-flat',
                ]
            ) ?>
        <?php else: ?>
            <?= Html::a(
                "Ko'rish",
                ['tayyorlov/query_acs/view', 'id' => $model->childDoc->id],
                [
                    'class' => 'btn btn-warning btn-flat',
                ]
            ) ?>
        <?php endif; ?>
    <?php endif; ?>

</div>
