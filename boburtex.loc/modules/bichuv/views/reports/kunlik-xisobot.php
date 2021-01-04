
<?php
use yii\helpers\ArrayHelper;
use yii\bootstrap\Collapse;
//?>
<?= Collapse::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Qidirish oynasi'),
//              'content'=>'',
            'content' => $this->render('search/_search_xisobot', [
                'model' => $searchModel,
            ]),
            'contentOptions' => ['class' => 'out']
        ]
    ]
]);
?>

<div class="container">
    <div class="row">
        <table border="1px" width="50%"  style="margin-left: 10%; ">
            <tr  style="background-color:#9dc1d3;">
                <td align="center"><strong>Tr</strong></td>
                <td  class="diagonalFalling"><strong><p id='rotate'>Sana</p></strong></td>
                <td align="center"><strong>Olib kelingan mato kg</strong></td>
                <td align="center"><strong>Bichuvga Berilgan kg</strong></td>
            </tr>

            <?php $kun=0; $keldi=0; $ketdi=0;$um=0;
            foreach ($dataProvider as $item)
            {
                $kun=$kun+1;
                $keldi=$keldi + $item['keldi'];
                $ketdi=$ketdi + $item['ketdi'];
                ?>
                <tr align="center">
                    <td><?=$kun;?></td>
                    <td><?=$item['sana'];?></td>
                    <td><?=$item['keldi'];?></td>
                    <td><?=$item['ketdi'];?></td>
                </tr>
            <?php } ?>
            <tr bgcolor="yellow">
                <td align="center" colspan="2"><strong>Jami</strong></td>
                <td align="center"><strong><?=$keldi;?> kg</strong></td>
                <td align="center"><strong><?=$ketdi;?> kg</strong></td>
            </tr>
            <tr bgcolor="yellow">
                <td align="center" colspan="2"><strong>Ortacha</strong></td>
                <td align="center"><strong><?=round($keldi/$kun,3);?> kg</strong></td>
                <td align="center"><strong><?=round($ketdi/$kun,3);?> kg</strong></td>
            </tr>
        </table>
    </div>
</div>

