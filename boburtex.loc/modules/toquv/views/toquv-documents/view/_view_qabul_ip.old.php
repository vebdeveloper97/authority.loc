<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {doc_type}',['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">

    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('toquv-documents/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED && $model->action == 2):?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?= Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
    </div>
    <?php $items = $model->getAcceptedItems($model->id, 2, $model->to_department); ?>
    <?php if(count($items) > 0):?>
        <table class="table table-bordered table-responsive">
            <tr>
                <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name; ?></td>
                <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->fromEmployee->user_fio ?></td>
                <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->toEmployee->user_fio ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('app','Imzo')?> _____________________</td>
                <td><?= Yii::t('app','Imzo')?> _____________________</td>
            </tr>
        </table>
        <form action="<?= Url::to(['/toquv/toquv-documents/view', 'id' => $model->id, 'slug' => $this->context->slug])?>" method="post">
        <div class="center-text">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= Yii::t('app','Ip nomi')?></th>
                    <?php if($model->action == 11):?>
                        <th><?= Yii::t('app','Miqdori (Hujjatda)')?></th>
                        <th><?= Yii::t('app','Qabul qilingan miqdor')?></th>
                        <th><?= Yii::t('app','Qoldiq')?></th>
                    <?php else:?>
                        <th><?= Yii::t('app','Miqdori (Hujjatda)')?></th>
                        <th><?= Yii::t('app','Qabul qilingan miqdor')?></th>
                        <th><?= Yii::t('app','Miqdori (Fakt)')?></th>
                        <th><?= Yii::t('app','Qoldiq')?></th>
                    <?php endif;?>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                $totalAccepted = 0;
                foreach ($items as $key => $item):?>
                    <tr>
                        <td><?= ($key+1) ?></td>
                        <td style="width:400px;"><?= "{$item['ipname']}-{$item['nename']}-{$item['thrname']}-{$item['clname']}({$item['lot']})"?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= ($item['quantity'] - $item['qoldiq']) ?></td>
                        <?php if($model->action != 11):?>
                            <td>
                                <div class="form-group">
                                    <div style="position: relative;">
                                        <input type="hidden" name="Items[<?= $key ?>][entity_id]" value="<?= $item['entity_id']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][entity_type]" value="<?= $item['entity_type']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][package_type]" value="<?= $item['package_type']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][package_qty]" value="<?= $item['package_qty']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][price_sum]" value="<?= $item['price_sum']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][price_usd]" value="<?= $item['price_usd']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][is_own]" value="<?= $item['is_own']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][lot]" value="<?= $item['lot']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][unit_id]" value="<?= $item['unit_id']?>">
                                        <input type="hidden" name="Items[<?= $key ?>][document_qty]" value="<?= $item['quantity']?>">
                                        <input type="number" name="Items[<?= $key ?>][quantity]" max="<?= $item['qoldiq']?>" min="0.000" step="any"  value="<?= $item['qoldiq']?>" class="checkMaxValueAccept form-control" required="required">
                                        <span class="tooltiptext">
                                            <?= Yii::t('app', "Miqdor {qty} dan katta bo'lmasligi kerak", ['qty' => $item['qoldiq']])?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        <?php endif;?>
                        <td data-remain="<?=$item['qoldiq']?>" class="diff-accept">0</td>
                    </tr>
                    <?php
                    $total += $item['quantity'];
                    $totalAccepted += ($item['quantity'] - $item['qoldiq']);
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp</td>
                    <td>&nbsp</td>
                    <td style="font-weight:bold;font-size:1.1em">
                        <?= number_format($total,3,'.', ' '); ?>
                    </td>
                    <td style="font-weight:bold;font-size:1.1em">
                        <?= number_format($totalAccepted,3,'.', ' '); ?>
                    </td>
                    <td>&nbsp</td>
                </tr>
                </tfoot>
            </table>
            <div class="form-group pull-right">
                <?php if($model->action != 11):?>
                    <button class="btn btn-primary" type="submit"><?=Yii::t('app','Save and finish'); ?></button>
                <?php endif;?>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div style="padding: 15px;font-size: 1.2em;">
            <p class="text-center"><?= Yii::t('app',"Ma'lumot mavjud emas!")?></p>
        </div>

    <?php endif;?>

</div>
<?php
$this->registerCss("
span.tooltiptext {
  visibility: hidden;
  width: 100%;
  background-color: #ecf0f5;
  color: red;
  border:1px solid red;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  top: 24px;
  left: 0;
}

.tooltiptext::after {
  content: ' ';
  position: absolute;
  top: -10px; 
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: transparent transparent #f30505 transparent;
}
");

$this->registerJs("
 $('.checkMaxValueAccept').on('keyup paste blur change', function(e){
        let max = $(this).attr('max');
        let diff = $(this).parents('tr').find('.diff-accept');
        let currentValue = $(this).val();
        let tooltip = $(this).parent().find('span.tooltiptext');
        tooltip.css('visibility','hidden');
        if((parseFloat(currentValue) > parseFloat(max)) || (parseFloat(currentValue) < 0)){
            $(this).val(parseFloat(max));
            tooltip.css('visibility','visible');
        }
        let accepted = parseFloat(diff.data('remain'));
        
        diff.html(accepted - $(this).val());
        
    });
    
    $('form input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});
");


?>
