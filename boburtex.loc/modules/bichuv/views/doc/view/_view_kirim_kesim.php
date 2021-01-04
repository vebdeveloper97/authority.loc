<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 28.05.20 17:03
 */

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$aks = $model->aks;
?>
<div class="toquv-documents-view">
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('doc/qabul_kesim/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Qabul qilish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromHrDepartment->name ?></td>
            <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->toHrDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromHrEmployee->fish ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toHrEmployee->fish ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>

        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?php
        $items = $model->getSliceMovingView($model->id);
        ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app',"Model");?></th>
                <th><?= Yii::t('app',"O'lcham");?></th>
                <th><?= Yii::t('app','Soni');?></th>
                <th></th>
                <th><?= Yii::t('app',"O'rtacha ish og'irligi (gr)");?></th>
                <th><?= Yii::t('app','Miqdori(kg)');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalRoll = 0;
            $totalKg = 0;
            foreach ($items as $key=> $item):?>
                <tr>
                    <td><?= ($key+1);?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party']  ?>
                    </td>
                    <td><?= $item['model'];?></td>
                    <td><?= $item['name'];?></td>
                    <td><?= number_format($item['quantity'],0);?></td>
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
                    <td><?= number_format($item['work_weight'],0)?></td>
                    <td><?= $item['quantity']*$item['work_weight']/1000;?></td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
                $totalRoll += $item['quantity']*$item['work_weight']/1000;
            endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td></td>
                <td></td>
                <td class="text-bold"><?= $totalRoll?></td>
            </tr>
            </tfoot>
        </table>
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
$('.modals').on('click',function(e) {
         e.preventDefault();
         $('#modal').modal('show').find('#modal-body').load($(this).attr('href'));
   });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);

