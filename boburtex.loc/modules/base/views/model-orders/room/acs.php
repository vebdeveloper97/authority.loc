<?php
/** @var $this \yii\web\View */
/** @var $model \app\modules\base\models\ModelOrders */
use app\modules\base\models\ModelOrdersVariations;
/** Malumotlarni ko'rish accessory uchun */
if($model->status === \app\modules\base\models\ModelOrders::STATUS_ACTIVE)
    $varStatus = 1;
else
    $varStatus = 3;

$acs = ModelOrdersVariations::getVariationAcs($model->id,$varStatus, null,$model->status);
?>

<table class="table table-bordered">

    <thead>
        <th>#</th>
        <th><?=Yii::t('app', 'Artikul / Kodi')?></th>
        <th><?=Yii::t('app', 'Bichuv Acs')?></th>
        <th><?=Yii::t('app', 'Properties')?></th>
        <th><?=Yii::t('app', 'Quantity')?> </th>
        <th><?=Yii::t('app', 'Add Info')?> </th>
    </thead>
    <tbody>
    <?php
    $cnt = 1;
    ?>
    <?php foreach ($acs as $item): ?>
        <tr>
            <td>
                <?=$cnt++?>
            </td>
            <td>
                <?= $item['artikul'] ?>
            </td>
            <td>
                <?=$item['acs_name']?>
            </td>
            <td>
                <?= $item['acs_properties']?>
            </td>
            <td>
                <?= $item['order_acs_qty'] . ' (' . $item['unit_name'] . ')' ?>
            </td>
            <td>
                <?= $item['order_acs_info'] ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

