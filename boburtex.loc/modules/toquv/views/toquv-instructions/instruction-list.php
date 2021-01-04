<?php

use app\modules\toquv\models\ToquvInstructions;
use app\widgets\helpers\Script;

/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\toquv\models\ToquvInstructionsSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Instructions');
$this->params['breadcrumbs'][] = $this->title;

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax; ?>
<div class="toquv-orders-index">
    <?php Pjax::begin(['id' => 'instructionListPjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'musteri_id',
                'value' => function($model){
                    return $model['mname'];
                },
                'filter' => $searchModel->getMusteriList()
            ],
            [
                'attribute' => 'doc_number_and_date',
                'label' => Yii::t('app','Hujjat raqami va sanasi'),
                'value' => function($model){
                    return $model['document_number']." - ".date('d.m.Y H:i:s', strtotime($model['reg_date']));
                }
            ],
            [
              'attribute' => 'quantity',
              'label' => Yii::t('app','Mato miqdori (kg)'),
              'value' => function($model){
                 return $model['quantity'];
              }
            ],
            [
               'label' => Yii::t('app',"Ko'rsatmani yopish"),
               'attribute' => 'is_closed',
               'value' => function($model){
                   $rm = ToquvInstructions::getInsRM($model['tid']);
                   if($rm){
                       return Html::button(Yii::t('app',"Tugatilgan"), [
                           'class'       => 'btn btn-primary form-control',
                       ]);
                   }else{
                       return Html::button(Yii::t('app',"Ko'rsatmani yopish"), [
                           'class'       => 'btn btn-danger form-control callModal',
                           'data-id'     => $model['orderId'],
                           'data-toggle' => 'modal',
                           'data-target' => '#instructionShowModal'
                       ]);
                   }

               },
               'format' => 'raw'
            ],
        ],
    ]); ?>
    <div class="modal fade" id="instructionShowModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?=Yii::t('app',"Ko'rsatmani ko'rish oynasi")?></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <?php
    $urlAjax = Url::to(['ajax']);
    $urlSaveAndFinish = Url::to(['save-and-finish']);
    $urlCloseInstructions = Url::to(['close-instructions']);
    Script::begin()
    ?>
    <script>
        $('body').delegate('.callModal','click',function(){
            let id = $(this).data('id');
            $('#instructionShowModal .modal-body').load("<?= $urlAjax ?>?id="+id,function(){
            });
        });
        $('body').delegate('.save-each-instruction','click', function (e) {
           let id = $(this).data('id');
           let rmId = $(this).data('rm-id');
           let orderId = $(this).data('order-id');
           if(true){
               $.ajax({
                   url:'<?= $urlSaveAndFinish; ?>?id='+id+'&orderId='+rmId,
                   success: function (response) {
                       if(response.status == 1){
                           $('#instructionShowModal .modal-body').load("<?= $urlAjax ?>?id="+orderId,function(){});
                       }
                   }
               });
           }
        });
        $('body').delegate('#closeInstructions','click', function (e) {
            if(true){
                let rmId = $(this).data('rm-ids');
                $.ajax({
                    url:"<?= $urlCloseInstructions;?>",
                    type:"POST",
                    data: {ids: rmId},
                    success: function (response) {
                        if(response.status == 1){
                            $('#instructionShowModal').modal('hide');
                            $.pjax.reload({container: "#instructionListPjax"});
                            $('.modal-backdrop').removeClass('in');
                        }
                    }
                });
            }
        });
    </script>
    <?php Script::end()?>
    <?php Pjax::end(); ?>

</div>