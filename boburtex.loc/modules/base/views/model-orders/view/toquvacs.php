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
    <th><?=Yii::t('app', 'Type')?></th>
    <th><?=Yii::t('app', 'Toquv Aksessuar')?></th>
    <th><?=Yii::t('app', 'Color')?></th>
    <th><?=Yii::t('app', 'Wms Desen')?> </th>
    <th><?=Yii::t('app', 'Pus Fine')?> </th>
    <th><?=Yii::t('app', 'Count')?> </th>
    </thead>
    <?php
    foreach ($lastVar as $item) {
        $acs = ModelOrdersVariations::getVariationToquvAcs($model->id, $item['status'], $item['id'], $model->status);
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
                    <?= $item['rmt_name'] ?>
                </td>
                <td>
                    <?=$item['trmname']?>
                </td>
                <td>
                    <?php
                        if($item['color_pantone_id']){
                            echo $item['cpcode'].'('.$item['cpname'].')';
                        }
                        else{
                            echo $item['color_code'].'('.$item['color_name'].')';
                        }
                    ?>
                </td>
                <td>
                    <?= $item['wdcode'].' - '.$item['wdname'].'('.$item['wbtname'].')'?>
                </td>
                <td>
                    <?= $item['tpf_name'] ?>
                </td>
                <td>
                    <?= $item['count'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <?php
    }
    ?>
</table>

