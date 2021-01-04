<?php

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\WhItemBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $data array */

$this->title = Yii::t('app', 'Wh Item Balances');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-report-index">
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search', ['model' => $searchModel, 'data' => $data]),
                    'contentOptions' => ['class' => 'in'],
                ]
            ]
        ]);
        ?>
    </div>
    <div class="no-print pull-right">
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
    </div>
    <div class="report-ip-title">
        <h3 class="text-center" style="padding-bottom: 25px;">
            <?= Yii::t('app', "{date} kungi qoldiq", ['date' => $data['date'] ? $data['date'] : date('d.m.Y')]) ?>
        </h3>
    </div>

    <table class="table table-condensed table-bordered" style="font-size: 11px">
        <tr class="text-primary">
            <th style="width: 1%">№</th>
            <th><?= Yii::t('app', 'Maxsulot')?></th>
            <th><?= Yii::t('app', 'Price')?></th>
            <th><?= Yii::t('app', 'Package Qty')?></th>
            <th><?= Yii::t('app', 'Quantity')?></th>
            <th><?= Yii::t('app', 'Summa')?></th>
        </tr>

        <?php
        if (!empty($dataProvider)) :
            $sum_price = [];
            $sum_package = 0;
            $sum_qty = 0;
            $i = 0;
            foreach ($dataProvider as $data) :
                $name = $data['name'];
                $sum_price[$data['currency']] += ($data['inventory'] * $data['wh_price']);
                $sum_package += $data['package_inventory'];
                $sum_qty += $data['inventory'];
        ?>
        <tr>
            <td><?= ++$i ?></td>
            <td><?= $name ?></td>
            <td>
                <?= $data['wh_price'] .
                " <small><i>" . $data['currency'] . "</i></small>" ?>
            </td>
            <td>
                <?= ($data['package_inventory'] ? $data['package_inventory'] : 0) .
                " <small><i>" .
                \app\models\Constants::getPackageTypes($data['package_type']) .
                "</i></small>" ?>
            </td>
            <td>
                <?= $data['inventory'] .
                " <small><i>" . $data['unit'] . "</i></small>" ?>
            </td>
            <td>
                <?= ($data['inventory'] * $data['wh_price']) .
                " <small><i>" . $data['currency'] . "</i></small>" ?>
            </td>
        </tr>
        <?php
            endforeach;
        ?>
            <tfoot class="text-primary">
                <tr>
                    <th colspan="3" class="text-right"> <?= Yii::t('app', 'Jami')?></th>
                    <th><?= $sum_package ?></th>
                    <th><?= $sum_qty ?></th>
                    <th><?php
                            if (!empty($sum_price)) :
                                foreach ($sum_price as $key => $value) :
                                    echo $value . " <small><i>" . $key ."</i></small><br>";
                                endforeach;
                            endif;
                        ?></th>
                </tr>
            </tfoot>
        <?php
            else:
        ?>
            <tr>
                <td colspan="30" class="text-center text-danger">
                    <b><?= Yii::t('app', "Ma'lumot mavjud emas!") ?></b>
                </td>
            </tr>
        <?php
            endif;
        ?>

    </table>


</div>
<?php
$css = <<< Css
.select2-container--krajee strong.select2-results__group{
display:none;
}
.select2-container--krajee ul.select2-results__options>li.select2-results__option[aria-selected] {
font-size: 11px;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
right: 5px;
opacity: 0.5;
z-index: 999;
font-size: 18px;
top: -7px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
top: 60%;
}
Css;
$this->registerCss($css);