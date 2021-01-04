<?php
?>
<?php
if (!empty($standartItems)):?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th><?=Yii::t('app','Base Error List')?></th>
                        <th><?=Yii::t('app','Quantity')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($standartItems as $standartItem):?>
                        <tr>
                            <td><?=$standartItem->errorList->name." - ".$standartItem->errorList->errorCategory->name?></td>
                            <td><?=$standartItem->quantity?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif;?>