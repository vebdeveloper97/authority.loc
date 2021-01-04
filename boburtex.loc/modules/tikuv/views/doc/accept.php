<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.03.20 19:48
 */



/* @var $this \yii\web\View */
/* @var $konveyer \app\modules\tikuv\models\TikuvKonveyer */
/* @var $konveyer_list \app\modules\admin\models\ToquvUserDepartment[]|\app\modules\bichuv\models\Product[]|\app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls[]|\app\modules\tikuv\models\TikuvDoc[]|\app\modules\tikuv\models\TikuvKonveyer[]|\app\modules\toquv\models\ToquvDocumentItems[]|\app\modules\toquv\models\ToquvRmDefects[]|array|\yii\db\ActiveRecord[] */
/* @var $id  */
/* @var $model null|static */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="center-text">
    <?php
    $items = $model->getSliceItems();


    ?>
    <h4><?= Yii::t('app',"Kesim");?></h4>
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
                    <?= $item['nastel_party_no']  ?>
                </td>
                <td><?= $item['model'];?></td>
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
            <td colspan="4" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
            <td class="text-bold"><?= $totalKg; ?></td>
            <td></td>
            <td class="text-bold"><?= $totalRoll?></td>
        </tr>
        </tfoot>
    </table>
    <?php if(false):?>
    <?php  $acsItems  = $model->getAdditionItems(2);?>
    <h4><?= Yii::t('app',"Aksessuar");?></h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app','Aksessuar');?></th>
            <th><?= Yii::t('app','Nastel No');?></th>
            <th><?= Yii::t('app','Soni');?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalQty = 0;
        foreach ($acsItems as $key=> $item):?>
            <tr>
                <td><?= ($key+1);?></td>
                <td class="expand-party">
                    <?= $item['sku']."-".$item['name']."-".$item['property'];  ?>
                </td>
                <td><?= $item['nastel_no']; ?></td>
                <td><?= number_format($item['quantity'],0,'.',' ')?></td>
            </tr>
            <?php
            $totalQty += $item['quantity'];
        endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
            <td class="text-bold"><?= $totalQty; ?></td>
        </tr>
        </tfoot>
    </table>
    <?php endif;?>
    <?php if(false):?>
    <?php $matoItems  = $model->getAdditionItems(1); ?>
    <h4><?= Yii::t('app',"Beka Ma'lumotlari");?></h4>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app','Mato');?></th>
            <th><?= Yii::t('app','Nastel No');?></th>
            <th><?= Yii::t('app','Rulon soni');?></th>
            <th><?= Yii::t('app','Miqdori(kg)');?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalBRoll = 0;
        $totalBKg = 0;
        foreach ($matoItems as $key=> $item):?>
            <tr>
                <td><?= ($key+1);?></td>
                <td class="expand-party">
                    <?= $item['mato']." ".$item['ne']." ".$item['thread']." ".$item['pus_fine']." ".$item['ctone']." ".$item['color_id']." ".$item['pantone'];  ?>
                </td>
                <td><?= $item['nastel_no'];?></td>
                <td><?= number_format($item['roll_count'],0);?></td>
                <td><?= number_format($item['quantity'],2)?></td>
            </tr>
            <?php
            $totalBKg += $item['quantity'];
            $totalBRoll += $item['roll_count'];
        endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
            <td class="text-bold"><?= $totalBRoll; ?></td>
            <td class="text-bold"><?= $totalBKg?></td>
        </tr>
        </tfoot>
    </table>
    <?php endif;?>
</div>
<div class="toquv-kalite-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($konveyer, 'id')->widget(
        Select2::classname(), [
        'data' => $konveyer_list, 'language' => 'ru',
        'options' => [
            'prompt'=>Yii::t('app',Yii::t('app','Kimga')),
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(Yii::t('app', 'Konveyer tanlang')) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

