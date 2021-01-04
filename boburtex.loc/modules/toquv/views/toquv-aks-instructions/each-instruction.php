<?php
/* @var $this \yii\web\View */
/* @var $items array */
?>
<table class="table-bordered table">
        <thead>
            <tr>
                <th>№</th>
                <th>Hijjat raqami va sanasi</th>
                <th>Kontragent</th>
                <th>Mato</th>
                <th>Miqdori</th>
                <th>Tayyor bo'lish sanasi</th>
                <th>Holati</th>
            </tr>
            </thead>
            <tbody>
            <?php $rmIds = ""; $isSavedCount = count($items); $count = 0; $total = 0; foreach ($items as $key=>$item):?>
                <tr>
                    <td><?= ($key+1) ?></td>
                    <td><?= $item['document_number'] ?></td>
                    <td><?= $item['mname'] ?></td>
                    <td><?= "{$item['mato']}-{$item['pus_fine']}-{$item['thread_length']}-{$item['finish_en']}-{$item['finish_gramaj']}" ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['done_date'] ?></td>
                    <?php if($item['status'] == 3):?>
                        <?php
                        $count++;
                        $status = Yii::t('app','Saqlangan');?>
                        <td><button class="btn btn-primary"><?= $status; ?></button></td>
                    <?php else:?>
                        <?php $status = Yii::t('app','Saqlanmagan'); ?>
                        <td><button class="btn save-each-instruction btn-danger" data-order-id = "<?= $item['orderId']?>" data-rm-id="<?= $item['rmid']?>" data-id = "<?= $item['id']?>"><?= $status; ?></button></td>
                    <?php endif;?>
                </tr>
                <?php
                $total += $item['quantity'];
                $rmIds .= $item['rmid'];
                if($isSavedCount > ($key+1)){
                    $rmIds .= ", ";
                }
                endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Jami:</td>
                    <td><?= $total; ?></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <?php if($count == $isSavedCount): ?>
        <div class="close-btn-box text-right">
            <button class="btn btn-success" data-rm-ids = "<?= $rmIds?>" id="closeInstructions"><?= Yii::t('app',"Ko'rsatmani yopish");?></button>
        </div>
        <?php endif;?>


