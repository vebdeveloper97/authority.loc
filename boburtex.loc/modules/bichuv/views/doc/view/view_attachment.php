<?php

/**
 * @var $resultPrint
 * @var $resultStone
 */
?>

<div style="padding: 10px">
    <?php if($resultPrint):
        foreach ($resultPrint as $item):
            ?>
            <table class="table table-bordered table-striped">
                <tr>
                    <td><?=Yii::t('app', 'Name')?></td>
                    <td> <h3 class="text-black-50" align="center"><?=$item['name']?></h3></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Code')?></td>
                    <td> <h3 class="text-black-50" align="center"><?=$item['code']?></h3></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Color')?></td>
                    <td>
                        <span style="width: 100%; height: 35px; background: rgb(<?=$item['r']?>,<?=$item['g']?>,<?=$item['b']?>); display: block"></span>
                        <span><?=$item['name_ru']?></span>
                    </td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Image')?></td>
                    <td><img src="<?=$item['path']?>" alt="" class="img-thumbnail"></td>
                </tr>
            </table>
        <?php
        endforeach;
    endif;?>
    <?php if($resultStone):
        foreach ($resultStone as $item):
            ?>
            <table class="table table-bordered table-striped" style="border-radius: 4px!important;">
                <tr>
                    <td><?=Yii::t('app', 'Name')?></td>
                    <td> <h3 class="text-black-50" align="center"><?=$item['name']?></h3></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Code')?></td>
                    <td> <h3 class="text-black-50" align="center"><?=$item['code']?></h3></td>
                </tr>
                <tr>
                    <td><?=Yii::t('app', 'Image')?></td>
                    <td><img src="<?=$item['path']?>" alt="" class="img-thumbnail"></td>
                </tr>
            </table>
        <?php
        endforeach;
    endif;?>
</div>

