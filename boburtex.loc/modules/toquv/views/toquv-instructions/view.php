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
<div class="toquv-instructions-view">
    <div class="pull-right" style="margin-bottom: 15px;">
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        <?php if (Yii::$app->user->can('toquv-instructions/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
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
        <?php if (Yii::$app->user->can('toquv-instructions/delete')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
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
                <tr>
                    <td><?= Yii::t('app',"Kontragent")?></td>
                    <td><?=$model->toquvOrder->musteri->name?></td>
                </tr>
                <?php if ($model->toquvOrder->modelMusteri){?>
                <tr>
                    <td><?= Yii::t('app',"Model buyurtmachisi")?></td>
                    <td><?=$model->toquvOrder->modelMusteri->name?></td>
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

    <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma mato ma'lumotlari")?>:</h4>
    <table class="table-bordered table table-middle">
        <thead>
        <tr>
            <th scope="col">№</th>
            <th scope="col"><?= Yii::t('app','Mato')?></th>
            <th scope="col"><?= Yii::t('app', 'Rang') ?></th>
            <th scope="col"><?= Yii::t('app', "Rang(Bo'yoq)") ?></th>
            <th scope="col"><?= Yii::t('app', 'Model kodi') ?></th>
            <th scope="col"><?= Yii::t('app','Order Quantity')?></th>
            <th scope="col"><?= Yii::t('app','Quantity')?></th>
            <th scope="col"><?= Yii::t('app','Pus/Fine')?></th>
            <th scope="col"><?= Yii::t('app', 'Buyurtma toquv turi') ?></th>
            <th scope="col"><?= Yii::t('app', 'Type Weaving') ?></th>
            <th scope="col"><?= Yii::t('app','Order Thread Length')?></th>
            <th scope="col"><?= Yii::t('app','Thread Length')?></th>
            <th scope="col"><?= Yii::t('app','Order Finish En')?></th>
            <th scope="col"><?= Yii::t('app','Finish En')?></th>
            <th scope="col"><?= Yii::t('app','Order Finish Gramaj')?></th>
            <th scope="col"><?= Yii::t('app','Finish Gramaj')?></th>
            <th scope="col"><?= Yii::t('app','Done Date')?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1;
        foreach ($rmItems as $key=>$item):?>
            <tr>
                <td><?= $count;?></td>
                <td><?= $item['mato'] ?></td>
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
                    <?= $item['model_code']; ?>
                </td>
                <td class="tro">
                    <?= $item['order_quantity'];?>
                </td>
                <td>
                    <span class="tir col-md-8"><?= $item['quantity'] ?></span>
                    <?php if (Yii::$app->user->can('toquv-instructions/update')): ?>
                        <span class="col-md-3"><button class="btn btn-success btn-xs plus_qty"><i class="fa fa-plus"></i></button></span>
                        <div class="input-group hidden">
                            <input type="hidden" class="tir_id" name="id" value="<?=$item['id']?>">
                            <input type="hidden" class="tro_id" name="tro_id" value="<?=$item['troid']?>">
                            <input type="text" class="form-control number qty" name="quantity" value="0">
                            <button type="button" class="btn btn-success btn-xs send_rm"><?php echo Yii::t('app','Saqlash')?></button>
                            <button type="button" class="btn btn-danger btn-xs close_rm"><i class="fa fa-close"></i></button>
                        </div>
                    <?php endif; ?>
                </td>
                <td><?= $item['pf']; ?></td>
                <td><?= Constants::getTypeWeaving($item['order_type_weaving']) ?></td>
                <td><?= Constants::getTypeWeaving($item['type_weaving']); ?></td>
                <td><?= $item['order_thread_length'] ?></td>
                <td><?= $item['thread_length'] ?></td>
                <td><?= $item['order_finish_en'] ?></td>
                <td><?= $item['finish_en'] ?></td>
                <td><?= $item['order_finish_gramaj'] ?></td>
                <td><?= $item['finish_gramaj'] ?></td>
                <td><?= date('d.m.Y', strtotime($item['done_date'])); ?></td>
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
            <th><?= Yii::t('app','Mato Nomi va miqdori')?></th>
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
                <td>
                    <?php $musteri = (!empty($item['order_musteri']))?" ({$item['order_musteri']})":'';?>
                    <?= $item['mname'].$musteri ?>
                </td>
                <td><?= $item['mato']." - (".$item['qty']." kg)"; ?></td>
                <td><?= $item['ip'] ?></td>
                <td><?= $item['ipqty'] ?></td>
                <td><?= $item['fact'] ?></td>
                <td><?= $item['comment'] ?></td>
            </tr>
            <?php $count++; endforeach;?>
        </tbody>
    </table>

    <div class="kalite-rm">
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
$url = \yii\helpers\Url::to('plus-quantity');
$js = <<< JS
    $(document).on('click','.plus_qty',function(e) {
        e.preventDefault();
        $(this).parent().addClass('hidden');
        $(this).parent().next().removeClass('hidden');
    });
    $(document).on('click','.close_rm',function(e) {
        e.preventDefault();
        let parent = $(this).parents('.input-group');
        let qty = parent.find('.qty');
        qty.val(0);
        parent.addClass('hidden');
        parent.prev().removeClass('hidden');
    });
    $(document).on('click','.send_rm',function(e) {
        e.preventDefault();
        let parent = $(this).parents('.input-group');
        let tr = $(this).parents('tr');
        let tir_id = parent.find('.tir_id').val();
        let tro_id = parent.find('.tro_id').val();
        let qty = parent.find('.qty');
        if(qty.val() > 0){
            $.ajax({
                url: "{$url}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tir_id : tir_id,
                    tro_id : tro_id,
                    qty : qty.val(),
                },
                type: "POST",
                success: function (response) {
                    if(response.status == 1){
                        qty.val(0);
                        parent.addClass('hidden');
                        parent.prev().removeClass('hidden');
                        tr.find('.tro').html(response.tro.toFixed(3));
                        tr.find('.tir').html(response.tir.toFixed(3));
                    }else{
                        alert('Error');
                    }
                }
            });
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);