<?php

use app\models\Constants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvInstructions */
/* @var array $insDoc */
/* @var int $orderId */
/* @var int $samo */
/* @var array $rmItems */
/* @var array $kaliteData */

$this->title = "Ko'rsatma№{$model->id} ({$model->getServiceTypes($model->is_service)}) ";
$this->params['breadcrumbs'][] = (!$model->model_orders_id)?['label' => Yii::t('app', 'Toquv Instructions'), 'url' => ['index']]:['label' => Yii::t('app', 'Toquv Model Instructions'), 'url' => ['model-orders']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-aks-instructions-view" id="print-barcode">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('toquv-aks-instructions/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ['save-and-finish', 'id' => $model->id, 'orderId' => $orderId], ['class' => 'btn btn-success']) ?>
                <?php
                if($model->notify!=2&&!$model->model_orders_id){?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'orderId' => $orderId], ['class' => 'btn btn-primary']); ?>
                <?php }elseif($model->model_orders_id){?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'orderId' => $orderId], ['class' => 'btn btn-primary']) ?>
                <?php }else{?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update-universal', 'id' => $model->id, 'orderId' => $orderId], ['class' => 'btn btn-primary']) ?>
                <?php }?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('toquv-aks-instructions/delete')): ?>
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
        <?php if(!$model->model_orders_id){?>
            <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
        <?php }else{?>
            <?=  Html::a(Yii::t('app', 'Back'), ["model-orders"], ['class' => 'btn btn-info']) ?>
        <?php }?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-barcode',]) ?>
    </div>

    <table class="table-bordered table">
        <tbody>
        <?php if($insDoc):?>
                <?php if($insDoc['model']){?>
                    <tr>
                        <td><?= Yii::t('app',"Model buyurtma raqami")?></td>
                        <td><?= $insDoc['model'];?></td>
                    </tr>
                <?php }?>
                <?php if($insDoc['order_musteri']){?>
                    <tr>
                        <td><?= Yii::t('app',"Model buyurtmachisi")?></td>
                        <td><?= $insDoc['order_musteri'];?></td>
                    </tr>
                <?php }?>
                <?php if($insDoc['mname']){?>
                    <tr>
                        <td><?= Yii::t('app',"Buyurtmachi")?></td>
                        <td><?= $insDoc['mname'];?></td>
                    </tr>
                <?php }?>
                <?php if($insDoc['doc']){?>
                    <tr>
                        <td><?= Yii::t('app',"Document Number")?></td>
                        <td><?= $insDoc['doc'];?></td>
                    </tr>
                <?php }?>
                <tr>
                    <td><?= Yii::t('app',"Ko'rsatma sanasi")?></td>
                    <td><?= date('d.m.Y' , strtotime($insDoc['reg_date']));?></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app',"Bajariladigan joy")?></td>
                    <td><?= $model->getServiceTypes($insDoc['is_service']);?></td>
                </tr>
                <?php if($model->is_service == 2):?>
                    <tr>
                        <td><?= Yii::t('app',"Yuborilgan kontragent")?></td>
                        <td><?= $model->musteri->name; ?></td>
                    </tr>
                <?php endif;?>
                <tr>
                    <td><?= Yii::t('app',"Yuborilgan bo'lim")?></td>
                    <td><?= $insDoc['dept']?></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app',"Mas'ul shaxslar")?></td>
                    <td><?= $insDoc['responsible_persons']?></td>
                </tr>
                <tr>
                    <td><?= Yii::t('app',"Izohlar")?></td>
                    <td><?= $insDoc['add_info']?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><?= Yii::t('app',"Ma'lumot mavjud emas!")?></td>
                </tr>
            <?php endif;?>
        </tbody>
    </table>

    <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma aksessuar ma'lumotlari")?>:</h4>
    <table class="table-bordered table table-middle">
        <thead>
        <tr>
            <th scope="col">№</th>
            <th scope="col"><?= Yii::t('app','Aksessuar')?></th>
            <th scope="col"><?= Yii::t('app', 'Rang') ?></th>
            <th scope="col"><?= Yii::t('app', "Rang(Bo'yoq)") ?></th>
            <th scope="col"><?= Yii::t('app','Order Quantity')?></th>
            <th scope="col"><?= Yii::t('app','Quantity')?></th>
            <th scope="col"><?= Yii::t('app','Count')?></th>
            <th scope="col"><?= Yii::t('app','Pus/Fine')?></th>
            <th scope="col"><?= Yii::t('app','Done Date')?></th>
            <th scope="col"><?= Yii::t('app', 'Uzunligi (Buyurtma)') ?></th>
            <th scope="col"><?= Yii::t('app', 'Uzunligi') ?></th>
            <th scope="col"><?= Yii::t('app', "Eni (Buyurtma)") ?></th>
            <th scope="col"><?= Yii::t('app', "Eni") ?></th>
            <th scope="col"><?= Yii::t('app', 'Qavati (Buyurtma)') ?></th>
            <th scope="col"><?= Yii::t('app', 'Qavati') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1;
        foreach ($rmItems as $key=>$item):?>
            <tr>
                <td><?= $count;?></td>
                <td><?= $item['mato_color'] ?> <?= $item['mato'] ?></td>
                <td>
                    <?=($item['color_pantone_id'])?"<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'></span></span>".$item['ccode']:""?>
                </td>
                <td>
                    <?=($item['color_id'])?"<span style='background:{$item['cl_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>rgb</span></span> ".$item['cl_name'] :""?>
                </td>
                <td>
                    <?= $item['order_quantity'];?>
                </td>
                <td>
                    <?= $item['quantity'] ?>
                </td>
                <td>
                    <?= $item['order_count'] ?>
                </td>
                <td><?= $item['pf']; ?></td>
                <td><?= date('d.m.Y', strtotime($item['done_date'])); ?></td>
                <td><?= $item['order_thread_length'] ?></td>
                <td><?= $item['thread_length'] ?></td>
                <td><?= $item['order_finish_en'] ?></td>
                <td><?= $item['finish_en'] ?></td>
                <td><?= $item['order_finish_gramaj'] ?></td>
                <td><?= $item['finish_gramaj'] ?></td>
            </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>

    <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma ip ma'lumotlari")?>:</h4>
    <table class="table-bordered table table-middle">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app',"Buyurtmachi")?></th>
            <th><?= Yii::t('app','Aksessuar nomi va miqdori')?></th>
            <th><?= Yii::t('app','Ip turi')?></th>
            <th><?= Yii::t('app','Ip miqdori(Hujjat,kg)')?></th>
            <th><?= Yii::t('app','Ip miqdori(Fakt,kg)')?></th>
            <th><?= Yii::t('app','Izoh')?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1;
        foreach ($items as $key=>$item):?>
            <tr>
                <td><?= $count;?></td>
                <td><?= $item['mname'] ?></td>
                <td><?= $item['mato_color'] ?> <b><?= $item['mato'] ."</b> - <span class='material_" . $item['tro_id'] . "'>" . $item['qty'] . "</span> kg" ?></td>
                <td><?= $item['ip'] ?></td>
                <td><?= $item['ipqty'] ?></td>
                <td><?= $item['fact'] ?></td>
                <td><?= $item['comment'] ?></td>
            </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>

    <div class="kalite-rm no-print">
        <h4><?= Yii::t('app',"Kalitedan o'tgan matolar")?>:</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th scope="col"><?= Yii::t('app', 'Mato') ?></th>
                <th scope="col"><?= Yii::t('app', 'Pus/Fine') ?></th>
                <th scope="col"><?= Yii::t('app', "To'quv masteri") ?></th>
                <th scope="col"><?= Yii::t('app', "Quantity") ?></th>
                <th scope="col"><?= Yii::t('app', "Navi") ?></th>
                <th scope="col"><?= Yii::t('app', "Yangilangan vaqt") ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $total = 0;
            foreach ($kaliteData as $key => $item): ?>
                <tr>
                    <td><?= ($key + 1); ?></td>
                    <td><?= $item['mato'] ?></td>
                    <td><?= $item['pus_fine'] ?></td>
                    <td><?= $item['user_fio'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['sortName'] ?></td>
                    <td><?= (time() - $item['created_at'] < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($item['created_at']), 'relativeTime') : date('d.m.Y H:i', $item['created_at']) ?></td>
                </tr>
                <?php $total += $item['quantity']; endforeach; ?>
            </tbody>
            <thead>
            <tr>
                <th colspan="3"><?= Yii::t('app', 'Jami') ?></th>
                <th></th>
                <th style="font-weight: bold;" class="text-danger"><?= number_format($total, 3, '.', ' '); ?></th>
                <th colspan="2"></th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="moving-box">

    </div>
</div>
<?php
$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/bichuv-acs-barcode.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);