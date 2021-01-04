<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvNastelDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$title = ($this->context->_process) ? $this->context->_process['name'] : '';
$title = ($this->context->_type) ? "  " . $this->context->_type['name'] : $title;
$title = ($this->context->_table) ? "  " . $this->context->_table['name'] : $title;
$this->title = $title;
if(!empty($this->context->slug) && !empty($this->context->type)){
    $this->params['breadcrumbs'][] = ['label' => $this->context->_process['name'], 'url' => ['index', 'slug' => $this->context->slug]];
}
if(!empty($this->context->slug) && !empty($this->context->type) && !empty($this->context->table)){
    $this->params['breadcrumbs'][] = ['label' => $this->context->_type['name'], 'url' => ['index', 'slug' => $this->context->slug, 'type' => $this->context->type]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if(empty($this->context->slug) && empty($this->context->type) && empty($this->context->table)){?>
    <div class="text-center">
        <?php foreach ($bichuv_processes as $key => $item):?>
            <a href="<?=\yii\helpers\Url::to(['index', 'slug' => $item['slug']])?>" class="btn btn-success btn-lg">
                <?=$item['name']?>
            </a>
        <?php endforeach;?>
    </div>
<?php }?>
<?php if(!empty($this->context->slug) && empty($this->context->type)){?>
    <div class="text-center">
        <?php foreach ($detail_types as $key => $item):?>
                <a href="<?=\yii\helpers\Url::to(['index', 'slug' => $this->context->slug, 'type' => $item['slug']])?>" class="btn btn-success btn-lg">
                    <?=$item['name']?>
                </a>
        <?php endforeach;?>
    </div>
<?php }?>

<?php if(!empty($this->context->slug) && !empty($this->context->type) && empty($this->context->table)){?>
    <div class="text-center">
        <?php foreach ($tables as $key => $item):?>
            <a href="<?=\yii\helpers\Url::to(['index', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $item['slug']])?>" class="btn btn-success btn-lg">
                <?=$item['name']?>
            </a>
        <?php endforeach;?>
    </div>
<?php }?>

<?php if(!empty($this->context->slug) && !empty($this->context->type) && !empty($this->context->table)){?>
    <div class="row">
        <div class="col-md-12">
            <p class="pull-right no-print">
                <?= ($check==0)?Html::a('<span class="fa fa-plus"></span>', ['section', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $this->context->table], ['class' => 'btn btn-sm btn-success']) : Yii::t('app', 'Yangi jarayonni boshlash uchun boshlangan jarayonni to\'xtatishingiz lozim'); ?>
            </p>
        </div>
    </div>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php foreach ($processes as $key => $item):?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading_<?=$item['id']?>">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?=$item['id']?>" aria-expanded="false" aria-controls="collapse_<?=$item['id']?>">
                            <button class="btn btn-success btn-lg">
                                <?=$item['nastel_no']?>
                            </button>
                            <span class="btn btn-default btn-md">
                                <?=$item->bichuvGivenRollItems->mato?>
                            </span>
                            <span class="btn btn-default btn-md">
                                <small><?php echo Yii::t('app','Partiya No')?> : </small>
                                <?=$item->bichuvGivenRollItems->party_no?>
                            </span>
                            <span class="btn btn-default btn-md">
                                <small><?php echo Yii::t('app','Boshlandi')?> : </small>
                                <?=$item->started_time?>
                                <br>
                                <small><?php echo Yii::t('app','Boshladi')?> : </small>
                                <?=$item->userStarted->user_fio?>
                            </span>
                            <?php if($item['action']==\app\modules\bichuv\models\BichuvNastelProcesses::ACTION_END){?>
                                <span class="btn btn-default btn-md">
                                    <small><?php echo Yii::t('app','Tugatildi')?> : </small>
                                    <?=$item->ended_time?> <br>
                                    <small><?php echo Yii::t('app','Tugatdi')?> : </small>
                                    <?=$item->userEnded->user_fio?>
                                </span>
                            <?php }?>
                        </a>
                        <?php if($item['action']<\app\modules\bichuv\models\BichuvNastelProcesses::ACTION_END){?>
                        <p class="pull-right no-print">
                            <?= Html::a('<span class="fa fa-pencil"></span>', ['process', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $this->context->table, 'id' => $item['id']], ['class' => 'btn btn-sm btn-success']) ?>
                        </p>
                        <?php }?>
                    </h4>
                </div>
                <div id="collapse_<?=$item['id']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?=$item['id']?>">
                    <div class="panel-body">
                        <div class="row">
                            <?php if($item->bichuvGivenRollItemsSubs){ foreach ($item->bichuvGivenRollItemsSubs as $sub){?>
                                <div class="col-md-5">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td><?php echo Yii::t('app','Nastel umumiy miqdori(rulon)')?></td>
                                            <td><?php echo Yii::t('app','Nastel umumiy miqdori(kg)')?></td>
                                            <td><?php echo Yii::t('app','Required Count')?></td>
                                            <td><?php echo Yii::t('app','Qoldiq (rulon)')?></td>
                                            <td><?php echo Yii::t('app','Qoldiq (kg)')?></td>
                                            <td><?php echo Yii::t('app','Otxod (kg)')?></td>
                                            <td><?php echo Yii::t('app','Add Info')?></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th><?=$item->bichuvGivenRollItems->roll_count?></th>
                                            <th><?=$item->bichuvGivenRollItems->quantity?></th>
                                            <th><?=$item->bichuvGivenRollItems->required_count?></th>
                                            <th><?php echo $sub['roll_remain']?></th>
                                            <th><?php echo $sub['remain']?></th>
                                            <th><?php echo $sub['otxod']?></th>
                                            <th><?php echo $sub['add_info']?></th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php }}?>
                            <?php if($item->getNastelItemsList($item['id'])){ ?>
                                <div class="col-md-7">
                                    <table class="table table-responsive table-bordered text-center">
                                        <tbody>
                                        <tr>
                                            <th>
                                                <?php echo Yii::t('app',"O'lcham")?>
                                            </th>
                                            <th>
                                                <?php echo Yii::t('app','Ish soni(reja)')?>
                                            </th>
                                            <th>
                                                <?php echo Yii::t('app','Ish soni(fakt)')?>
                                            </th>
                                            <th>
                                                <?php echo Yii::t('app','Brak')?>
                                            </th>
                                        </tr>
                                        <?php $count = 0;
                                        $count_fakt = 0;
                                        $count_brak = 0;
                                        foreach ($item->bichuvNastelDetailItems as $roll_item) {
                                            $item = $roll_item['required_count'] ?? $roll_item['required_weight'];
                                            $item_count = $roll_item['count'] ?? $roll_item['weight'];?>
                                            <tr>
                                                <th>
                                                    <?=$roll_item->size['name']?>
                                                </th>
                                                <th>
                                                    <?=$item?>
                                                </th>
                                                <th>
                                                    <?=$item_count?>
                                                </th>
                                                <th>
                                                    <?=$roll_item['brak']?>
                                                </th>
                                            </tr>
                                            <?php $count += $item; $count_fakt += $item_count; $count_brak += $item['brak']; }?>
                                        <tr>
                                            <th>
                                                <?php echo Yii::t('app','Jami')?>
                                            </th>
                                            <th>
                                                <?=$count?>
                                            </th>
                                            <th>
                                                <?=$count_fakt?>
                                            </th>
                                            <th>
                                                <?=$count_brak?>
                                            </th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
<?php }?>

