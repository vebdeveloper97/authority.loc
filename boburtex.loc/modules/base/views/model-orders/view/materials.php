<?php
/** @var $this \yii\web\View */
/** @var $model \app\modules\base\models\ModelOrders */
/* @var $variant_id ModelOrdersVariations */
use app\modules\base\models\ModelOrdersVariations;
use yii\helpers\Html;

$lastVar = ModelOrdersVariations::find()
    ->select(['id', 'status'])
    ->where(['model_orders_id' => $model->id])
    ->asArray()
    ->orderBy(['id' => SORT_DESC])
    ->all();
?>

<table class="table table-bordered">
    <thead>
        <th>#</th>
        <th><?=Yii::t('app', 'Material')?></th>
        <th><?=Yii::t('app', 'En/gramaj')?></th>
        <th><?=Yii::t('app', 'Color')?></th>
        <th><?=Yii::t('app', 'Desen No')?> / <?= Yii::t('app', 'Baski type') ?></th>
        <th><?=Yii::t('app', 'Add Info')?></th>
    </thead>
    <?php
    foreach ($lastVar as $item) {
        $materials = ModelOrdersVariations::getVariantMaterials($model->id,$item['status'],$item['id'], $model->status);
        ?>
            <tbody style="<?php if($item['status'] == 2){echo 'background:#EF5350'; }?>">
        <?php
        $cnt = 1;
        ?>
        <?php foreach ($materials as $item): ?>
            <tr style="<?php if($item['status'] == 2){echo 'background:#EF5350'; }?>">
                <td>
                    <?=$cnt++?>
                </td>
                <td>
                    <?= $item['rcode']
                    . ' - ' . $item['rname']
                    . ' - ' . $item['tname']
                    . ' (' . $item['ne'] . ' - ' . $item['thread'] . ')'?>
                </td>
                <td>
                    <?=$item['en'] . 'sm | ' . $item['gramaj'] . ' gr/m<sup>2</sup>'?>
                </td>
                <td>
                    <?= $item['color_name'] . ' (' . $item['color_code'] . ') '?>
                </td>
                <td>
                    <?= $item['desen_name'] . ' (' . $item['desen_code'] . ')' . ' / ' . $item['baski_name'] ?>
                </td>
                <td>
                    <?= Html::encode($item['material_info']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?php
    }
    ?>
</table>

