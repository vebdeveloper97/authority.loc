<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">

    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if($model->status != $model::STATUS_SAVED):?>
            <?= Html::a(Yii::t('app', 'Qabul qilish'), ["accept-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                ['class' => 'btn btn-success']) ?>
        <?php endif;?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
    </div>

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


    </table>
    <div class="center-text">
        <?php
        $items = $model->getSliceMovingView($model->id);
        ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app',"Model");?></th>
                <th><?= Yii::t('app',"O'lcham");?></th>
                <th><?= Yii::t('app','Soni');?></th>
                <th><?= Yii::t('app','Qabul(Fact)');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalFact = 0;
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
                    <td><?= number_format($item['fact_quantity'],0);?></td>

                </tr>
                <?php
                $totalKg += $item['quantity'];
                $totalFact += $item['fact_quantity'];
            endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td class="text-bold"><?=$totalFact?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
