<?php
/** @var $this \yii\web\View */
/** @var $model \app\modules\base\models\ModelOrders */
use app\modules\base\models\ModelOrdersVariations;
/** Malumotlarni ko'rish accessory uchun */

$lastVar = ModelOrdersVariations::find()
    ->select(['id', 'status'])
    ->where(['model_orders_id' => $model->id])
    ->asArray()
    ->orderBy(['id' => SORT_DESC])
    ->all();

$acs = ModelOrdersVariations::getVariationAcs($model->id,$lastVar['status'], $lastVar['id'], $model->status);
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
    <?php
        foreach ($lastVar as $item) {
            $acs = ModelOrdersVariations::getVariationAcs($model->id, $item['status'], $item['id'], $model->status);
        ?>
            <tbody style="<?php if($item['status'] == 2){echo 'background:#EF5350'; }?>">
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
        <?php
        }
    ?>
</table>

