<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 20.08.20 23:31
 */


use app\modules\tikuv\models\TikuvReportSearch;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel TikuvReportSearch */
/* @var $dataProvider array */
$this->params['bodyClass'] = 'sidebar-collapse';

$this->title = Yii::t('app', 'Qabul qilingan ishlar');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bichuv-report-index">
    <p class="pull-right no-print">
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black', 'class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php Pjax::begin(['id' => 'usluga-summa_pjax']); ?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search_accepted_report', [
                        'model' => $searchModel
                    ]),
                    'contentOptions' => ['class' => 'in']
                ]
            ]
        ]);
        ?>
    </div>
    <div class="row">
        <div class="col-md-4" id="forTable">
            <table class="table table-bordered" id="table-list">
                <thead>
                <tr style="background-color: #ddd;" align="center">
                    <td><b>№</b></td>
                    <td><b><?= Yii::t('app', 'Sanasi') ?></b></td>
                    <td><b><?= Yii::t('app', '2-qavat') ?></b></td>
                    <td><b><?= Yii::t('app', '3-qavat') ?></b></td>
                    <td><b><?= Yii::t('app', 'Usluga') ?></b></td>
                    <td><b><?= Yii::t('app', 'Jami') ?></b></td>
                </tr>
                </thead>
                <tbody>
                <?php $count = 0;
                $sum_floor2 = 0;
                $sum_floor3 = 0;
                $sum_uslug = 0;
                $jami = 0;
                $inform['qavat2'] = [];
                $inform['qavat3'] = [];
                $inform['usluga'] = [];
                $inform['date'] = [];
                $inform['jami'] = [];
                foreach ($dataProvider as $usluga):?>
                    <?php
                    $floor2 = 0;
                    $floor2 = $floor2 + $usluga['qavat2'];
                    $sum_floor2 = $sum_floor2 + $usluga['qavat2'];
                    $floor3 = 0;
                    $floor3 = $floor3 + $usluga['qavat3'];
                    $sum_floor3 = $sum_floor3 + $usluga['qavat3'];
                    $uslug = 0;
                    $uslug = $uslug + $usluga['usluga'];
                    $sum_uslug = $sum_uslug + $usluga['usluga'];
                    $summ = 0;
                    $summ = $floor2 + $floor3 + $uslug;
                    $jami = $jami + $summ;
                    if (!empty($usluga['qavat2'])) {
                        $item1 = $usluga['qavat2'];
                    } else {
                        $item1 = 0;
                    }
                    if (!empty($usluga['qavat3'])) {
                        $item2 = $usluga['qavat3'];
                    } else {
                        $item2 = 0;
                    }
                    if (!empty($usluga['usluga'])) {
                        $item3 = $usluga['usluga'];
                    } else {
                        $item3 = 0;
                    }
                    $inform['qavat2'][] = $item1;
                    $inform['qavat3'][] = $item2;
                    $inform['usluga'][] = $item3;
                    $inform['date'][] = $usluga['sana'];
                    $inform['jami'][] = $summ;
                    ?>
                    <tr align="center">
                        <td><?= $count + 1 ?></td>
                        <td><?= $usluga['sana'] ?></td>
                        <td><?= $item1; ?></td>
                        <td><?= $item2; ?></td>
                        <td><?= $item1; ?></td>
                        <td><?= number_format($summ, 3) ?></td>
                    </tr>
                    <?php $count++; endforeach; ?>
                </tbody>
                <tr align="center" style="background-color:#9dc1d3">
                    <td colspan="2"><b><?= Yii::t('app', 'Jami') ?></b></td>
                    <td><?= number_format($sum_floor2, 3); ?></td>
                    <td><?= number_format($sum_floor3, 3); ?></td>
                    <td><?= number_format($sum_uslug, 3); ?></td>
                    <td><?= number_format($jami, 3); ?></td>

                </tr>
                <tr align="center" style="background-color:#9dc1d3">
                    <td colspan="2"><b><?= Yii::t('app', 'O\'rtacha') ?></b></td>
                    <td><?= number_format($sum_floor2 / ($count != 0 ? $count : 1), 3); ?></td>
                    <td><?= number_format($sum_floor3 / ($count != 0 ? $count : 1), 3); ?></td>
                    <td><?= number_format($sum_uslug / ($count != 0 ? $count : 1), 3); ?></td>
                    <td><?= number_format($jami / ($count != 0 ? $count : 1), 3); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-8" id="diagram">
            <canvas id="line-chart" width="200" height="80"></canvas>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
<?php
$this->registerJsFile('/js/chart/chart.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsVar('inform', $inform);
$js = <<< JS
   new Chart(document.getElementById("line-chart"), {
       type: 'line',
      data: {
        labels:inform.date,
        datasets: [{ 
            data:inform.qavat2,
            label: "2 - Qavat",
            borderColor: "#3e95cd",
            fill: false
          }, { 
            data:inform.qavat3,
            label: "3 - Qavat",
            borderColor: "#8e5ea2",
            fill: false
          }, { 
            data:inform.usluga,
            label: "Usluga",
            borderColor: "#3cba9f",
            fill: false
          }, { 
            data:inform.jami,
            label: "Jami",
            borderColor: "#e8c3b9",
            fill: false
          }, 
        ]
      },
      options: {
        title: {
          display: true,
          
        }
      }
    });

 $('#onlyTable').change(function() {
      $('#forTable').attr('class','col-md-12').css('display','block');
      $('#diagram').css('display','none');
    }); 
    $('#onlyDiagram').change(function() {
      $('#diagram').attr('class','col-md-12').css('display','block');
      $('#forTable').css('display','none');
    });    
    $('#tableAndDiagram').change(function() {
      $('#diagram').attr('class','col-md-8').css('display','block');
      $('#forTable').attr('class','col-md-4').css('display','block');
    })
JS;
$this->registerJs($js);

$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$js = <<< JS
    $("table#table-list").tableExport({
        headers: true,
        footers: true,
        formats: ["xlsx", "csv", "xls"],
        filename: 'excel-table',
        bootstrap: true,
        exportButtons: true,
        position: "top",
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: "id",
        defaultFileName: "myDodwdwdwnload"
    });
    $("#reportResultMoving").on("pjax:end", function() {
        $("table#table-list").tableExport({
            headers: true,
            footers: true,
            formats: ["xlsx", "csv", "xls"],
            filename: "id",
            bootstrap: true,
            exportButtons: true,
            position: "top",
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: "id",
        });
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
#table-list td,#table-list th{
    text-align:center;
}
CSS;
$this->registerCss($css);
?>