<?php

use yii\helpers\ArrayHelper;
use yii\bootstrap\Collapse;

$this->title = Yii::t('app', "Buyurtmalar bo'yicha aksessuar ehtiyojlari");

$dateByValues = ArrayHelper::map($dateBy, "load_data", "summ", 'id');

/*echo '<pre>';
    print_r($dateByValues);
echo '</pre>';
echo '<pre>';
    print_r($dateBy);
echo '</pre>';
echo '<pre>';
    print_r($dataProvider);
echo '</pre>';*/
?>
<?php echo Collapse::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Qidirish oynasi'),
            'content' => $this->render('view/search_acs', [
                'model' => $searchModel,
            ]),
            'contentOptions' => ['class' => 'out']
        ]
    ]
]);
?>
    <div class="table__column--persistent-wrap">
        <div class="table-wrap">
            <table class="table table-bordered fixed_header">
                <thead>
                <tr>
                    <th style="font-size: 11px; min-width: 220px!important; width:170px" >Aksessuarlar</th>
                    <?php foreach ($dataProvider as $value):?>
                    <th><?=$value['load_data']?></th>
                    <?php endforeach;?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dateBy as $key => $item):?>
                <tr style="height: auto">
                    <td class="notFixing" style="height: auto;min-width: 220px!important; width:170px" ><?=$item['name']?></td>
                    <?php foreach ($dataProvider as $value):?>
                        <td class="fixing" style="height: auto;"><?php echo number_format($dateByValues[$item['id']][$value['load_data']],'0','.',' ')??0?></td>
                    <?php endforeach;?>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
<?php
$sty="font-size: 0px;";
$css = <<< Css
.fixed_header{
    width: auto;
    table-layout: fixed;
    border-collapse: collapse;
    /*overflow-x: auto;*/
}

.fixed_header tbody{
  display:block;
  width: 100%;
  overflow: auto;
  height: 80vh;
}

.fixed_header thead tr {
   display: block;
}

.fixed_header thead {
  background: #595959;
  color:#fff;
}

.fixed_header th, .fixed_header td {
  padding: 5px;
  text-align: left;
  min-width: 83.33px;
}
.table__column--persistent-wrap {   
    /*box-sizing: border-box;*/
    overflow: auto;
}
/*.
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

!* ------- Presentational Formatting --------- *!
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
  position: -webkit-sticky;
  position: sticky;
  top: 0;
}*/
table.table__column--persistent{
    position: absolute;
    background: ivory;
}
table.table__column--persistent tbody{
    overflow-y: hidden;
}
table.table__column--persistent thead th{
    border: none;
    text-align:center;
    vertical-align: middle;
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
$this->registerJs($js, \yii\web\View::POS_READY);
$js = <<<JS
/*var lastScrollLeft = 0;
$('.table__column--persistent-wrap').scroll(function() {
    var documentScrollLeft = $('.table__column--persistent-wrap').scrollLeft();
    let table = $('.fixed_header')
    if (lastScrollLeft != documentScrollLeft) {
        console.log('scroll x');
        lastScrollLeft = documentScrollLeft;
    }
     console.log(lastScrollLeft);
});*/
    $('.table-wrap tbody').scroll(function(e) {
        $('table.table__column--persistent tbody').scrollTop($(this).scrollTop());
        console.log($(this).scrollTop())
    });
JS;
$this->registerJs($js);