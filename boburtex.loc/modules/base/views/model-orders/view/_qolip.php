<?php
    /* @var $model \app\modules\base\models\ModelOrders */
    /* @var $variant_id \app\modules\base\models\ModelOrdersVariations */
    use yii\helpers\Html;
    /** BasePattern va  boshqa tablelarni bog'lash uchun ishlatilyabdi */
    $res = $model->getPatterns($model->id);
    $miniPostal = $model->getMiniPostal($model->id);
    if(!empty($res)){
        ?>
            <table class="table table-bordered">
                <thead>
                    <th>#</th>
                    <th><?=Yii::t('app', 'Pattern name')?></th>
                    <th><?=Yii::t('app', 'Pattern Variation')?></th>
                    <th><?=Yii::t('app', 'Qolip Andoza Detallari')?></th>
                    <th><?=Yii::t('app', 'Postal')?></th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td>
                            <?=$res['pattern_name']; ?>
                        </td>
                        <td>
                            <div style="width: 50px">
                                <?=$res['base_patterns_variation_no']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="row">
                            <?php
                            $count = count($res['patterns_items']['base_pattern_part']);
                            for($i = 0; $i < $count; $i++): ?>
                                <div class="col-sm-3">
                                    <div class="thumbnail">
                                        <p><strong><?=Yii::t('app', 'Qism nomi')?> </strong> <small class="text-danger"><?=$res['patterns_items']['base_pattern_part'][$i]?></small></p>
                                        <p><strong><?=Yii::t('app', 'Andoza detali')?> </strong> <small class="text-danger"><?=$res['patterns_items']['base_detail_list'][$i]?></small></p>
                                        <p><strong><?=Yii::t('app', 'Detal guruhi')?> </strong> <small class="text-danger"><?=$res['patterns_items']['bichuv_detail_type_name'][$i]?></small></p>
                                    </div>
                                </div>
                            <?php endfor; ?>
                            </div>
                        </td>
                        <td style="border: 1px solid red;">
                            <div class="row" style="width: 200px;">
                            <?php if($miniPostal): ?>
                                <div class="col-sm-6"><strong><?=Yii::t('app', 'Size')?></strong></div>
                                <div class="col-sm-6">
                                    <strong><?=Yii::t('app', 'Loss')?></strong>
                                </div>
                                <?php foreach ($miniPostal as $k => $v): ?>
                                    <div class="col-sm-6"><?=$v['name']?></div>
                                    <div class="col-sm-6"><?=$v['loss'];?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php
    }
    else{
        ?>
            <p class="alert alert-warning">
                <?=Yii::t('app', 'Qoliplar biriktirilmagan'); ?>
            </p>
        <?php
    }