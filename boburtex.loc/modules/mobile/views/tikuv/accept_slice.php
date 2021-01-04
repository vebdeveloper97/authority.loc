<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use app\modules\bichuv\models\TayyorlovNastelAcs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvDoc */
/* @var $searchModel app\modules\tikuv\models\TikuvDocItems */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $konveyer \app\modules\tikuv\models\TikuvKonveyer */
/* @var $models \app\modules\tikuv\models\TikuvDocItems */

$slug = Yii::$app->request->get('slug');
$slug = 'accept_slice';
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
    <div class=" no-print" style="margin-bottom: 15px;">
        <?php if($model->status != $model::STATUS_SAVED):?>
            <?php if(!empty($konveyer)): ?>
                <?= Html::a("<b>{$konveyer['name']}</b>ga qabul qilish", ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 'konveyer' => $konveyer['id'], 'bgr'=>$konveyer['bgr_id']],['class' => 'btn btn-success']) ?>
                <?php if (false): ?>
                    <?= Html::a(Yii::t('app', "Konveyerni o'zgartirish"), ["accept", 'id' => $model->id, 'slug' => $this->context->slug],['class' => 'btn btn-info']) ?>
                <?php endif; ?>
            <?php else: ?>
                <?= Html::button(Yii::t('app', 'Accept'), [
                    'class' => 'btn btn-success btn-block btn-lg accept-button',
                    'data' => [
                        'slug' => $this->context->slug,
                        'id' => $model->id,
                    ]
                ]) ?>
            <?php endif;?>
        <?php endif;?>

    </div>
    <?php $modelData = $model->getModelListInfo();?>
    <h4><strong><?= '№' . Html::encode($model->doc_number) . "($model->reg_date)"; ?></strong></h4>
    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','From department')?></strong>: <?= $model->fromHrDepartment->name ?></td>
            <td><strong><?= Yii::t('app','To department')?></strong>: <?= $model->toHrDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Responsible person')?></strong>: <?= $model->fromHrEmployee->fish ?></td>
            <td><strong><?= Yii::t('app','Responsible person')?></strong>: <?= $model->toHrEmployee->fish ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Musteri ID')?></strong>: <?= $model->musteri->name; ?></td>
            <td><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Process')?></strong>: </td>
            <td><?= $model->mobileProcess->name; ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Tikuv Konveyer')?></strong>: </td>
            <td><?= $model->mobileTable->name; ?></td>
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
        <?php $form = ActiveForm::begin([
            'id' => 'form_accept_slice_form'
        ]); ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app',"O'lcham");?></th>
                <th><?= Yii::t('app','Soni');?></th>
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
            $totalFactQty = 0;
            $nastelNo = '';

            $cnt = 0;
            foreach ($models as $key=> $item):?>
                <?php
                $nastelNo = $item['nastel_party_no'];
                ?>
                <tr>
                    <td><?= ($key+1);?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party_no']  ?>
                    </td>
                    <td><?= $item->size->name;?></td>
                    <td><?= number_format($item['quantity'],0,'.',' ');?></td>
                    <td>
                        <?= $form->field($item, "[{$cnt}]add_info")->textarea(['style' => 'display:none;'])->label(false) ?>
                        <?= $form->field($item, "[{$cnt}]quantity")->hiddenInput(['disabled' => true])->label(false) ?>
                        <?= $form->field($item, "[{$cnt}]fact_quantity", [
                            'template' => '<div class="input-group input-group-lg">{input}<span class="input-group-btn"><button class="btn btn-info add_info_btn" data-container="body" data-toggle="popover" data-placement="left" data-content="<code>Mavjud emas</code>" tabindex="-1"><i class="fa fa-info" aria-hidden="true"></i></button></span></div>',
                        ])->label(false)->textInput(['data-quantity' => $item['quantity'], 'readonly' => $model->isSavedDocument()])?>
                    </td>
                    <?php if (false): ?>
                        <td><?= number_format($item['work_weight'],0)?></td>
                        <td><?= $item['quantity']*$item['work_weight']/1000;?></td>
                    <?php endif; ?>
                </tr>
                <?php
                $cnt++;
                $totalKg += $item['quantity'];
                $totalFactQty += $item['fact_quantity'];
                $totalRoll += $item['quantity']*$item['work_weight']/1000;
            endforeach;?>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td class="text-bold"><span id="totalFactQuantity"><?= $totalFactQty; ?></span></td>
                <?php if (false): ?>
                    <td></td>
                    <td class="text-bold"><?= $totalRoll?></td>
                <?php endif; ?>
            </tr>
            </tfoot>
        </table>
        <?php ActiveForm::end(); ?>
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
