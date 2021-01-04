<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');

$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {doc_type}',['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
    </div>
    <?php $items = $model->getAcceptedItems($model->id, 2, $model->to_department); ?>
    <?php if(count($items) > 0):?>
        <table class="table table-bordered table-responsive">
            <tr>
                <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name; ?></td>
                <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->fromEmployee->user_fio ?></td>
                <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->toEmployee->user_fio ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('app','Imzo')?> _____________________</td>
                <td><?= Yii::t('app','Imzo')?> _____________________</td>
            </tr>
        </table>
        <div class="center-text">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= Yii::t('app','Ip nomi')?></th>
                    <th><?= Yii::t('app','Yuborilgan miqdor')?></th>
                    <th><?= Yii::t('app','Qabul qilingan miqdor')?></th>
                    <th><?= Yii::t('app','Farq')?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                $totalAccepted = 0;
                foreach ($items as $key => $item):?>
                    <tr>
                        <td><?= ($key+1) ?></td>
                        <td style="width:400px;"><?= "{$item['ipname']}-{$item['nename']}-{$item['thrname']}-{$item['clname']}({$item['lot']})"?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format(($item['quantity'] - $item['qoldiq']),3,'.',' ') ?></td>
                        <td><?= $item['quantity'] - ($item['quantity'] - $item['qoldiq'])?></td>
                    </tr>
                    <?php
                    $total += $item['quantity'];
                    $totalAccepted += ($item['quantity'] - $item['qoldiq']);
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td style="font-weight:bold;font-size:1.1em">
                        <?= number_format($total,3,'.', ' '); ?>
                    </td>
                    <td style="font-weight:bold;font-size:1.1em">
                        <?= number_format($totalAccepted,3,'.', ' '); ?>
                    </td>
                    <td>&nbsp</td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div style="padding: 15px;font-size: 1.2em;">
            <p class="text-center"><?= Yii::t('app',"Ma'lumot mavjud emas!")?></p>
        </div>
    <?php endif;?>
</div>

