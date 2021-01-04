<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.03.20 15:34
 */

use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $model null|static */
?>
<?php if ($model):?>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading_<?=$model['id']?>">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?=$model['id']?>" aria-expanded="false" aria-controls="collapse_<?=$model['id']?>">
                    <button class="btn btn-success btn-lg">
                        <?=$model->bichuvGivenRoll['nastel_party']?>
                    </button>
                    <span class="btn btn-default btn-md">
                                <?=$model->mato?>
                            </span>
                    <span class="btn btn-default btn-md">
                                <small><?php echo Yii::t('app','Boshlandi')?> : </small>
                                <?=$model->bichuvNastelProcesses[0]->started_time?>
                            </span>
                    <span class="btn btn-default btn-md">
                                <small><?php echo Yii::t('app','Boshladi')?> : </small>
                                <?=$model->bichuvNastelProcesses[0]->userStarted->user_fio?>
                            </span>
                    <span class="btn btn-default btn-md">
                                <small><?php echo Yii::t('app','Partiya No')?> : </small>
                                <?=$model->party_no?>
                            </span>
                </a>
                <?=$model->bichuvNastelProcesses[0]['action']?>
            </h4>
        </div>
        <div id="collapse_<?=$model['id']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?=$model['id']?>">
            <div class="panel-body">
                <div class="row">
                    <?php if($model->bichuvGivenRollItemsSubs){ foreach ($model->bichuvGivenRollItemsSubs as $sub){?>
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
                                    <th><?=$model->bichuvGivenRollItems->roll_count?></th>
                                    <th><?=$model->bichuvGivenRollItems->quantity?></th>
                                    <th><?=$model->bichuvGivenRollItems->required_count?></th>
                                    <th><?php echo $sub['roll_remain']?></th>
                                    <th><?php echo $sub['remain']?></th>
                                    <th><?php echo $sub['otxod']?></th>
                                    <th><?php echo $sub['add_info']?></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php }}?>
                    <?php if($model->bichuvNastelDetailItems){ ?>
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
                                foreach ($model->bichuvNastelDetailItems as $roll_item) {
                                    $model = $roll_item['required_count'] ?? $roll_item['required_weight'];
                                    $model_count = $roll_item['count'] ?? $roll_item['weight'];?>
                                    <tr>
                                        <th>
                                            <?=$roll_item->size['name']?>
                                        </th>
                                        <th>
                                            <?=$model?>
                                        </th>
                                        <th>
                                            <?=$model_count?>
                                        </th>
                                        <th>
                                            <?=$roll_item['brak']?>
                                        </th>
                                    </tr>
                                    <?php $count += $model; $count_fakt += $model_count; $count_brak += $model['brak']; }?>
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
<?php endif;?>
