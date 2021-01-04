<?php
 use yii\helpers\ArrayHelper;
use yii\bootstrap\Collapse;
$this->title = Yii::t('app', "Buyurtmalar bo'yicha ip ehtiyojlari");
 //?>
<?= Collapse::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Qidirish oynasi'),
//              'content'=>'',
            'content' => $this->render('view/search', [
                'model' => $searchModel,
            ]),
            'contentOptions' => ['class' => 'out']
        ]
    ]
]);
?>
<div class="table__column--persistent-wrap">
    <div class="table-wrap">

    <table class="table table-bordered" >
    <thead>
    <tr>

                    <th style="font-size: 11px;" width="170px" scope="row">Iplar</th>

         <?php foreach (ArrayHelper::index($dataProvider,null,'nomi') as $key):?>
            <?php
            $bol = false;
            $i=0;
            foreach ($result as $k=>$v):
                $bol = true;?>
                <th scope="col" style="font-size: 11px; " width="170px" ><?= $v['load_date']?></th>
                <?php $i++;  endforeach;?>
            <?php if ($bol)break;?>
        <?php endforeach;?>
    </tr>
    </thead>
    <tbody>
        <?php $i=1;
        foreach(ArrayHelper::index($dataProvider,null,'nomi') as $key => $val):?>
       <?php if(!empty($key)) {?>
        <tr>
          <th scope="row" style="font-size: 12px;"><?= $key;?> </th>
             <?php $n=0;
             if(!empty($result)):
                 foreach($result as $k => $v):?>
                    <?php if($v['load_date']==$val[$n]['load_date'] ) {?>
                         <td style="font-size: 11px; text-align: center"><?=$val[$n]['jami']." " ;?></td>
                    <?php $n++; }else { ?>
                         <td style=font-size: 11px;"> </td>
                    <?php }?>
                 <?php endforeach;
             endif;?>
        </tr>
        <?php $i++;}
        endforeach;?>
    </tbody>
    </table>

    </div>
</div>
<?php
$sty="font-size: 0px;";
$css = <<< Css
.table-wrap {
  overflow-x: auto;
  width: 100%;
}

.table-wrap .table {
  width: 100%;
  max-width: 100%;
}

.table tr th {
  white-space: nowrap;
}

.table-wrap {
  position: static;
}

.table__column--persistent-wrap {
  position: relative;
  border: 1px solid #f0f0f0;
}

.table__column--persistent {
  background-color:white;
  position: absolute;
  top: 0;
  left: 0;
  display: inline-block;
  width: auto;

  z-index: 4;
}

/* ------- Presentational Formatting --------- */
* {
  font-family: Arial, Helvetica, sans-serif;
}
h1 {
  text-align: center;
}
.center {
  margin: 0 auto;
  width: 100%;
}

.table {
  border-collapse: collapse;
}

.table tr {
  border-bottom: 1px solid #f0f0f0;
}

.table thead tr {
  border-bottom: 2px solid #f0f0f0;
}

.table tr td,
.table tr th {
  padding: .5em;
  border-right: 1px solid #f0f0f0;
}

.table th {
  text-align: center;
}
Css;
$this->registerCss($css);

$js = <<< JS

function cloneTables(tables) {
  tables.each(function () {
    var table = $(this);
    var persistentColumn = table
      .clone()
      .insertBefore($(table).parent())
      .addClass('table__column--persistent');
    persistentColumn.find('th:not(:first-child),td:not(:first-child)')
      .remove();
    equalizeRowHeights(table, persistentColumn, false);
  });
}

function equalizeRowHeights(fullTable, singleColumn, stopRecursion) {
  singleColumn.find('tr')
      .each(function (i, elem) {
            $(this).height(fullTable.find('tr:eq(' + i + ')').height());
        });
  if (!stopRecursion) {
    $(window).resize(function() {
      equalizeRowHeights(fullTable, singleColumn, true);
    });
  }
}

$(function() {
    cloneTables($('.table-wrap .table'));
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);