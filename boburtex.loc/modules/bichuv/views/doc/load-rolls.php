<?php



/* @var $this \yii\web\View */
/* @var $t integer */
/* @var $items array */

?>
<?php if(!empty($items)):?>
<div>
    <?php $item = $items[0]; ?>
    <p><strong>Partiya №</strong>: <?= $item['partiya_no']?> |  <strong>Mijoz Partiya №:</strong> <?= $item['mijoz_part']; ?></p>
    <p><strong>Mato:</strong> <?= "{$item['mato']}-{$item['ne']}-{$item['ip']}|{$item['pus_fine']}";?> | <strong>Rangi:</strong> <?= "{$item['ctone']} {$item['color_id']} {$item['pantone']}"; ?></p>
</div>
<?php endif;?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">№</th>
            <?php if($t == 2):?>
                <th class="text-center"><?= Yii::t('app','Rulon soni');?></th>
            <?php else:?>
                <th class="text-center"><?= Yii::t('app','Rulon №');?></th>
            <?php endif;?>
            <th class="text-center"><?= Yii::t('app','Miqdori(kg)');?></th>
        </tr>
    </thead>
    <tbody>
        <?php $count = count($items);
        $total = 0;
        foreach ($items as $key => $item):?>
            <tr class="text-center">
                <td><?= ($key + 1) ?></td>
                <?php if($t == 2):?>
                    <td><?= $item['roll_count'];?></td>
                <?php else:?>
                    <td><?= ($key+1)."-".$count; ?></td>
                <?php endif;?>
                <td><?= number_format($item['rulon_kg'],3,'.',' ');?></td>
            </tr>
        <?php
        if($t == 2){
            $count = $item['roll_count'];
        }
        $total += $item['rulon_kg'];
        endforeach;?>
        <tfoot>
            <tr>
                <th class="text-center text-bold"><?= Yii::t('app','Jami');?></th>
                <th class="text-center"><?= $count; ?></th>
                <th class="text-center text-bold"><?= number_format($total,3,'.', ' ')?></th>
            </tr>
        </tfoot>
    </tbody>
</table>
