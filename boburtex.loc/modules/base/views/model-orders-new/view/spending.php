<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataProvider app\modules\base\models\ModelOrdersItems */

?>
    <table width="90%">
        <tr>
            <td width="3%"></td>
            <td width="15%"><strong><font style="font-size: 14px; font"><?= Yii::t('app', 'Doc Number')?> </font></strong></td>
            <td width="45%"><?= $model->doc_number ?> </td>
            <td width="10%"><strong><font style="font-size: 14px; font"><?= Yii::t('app', 'Musteri ID')?> </font></strong></td>
            <td width="15%"><?= $model->musteri->name; ?> </td>
            <td><p class="pull-right no-print">
                    <?= Html::button('<i class="fa fa-print print-btn"></i>',
                        ['target' => '_black', 'class' => 'btn btn-sm btn-primary']) ?>
                </p></td>
        </tr>
    </table>
    <div class="table-responsive">
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['style' => 'display: none'],
            'options' => ['style' => 'font-size:11px;'],
            'filterModel' => false,
            'rowOptions' => function($model){
                return ['style'=> ($model->status!=2)?'':'background:#EF5350'];
            },
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['style' => 'width:25px;'],
                ],
                [
                    'attribute' => 'tur2',
                    'label' => Yii::t('app', "Ne"),
                    'value' => function($model) {
                      return $model['tur2'];
                  },
                    'options' => ['width' => '90px'],
                    'format' => 'html'
                ],
                [
                    'attribute' => 'tur',
                    'label' => Yii::t('app', "Iplik turi"),
                    'value' => function($model) {
                        return $model['tur'];
                    },
                    'options' => ['width' => '90px'],
                    'format' => 'html'
                ],
                [
                    'attribute' => 'xom_mato',
                    'value' => function($model){
                        return  number_format($model['xom_mato'] ,2,',',' ') ;
                    },
                ],
                [
                    'attribute' => 'miqdor',
                    'value' => function($model){

                        return  number_format($model['miqdor'] ,2,',',' ') ;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'yuklama',
                    'format' =>  ['date', 'php:d.m.Y']

                ],
             ],
        ]); ?>
    </div>
<?php
$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .print_div,.stone_div{
        width: 70px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        position: relative;
        margin-bottom: 3px;
    }
    .list_prints,.list_stone{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
    .check_button{
        position: absolute;
        bottom: -18px;
        left: 30%;
    }
CSS;
$this->registerCss($css);
$js = <<< JS
    $('body').delegate('.add-order', 'click', function(e){
        e.preventDefault();
        $('#order-modal').modal('show');
        $('#order-modal').find('.modal-body').load($(this).attr('href'));
    });
$(document).ready(function () {
    $('body').delegate('.print-info','click',function () {
        printDivById();
    });
    function printDivById() {
        var oldHtml = document.body.innerHTML;
        document.body.innerHTML = document.getElementById('print-info').innerHTML;
        window.print();
        document.body.innerHTML = oldHtml;
        return false;
    }
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);