<?php



/* @var $this \yii\web\View */
/* @var $toquv_orders null|static */
/* @var $toquv_order \app\modules\toquv\models\ToquvOrders */
?>
<?php if (!$toquv_orders) {?>
    <h4><?php echo Yii::t('app','Buyurtma yaratilmagan')?></h4>
<?php }else{?>
    <?php foreach ($toquv_orders as $toquv_order) {?>
    <?=$toquv_order->document_number?>
<?php } }?>
