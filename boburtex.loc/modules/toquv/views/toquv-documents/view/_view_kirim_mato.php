<?php

use app\modules\toquv\models\ToquvDocumentItemsSearch;
use app\modules\toquv\models\ToquvDocuments;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\modules\toquv\models\ToquvDocumentItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$t = Yii::$app->request->get('t',1);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {doc_type}',['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if(!Yii::$app->request->isAjax||Yii::$app->request->isPjax){?>
            <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info btn-sm']) ?>
        <?php }?>
        <?php if($model->status < $model::STATUS_SAVED):?>
            <?php if(!Yii::$app->request->isAjax){?>
                <?php if (Yii::$app->user->can('toquv-documents/kirim_mato/update')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], ['class' => 'btn btn-success']) ?>
                <?php endif;?>
                <?php if (Yii::$app->user->can('toquv-documents/kirim_mato/cancel')): ?>
                    <?= Html::a(Yii::t('app', 'Bekor qilish'), ["cancel", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], ['class' => 'btn btn-danger']) ?>
                <?php endif;?>
            <?php }?>
            <?php if (Yii::$app->user->can('toquv-documents/kirim_mato/save-and-finish')): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug,], ['class' => 'btn btn-success default_button']) ?>
            <?php endif;?>
        <?php endif;?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-content btn-sm']) ?>
    </div>
    <div id="print-content">
        <table class="table table-bordered table-responsive">
            <tr>
                <td><strong><?= Yii::t('app','Doc Number')?></strong>: <?= $model->doc_number; ?></td>
                <td><strong><?= Yii::t('app','Sana')?></strong>: <?= $model->reg_date ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name; ?></td>
                <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromEmployee->user_fio ?></td>
                <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('app','Imzo')?> _____________________</td>
                <td><?= Yii::t('app','Imzo')?> _____________________</td>
            </tr>
            <tr>
                <?php if(!empty($model->party)){?>
                    <td><strong><?= Yii::t('app','Partiya No')?></strong>: <?= $model->party ?></td>
                <?php }?>
                <td colspan="<?=(!empty($model->party))?1:2?>"><strong><?php echo Yii::t('app','Izoh')?></strong> : <?=$model->add_info?></td>
            </tr>
        </table>
        <?php $items = $model->getMatoInfo($model->id, $model->to_department) ?>
        <div class="center-text">
            <table class="table table-striped table-bordered" id="ipKirimViewTable">
                <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2"><?= Yii::t('app','Mato nomi')?></th>
                    <th rowspan="2"><?= Yii::t('app','Pus Fine');?></th>
                    <th rowspan="2"><?= Yii::t('app','Thread Length');?></th>
                    <th rowspan="2"><?= Yii::t('app','Finish Gramaj');?></th>
                    <th rowspan="2"><?= Yii::t('app','Finish En');?></th>
                    <th rowspan="2"><?= Yii::t('app','Buyurtmachi');?></th>
                    <th rowspan="2"><?= Yii::t('app',"Sort");?></th>
                    <th colspan="2"><?= Yii::t('app','Ombordagi Qoldiq')?></th>
                    <th colspan="2"><?=($model->status<3)?Yii::t('app',"Jo'natilgan miqdor"):Yii::t('app','Qabul qilingan miqdor')?></th>
                    <th rowspan="2"><?= Yii::t('app','Add Info')?></th>
                    <th rowspan="2"><?= Yii::t('app','Xizmat turi')?></th>
                    <th rowspan="2"><?= Yii::t('app','Rang')?></th>
                    <th rowspan="2"><?= Yii::t('app',"Rang(Bo'yoqxona)")?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo Yii::t('app','Kg')?></td>
                    <td><?php echo Yii::t('app','Rulon')?></td>
                    <td><?php echo Yii::t('app','Kg')?></td>
                    <td><?php echo Yii::t('app','Rulon')?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
                $totalQty = 0;
                $totalRemain = 0;
                $totalRollRemain = 0;
                $totalRoll = 0;
                foreach ($items as $key => $item):?>
                    <tr id="docItemRow<?= $key; ?>" class="">
                        <td><?= ($key+1) ?></td>
                        <td><?= $item['mato']; ?></td>
                        <td><?= $item['pus_fine'];?></td>
                        <td><?= $item['lth'];?></td>
                        <td><?= $item['gr'];?></td>
                        <td><?= $item['en'];?></td>
                        <td><?= $item['ka'];?></td>
                        <td><?= $item['sort']; ?></td>
                        <td><?= $item['remain']?></td>
                        <td><?= $item['remain_roll']?></td>
                        <td><?= $item['quantity']?></td>
                        <td><?= $item['roll_count']?></td>
                        <td><?= $item['add_info']?></td>
                        <td><?= (!empty($item['order_type']))?\app\modules\toquv\models\ToquvOrders::getOrderTypeList($item['order_type']):""?></td>
                        <td><?="<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> ".$item['c_pantone']?></td>
                        <td><?=" <span style='background:{$item['b_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> {$item['color_id']}"?></td>
                    </tr>
                    <?php
                    $totalRemain += $item['remain'];
                    $totalQty += $item['quantity'];
                    $totalRollRemain += $item['remain_roll'];
                    $totalRoll += $item['roll_count'];
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalRemain,3,'.',' '); ?></td>
                    <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalRollRemain,3,'.',' '); ?></td>
                    <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalQty,3, '.',' '); ?></td>
                    <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalRoll,3, '.',' '); ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <?php $items = $model->getMatoInfo($model->id, $model->to_department, \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_ACS);
        if(!empty($items)){?>
            <div class="center-text">
                <table class="table table-striped table-bordered" id="ipKirimViewTable">
                    <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2"><?= Yii::t('app','Aksessuar nomi')?></th>
                        <th rowspan="2"><?= Yii::t('app','Pus Fine');?></th>
                        <th rowspan="2"><?= Yii::t('app','Buyurtmachi');?></th>
                        <th colspan="3"><?= Yii::t('app','Ombordagi Qoldiq')?></th>
                        <th colspan="3"><?=($model->status<3)?Yii::t('app',"Jo'natilgan miqdor"):Yii::t('app','Qabul qilingan miqdor')?></th>
                        <th rowspan="2"><?= Yii::t('app','Add Info')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo Yii::t('app','Kg')?></td>
                        <td><?php echo Yii::t('app','Dona')?></td>
                        <td><?php echo Yii::t('app','Rulon')?></td>
                        <td><?php echo Yii::t('app','Kg')?></td>
                        <td><?php echo Yii::t('app','Dona')?></td>
                        <td><?php echo Yii::t('app','Rulon')?></td>
                        <td></td>
                    </tr>
                    <?php
                    $totalQty = 0;
                    $totalCountRemain = 0;
                    $totalRollRemain = 0;
                    $totalRemain = 0;
                    $totalCount = 0;
                    $totalRoll = 0;
                    foreach ($items as $key => $item):?>
                        <tr id="docItemRow<?= $key; ?>" class="">
                            <td><?= ($key+1) ?></td>
                            <td><?= $item['mato']; ?></td>
                            <td><?= $item['pus_fine'];?></td>
                            <td><?= $item['ka'];?></td>
                            <td><?= $item['remain']?></td>
                            <td><?= $item['remain_count']?></td>
                            <td><?= $item['remain_roll']?></td>
                            <td><?= $item['quantity']?></td>
                            <td><?= $item['count']?></td>
                            <td><?= $item['roll_count']?></td>
                            <td><?= $item['add_info']?></td>
                        </tr>
                        <?php
                        $totalRemain += $item['remain'];
                        $totalQty += $item['quantity'];
                        $totalCountRemain += $item['remain_count'];
                        $totalRollRemain += $item['remain_roll'];
                        $totalCount += $item['count'];
                        $totalRoll += $item['roll_count'];
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalRemain,3,'.',' '); ?></td>
                        <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalCountRemain,3,'.',' '); ?></td>
                        <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalRollRemain,3,'.',' '); ?></td>
                        <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalQty,3, '.',' '); ?></td>
                        <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalCount,3,'.',' '); ?></td>
                        <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalRoll,3,'.',' '); ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        <?php }?>
        <?php $rolls = $model->parent->rollMoveInfos;
        if(!empty($rolls)){?>
            <div class="center-text">
                <table class="table table-striped table-bordered" id="ipKirimViewTable">
                    <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2"><?= Yii::t('app','Rulon kodi')?></th>
                        <th rowspan="2"><?= Yii::t('app',"To'quvchi tabel raqami");?></th>
                        <th rowspan="2"><?= Yii::t('app',"Tekshiruvchi");?></th>
                        <th rowspan="2"><?= Yii::t('app','Miqdori');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    foreach ($rolls as $key => $item):?>
                        <?php $kalite = $item->rollInfo->toquvKalite;?>
                        <tr id="docItemRow<?= $key; ?>" class="">
                            <td><?= ($key+1) ?></td>
                            <td><?= $kalite['code']; ?></td>
                            <td>T-<?= $kalite->user->usersInfo['tabel'];?></td>
                            <td><?= $kalite->userKalite['user_fio'];?></td>
                            <td><?= $kalite['quantity'];?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        <?php }?>
    </div>
</div>
<?php
$js = <<< JS
    $('body').delegate('.print-content','click',function () {
        printDivById('print-content');
    });
    function printDivById(content_id) {
        let new_content = document.getElementById(content_id).innerHTML;
        $('.wrapper').hide();
        $('body').append("<div id='new_content_print'>"+new_content+"</div>");
        window.print();
        $('#new_content_print').remove();
        $('.wrapper').show();
        return false;
    }
JS;
if(!Yii::$app->request->isAjax) {
    $this->registerJs($js, \yii\web\View::POS_READY);
}
