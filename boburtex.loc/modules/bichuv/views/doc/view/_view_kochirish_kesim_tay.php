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
use app\components\PermissionHelper as P;

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
        <?php if ($model->to_department!=$usluga&&$model->to_department!=$tayyorlov){?>
            <?=$model->checkBgr();?>
        <?php }?>
    <?php endif;?>
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?php if (P::can('doc/kochirish_mato/update')): ?>
            <?php if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], ['class' => 'btn btn-primary']) ?>
                <?php if($model->checkBgr(true)<=0||$model->to_department==$usluga||$model->to_department==$tayyorlov||$model->to_department==$print||$model->to_department==$pattern){?>
                    <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t],
                    ['class' => 'btn btn-success']) ?>
                <?php }?>
                <?php if (P::can('doc/kochirish_mato/delete')): ?>
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
        <?php if ($t == 1 || $t == 5): ?>
            <?php
            $items = $model->getSliceMovingView($model->id);
            $modelData = $model->getModelListInfoOld();
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
                </tr>
                </thead>
                <tbody>
                <?php
                $totalKg = 0;
                foreach ($items as $key => $item):?>
                    <tr>
                        <td><?= ($key + 1); ?></td>
                        <td class="expand-party">
                            <?= $item['nastel_party']; ?>
                        </td>
                        <td><?= $item['name']; ?></td>
                        <td><?= number_format($item['quantity'], 0); ?></td>
                    </tr>
                    <?php
                    $totalKg += $item['quantity'];
                endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                    <td class="text-bold"><?= $totalKg; ?></td>
                </tr>
                </tfoot>
            </table>
        <?php elseif ($t == 2): ?>
            <?php $items = $model->getAccessoriesView(); ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', 'Aksessuar'); ?></th>
                    <th><?= Yii::t('app', 'Nastel Party'); ?></th>
                    <th><?= Yii::t('app', "Model"); ?></th>
                    <th><?= Yii::t('app', 'Soni'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $totalKg = 0;
                foreach ($items as $key => $item):?>
                    <tr>
                        <td><?= ($key + 1); ?></td>
                        <td>
                            <?= $item['sku'] . '-' . $item['acs'] . '-' . $item['property']; ?>
                        </td>
                        <td class="expand-party">
                            <?= $item['nastel_no']; ?>
                        </td>
                        <td><?= $item['model']; ?></td>
                        <td><?= number_format($item['quantity'], 0,'.',' '); ?></td>
                    </tr>
                    <?php
                    $totalKg += $item['quantity'];
                endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                    <td class="text-bold"><?= $totalKg; ?></td>
                </tr>
                </tfoot>
            </table>
        <?php elseif ($t == 3): ?>
            <div class="center-text">
                <?php $items = $model->getMatoView($model->id, $t); ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Mato</th>
                        <th>En/Gramaj</th>
                        <th>Rangi</th>
                        <th>Model</th>
                        <th>Nastel №</th>
                        <th>Partiya № / Mijoz Partiya</th>
                        <th>Rulon soni</th>
                        <th>Miqdori (kg)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalRoll = 0;
                    $totalKg = 0;
                    foreach ($items as $key => $item):?>
                        <tr>
                            <td><?= ($key + 1); ?></td>
                            <td class="expand-party" data-bss-id="<?= $item['id']; ?>">
                                <?php echo "{$item['mato']}-{$item['ne']}-{$item['ip']}|{$item['pus_fine']}"; ?>
                            </td>
                            <td>
                                <?php echo "{$item['mato_en']} sm /{$item['gramaj']} gr/m<sup>2</sup>"; ?>
                            </td>
                            <td><?= "{$item['ctone']} {$item['color_id']} {$item['pantone']}" ?></td>
                            <td><?= $item['model'] ?></td>
                            <td class="text-red"><?= $item['nastel_no'] ?></td>
                            <td><?= "{$item['partiya_no']}/{$item['mijoz_part']}" ?></td>
                            <td><?= number_format($item['count_rulon'], 0, '.', ' '); ?></td>
                            <td><?= $item['rulon_kg'] ?></td>
                        </tr>
                        <?php
                        $totalKg += $item['rulon_kg'];
                        $totalRoll += $item['count_rulon'];
                    endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                        <td class="text-bold"><?= $totalRoll ?></td>
                        <td class="text-bold"><?= $totalKg; ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if ($t == 1): ?>
    <div id="modal" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
<?php endif;
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
JS;
$this->registerJs($js,\yii\web\View::POS_READY);