<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use app\modules\bichuv\models\TayyorlovNastelAcs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvDocItems */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $konveyer \app\modules\tikuv\models\TikuvKonveyer */

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
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if($model->status != $model::STATUS_SAVED):?>
            <?php if(!empty($konveyer)){?>
                <?= Html::a("<b>{$konveyer['name']}</b>ga qabul qilish", ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 'konveyer' => $konveyer['id'], 'bgr'=>$konveyer['bgr_id']],['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', "Konveyerni o'zgartirish"), ["accept", 'id' => $model->id, 'slug' => $this->context->slug],['class' => 'btn btn-info']) ?>
            <?php }else{?>
                <?= Html::a(Yii::t('app', 'Qabul qilish'), ["accept", 'id' => $model->id, 'slug' => $this->context->slug],['class' => 'btn btn-success']) ?>
            <?php }?>
        <?php endif;?>
        <?= Html::button('<span class="fa fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
        <?php if (Yii::$app->user->can('doc/qabul_kesim/view')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?php if (Yii::$app->user->can('doc/qabul_kesim/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif;?>
            <?php endif;?>
        <?php endif;?>

    </div>
 <?php $modelData = $model->getModelListInfo();?>
    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name ?></td>
            <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Musteri ID')?></strong>: <?= $model->musteri->name; ?></td>
            <td><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Tikuv Konveyer')?></strong>: <?= $konveyer['name']; ?></td>
            <td></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Buyurtma Raqami')?></strong>: </td>
            <td><?= $modelData['doc']; ?></td>
        </tr>
    </table>
    <?php

    ?>
    <table class="table-bordered table">
        <tbody>
        <tr>
            <td class="text-bold"><?= Yii::t('app','Article');?></td>
            <td><?= $modelData['model']?></td>
        </tr>
        <tr>
            <td class="text-bold"><?= Yii::t('app','Model Ranglari');?></td>
            <td><?= $modelData['model_var']?></td>
        </tr>
        </tbody>
    </table>
    <div class="center-text">
        <?php $items = $model->getSliceItems();?>
        <h4><?= Yii::t('app',"Kesim");?></h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app','Nastel Party');?></th>
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
            $nastelNo = '';
            foreach ($items as $key=> $item):?>
                <?php
                $nastelNo = $item['nastel_party_no'];
                ?>
                <tr>
                    <td><?= ($key+1);?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party_no']  ?>
                    </td>
                    <td><?= $item['size'];?></td>
                    <td><?= number_format($item['quantity'],0,'.',' ');?></td>
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
                <td colspan="3" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td></td>
                <td class="text-bold"><?= $totalRoll?></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <?php
    $acsDocId = TayyorlovNastelAcs::find()->select(['acs_doc_id'])->andWhere(['nastel_no' => $nastelNo])->scalar();
    $acsDoc = BichuvDoc::findOne(['id' => $acsDocId]);
    $items = ($acsDoc instanceof BichuvDoc) ? $acsDoc->getAccessoriesView() : null;
    ?>
    <?php if ($items): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Aksessuar'); ?></th>
                <th><?= Yii::t('app', 'Nastel Party'); ?></th>
                <th><?= Yii::t('app', 'Soni'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalKg = 0;
            foreach ($items as $key => $item):?>
                <tr>
                    <td><?= ($key + 1); ?></td>
                    <td>
                        <?= $item['sku'] . '-' . $item['acs'] . '-' . $item['property']; ?>
                    </td>
                    <td class="expand-party">
                        <?= $item['nastel_no']; ?>
                    </td>
                    <td><?= number_format($item['quantity'], 0,'.',' '); ?></td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
            endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
            </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>
