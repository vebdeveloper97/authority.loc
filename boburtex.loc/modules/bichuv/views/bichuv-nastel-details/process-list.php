<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 25.02.20 14:29
 */



/* @var $this \yii\web\View */
/* @var $list array */
?>
<div class="nastel-detail-process">
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>
                    <?php echo Yii::t('app','Nastel No')?>
                </th>
                <th>
                    <?php echo Yii::t('app','Started Time')?>
                </th>
                <th>
                    <?php echo Yii::t('app','Status')?>
                </th>
                <th></th>
            </tr>
        </thead>
        <?php foreach ($list as $item) {?>
            <tr>
                <td>
                    <?=$item['nastel_no']?>
                </td>
                <td>
                    <?=$item['started_time']?>
                </td>
                <td>
                    <?=$item['action']?>
                </td>
                <td>
                    <button class="btn btn-danger end-process" data-id="<?=$item['bgri_id']?>" data-toggle="modal" data-target="#modal-process">
                        <?php echo Yii::t('app','Jarayonni tugatish')?>
                    </button>
                </td>
            </tr>
        <?php }?>
    </table>
</div>
<?php
\yii\bootstrap\Modal::begin([
    'id' => 'modal-process',
    'header' => Yii::t('app','Jarayon'),
    'size' => 'modal-md',
    'options' => [
        'style' => 'background: black;'
    ]
]);
?>

<?php
\yii\bootstrap\Modal::end()
?>
<?php
$url = \yii\helpers\Url::to('process');
$js = <<< JS
    $("body").delegate(".end-process","click",function(e) {
        let url = "{$url}?id=" + $(this).attr('data-id');
        $('#modal-process').find('.modal-body').load(url);
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
