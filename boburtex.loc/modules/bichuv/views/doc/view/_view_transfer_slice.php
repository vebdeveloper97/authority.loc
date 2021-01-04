<?php

use app\models\Constants;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');
$t = Yii::$app->request->get('t', 1);
$this->title = Yii::t('app', '{doc_type}  №{number} - {date}', [
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$usluga = \app\modules\toquv\models\ToquvDepartments::findOne(['token'=>'USLUGA'])['id'];
$tayyorlov = \app\modules\toquv\models\ToquvDepartments::findOne(['token'=> Constants::$TOKEN_TAYYORLOV])['id'];
$print = \app\modules\toquv\models\ToquvDepartments::findOne(['token'=> Constants::$TOKEN_PECHAT])['id'];
$pattern = \app\modules\toquv\models\ToquvDepartments::findOne(['token'=> Constants::$TOKEN_NAQSH])['id'];
?>
<div class="toquv-documents-view">
    <?php if ($t == 1): ?>
        <?php Pjax::begin(['id' => 'pjax-content'])?>
    <?php endif;?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?php if (Yii::$app->user->can('doc/transfer_slice/update')): ?>
            <?php if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t],
                    ['class' => 'btn btn-success']) ?>
                <?php if (Yii::$app->user->can('doc/transfer_slice/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?= Html::button('<span class="fa fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
    </div>
    <?php if ($t == 1): ?>
        <?php Pjax::end()?>
    <?php endif;?>
    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app', 'Qayerdan') ?></strong>: <?= $model->fromHrDepartment->name ?></td>
            <td><strong><?= Yii::t('app', 'Kimga') ?></strong>: <?= $model->toHrDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Javobgar shaxs') ?></strong>: <?= $model->fromHrEmployee->fish ?></td>
            <td><strong><?= Yii::t('app', 'Javobgar shaxs') ?></strong>: <?= $model->toHrEmployee->fish ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Imzo') ?></strong> _____________________</td>
            <td><strong><?= Yii::t('app', 'Imzo') ?></strong> _____________________</td>
        </tr>
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info') ?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?php if ($t == 1): ?>
            <?php
            $items = $model->getSliceMovingView($model->id);
            $modelData = $model->getModelListInfo();
            $aks = $model->aks;
            ?>
            <table class="table-bordered table">
                <tbody>
                    <tr>
                        <td class="text-bold"><?= Yii::t('app','Article');?></td>
                        <td><?= $modelData['model']?></td>
                    </tr>
                    <tr>
                        <td class="text-bold"><?= Yii::t('app','Model Ranglari');?></td>
                        <td><?= $modelData['model_var']?></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', 'Nastel Party'); ?></th>
                    <th><?= Yii::t('app', "O'lcham"); ?></th>
                    <th><?= Yii::t('app', 'Soni'); ?></th>
                    <th><?= Yii::t('app', 'Yaroqsiz soni'); ?></th>
                    <th><?= Yii::t('app', 'Izoh'); ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                // TODO :: Print or Stone bosilganda uning attachmentlarini modalga chiqrishim kerak
                $totalKg = 0;
                foreach ($items as $key => $item):?>
                
                    <tr>
                        <td><?= ($key + 1); ?></td>
                        <td class="expand-party"><?= $item['nastel_party']; ?></td>
                        <td><?= $item['name']; ?></td>
                        <td><?= number_format($item['quantity'], 0); ?></td>
                        <td><?= number_format($item['invalid_quantity'], 0); ?></td>
                        <td><?= $item['add_info'] ?></td>
                        <?php if(!empty($item['print_id'])):?>
                            <td>
                                <?= Html::a($item['print_code'], [
                                    'view-attachment',
                                    'slug' => $this->context->slug,
                                    'print_id' => $item['print_id']
                                ],['class' => 'modals']) ?>
                            </td>
                        <?php endif;?>
                        <?php if(!empty($item['stone_id'])):?>
                            <td>
                                <?= Html::a($item['stone_code'], [
                                    'view-attachment',
                                    'slug' => $this->context->slug,
                                    'print_id' => null,
                                    'stone_id' => $item['stone_id']
                                ],['class' => 'modals']) ?>
                            </td>
                        <?php endif;?>
                    </tr>
                    <?php
                    $totalKg += $item['quantity'];
                endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                    <td class="text-bold"><?= $totalKg; ?></td>
                    <td colspan="3"></td>
                </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
</div>

    <div id="modal" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div id="modal-body">

                </div>
            </div>
        </div>
    </div>
<?php
$js = <<< JS
    $('.add-konveyer').on('click',function(e) {
       e.preventDefault();
       $('#modal').modal('show').find('.modal-body').load($(this).attr('href'));
       $(this).addClass('active-link')
    });
    $("body").on("submit", ".customAjaxForm", function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        var url = $(this).attr("action");
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function (response) {
                if(response.status === 1){
                    $('#modal').modal("hide");
                    call_pnotify('success',response.message);
                    $('.active-link').remove();
                    if($('.add-konveyer').length==0){
                        $.pjax.reload({container:"#pjax-content"});
                    }
                }else{
                    call_pnotify('fail',response.message);
                }
            }
        });
    });
    function call_pnotify(status,text='Text') {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 500;
                PNotify.alert({text:text,type:'success'});
                break;    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 500;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
    
   $('.modals').on('click',function(e) {
         e.preventDefault();
         $('#modal').modal('show').find('#modal-body').load($(this).attr('href'));
   })
JS;
$this->registerJs($js,\yii\web\View::POS_READY);