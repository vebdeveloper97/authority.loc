<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 10.01.20 20:17
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');

$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel(),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {doc_type}',['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_aksessuar/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?php if (Yii::$app->user->can('toquv-documents/hisobdan_chiqarish_aksessuar/delete')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif;?>
        <?php endif;?>
        <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
    </div>
    <div style="clear: both;padding-bottom: 25px;letter-spacing: 2px;">
        <h3 class="text-center">
            <strong>
                <?= Yii::t('app','HISOBDAN CHIQARISH DALOLATNOMASI')?>
            </strong>
        </h3>
    </div>
    <?php $items = $model->getMatoInfo($model->id, $model->from_department, \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_ACS);
    ?>
    <div class="center-text">
        <table class="table table-striped table-bordered" id="ipKirimViewTable">
            <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2"><?= Yii::t('app','Aksessuar nomi')?></th>
                <th rowspan="2"><?= Yii::t('app','Pus Fine');?></th>
                <th rowspan="2"><?=Yii::t('app', "Uzunligi | Eni | Qavati")?></th>
                <th rowspan="2"><?= Yii::t('app','Buyurtmachi');?></th>
                <th colspan="3"><?= Yii::t('app','Ombordagi Qoldiq')?></th>
                <th colspan="3"><?= Yii::t('app',"Jo'natilgan miqdor")?></th>
                <th rowspan="2"><?= Yii::t('app','Add Info')?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
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
                    <td><?= $item['mato_color']; ?> <b><?= $item['mato']; ?></b></td>
                    <td><?= $item['pus_fine'];?></td>
                    <td><?= $item['info'];?></td>
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
    <div>
        <p>
            <?php echo Yii::t('app','Izoh')?> : <b><?=$model->add_info?></b>
        </p>
        <p>
            Quyidagi keltirilgan moddiy tovar boyliklarni <strong><?= $model->fromDepartment->name ?></strong>
            javobgar shaxsi <strong><?= $model->fromEmployee->user_fio; ?></strong> hisobidan chiqarishga ruxsat berishingizni so'rayman.
        </p>
        <p><strong><?= $model->fromDepartment->name; ?> mas'ul shaxsi: <?= $model->fromEmployee->user_fio; ?>   ________________________</strong></p>
    </div>
</div>
