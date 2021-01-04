<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

?>
    <div id="rmContentSize">
        <?php foreach ($model as $key){?>
            <div style="width: 49px;padding-right: 3px;float: left;">
                <div class="form-group field-model_orders_size_<?=$id?>">
                    <label class="control-label text-center" style="width: 100%"
                           for="model_orders_size_<?=$key['id']?>_<?=$id?>"><?=$key['name']?>
                    </label>
                    <input type="text" id="model_orders_size_<?=$key['id']?>_<?=$id?>" class="form-control number numberFormat"
                           name="ModelOrdersItems[<?=$id?>][size][<?=$key['id']?>]"
                           indeks="<?=$id?>" style="padding-left: 2px;" value="<?=($order)?$order->getSizeCount($key['id']):0?>">
                    <div class="help-block"></div>
                </div>
            </div>
        <?php }?>
    </div>