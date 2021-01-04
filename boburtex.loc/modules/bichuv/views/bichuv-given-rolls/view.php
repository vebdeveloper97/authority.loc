<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */

$this->title = "NCH-№{$model->doc_number}/{$model->reg_date}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Given Rolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="bichuv-given-rolls-view">
        <?php if (!Yii::$app->request->isAjax):?>
            <div class="pull-right" style="margin-bottom: 15px;">
                <?php if (P::can('bichuv-given-rolls/update')): ?>
                    <?php if ($model->status != $model::STATUS_SAVED): ?>
                        <?= Html::a(Yii::t('app', 'Save and finish'), ['save-and-finish', 'id' => $model->id, 't' => $model->type], ['class' => 'btn btn-success']) ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 't' => $model->type], ['class' => 'btn btn-primary']) ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (P::can('bichuv-given-rolls/delete')): ?>
                    <?php if ($model->status != $model::STATUS_SAVED): ?>
                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 't' => $model->type], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?= Html::a(Yii::t('app', 'Back'), ["index", 't' => $model->type], ['class' => 'btn btn-info']) ?>
            </div>
        <?php endif; ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'nastel_party',
                'reg_date',
                'add_info:ntext',
                [
                    'label' => Yii::t('app', 'Yaratgan shaxs'),
                    'attribute' => 'created_by',
                    'value' => function ($model) {
                        return (\app\models\Users::findOne($model->created_by)) ? \app\models\Users::findOne($model->created_by)->user_fio : $model->created_by;
                    }
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return (app\modules\bichuv\models\BichuvGivenRolls::getStatusList($model->status)) ? app\modules\bichuv\models\BichuvGivenRolls::getStatusList($model->status) : $model->status;
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        return (time() - $model->created_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->created_at), 'relativeTime') : date('d.m.Y H:i', $model->created_at);
                    }
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => function ($model) {
                        return (time() - $model->updated_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->updated_at), 'relativeTime') : date('d.m.Y H:i', $model->updated_at);
                    }
                ],
            ],
        ]) ?>

        <?php
        $items = $model->getRollItems(false, true);
        $details = $model->getDetails();
        $modelLists = $model->modelRelProductions;
        ?>
        <h4 class="text-blue"><?= Yii::t('app','Model va ranglari');?>:</h4>
        <table class="table-responsive table-bordered table">
            <?php if(!empty($modelLists)):?>
            <?php foreach ($modelLists as $modelList):?>
                <tr>
                    <td><?= $modelList->modelsList->article." - ".$modelList->modelsList->name; ?></td>
                    <?php if(!empty($modelList->model_var_part_id)):?>
                        <td><?= $modelList->modelVarPart->basePatternPart->name." ".$modelList->modelVariation->colorPan->code." ".$modelList->modelVariation->name; ?></td>
                    <?php else:?>
                        <td><?= $modelList->modelVariation->colorPan->code." ".$modelList->modelVariation->name; ?></td>
                    <?php endif;?>

                </tr>
            <?php endforeach;?>
            <?php endif;?>
        </table>
        <?php
        $totalKg = 0;
        ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Mato Nomi'); ?></th>
                <th><?= Yii::t('app', 'En/gramaj'); ?></th>
                <th><?= Yii::t('app', 'Rang'); ?></th>
                <th><?= Yii::t('app', 'Partya № / Mijoz №'); ?></th>
                <th><?= Yii::t('app', 'Buyurtmachi'); ?></th>
                <th><?= Yii::t('app', 'Miqdori(kg)'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($items)): ?>
                <?php
                foreach ($items as $key => $item):?>
                    <tr>
                        <td><?= ($key + 1); ?></td>
                        <td><?php
                            if ($item['is_accessory'] && $item['is_accessory'] != 1) {
                                echo "{$item['mato']}-{$item['thread']}";
                            } else {
                                echo "{$item['mato']}-{$item['ne']}-{$item['thread']}|{$item['pus_fine']}";
                            }
                            ?></td>
                        <td><?php
                            if ($item['is_accessory'] && $item['is_accessory'] != 1) {
                                echo Yii::t('app', 'Aksessuar');
                            } else {
                                echo "{$item['en']} sm/{$item['gramaj']} gr/m<sup>2</sup>";
                            }
                            ?>
                        </td>
                        <td><?= "{$item['ctone']} {$item['color_id']} {$item['pantone']}"; ?></td>
                        <td><?= $item['party_no'] . " / " . $item['musteri_party_no']; ?></td>
                        <td><?= $item['name']; ?></td>
                        <td><?= $item['rulon_kg']; ?></td>

                    </tr>
                    <?php
                    $totalKg += $item['rulon_kg'];
                endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="5" class="text-center"><?= Yii::t('app', 'Jami'); ?></th>
                <th></th>
                <th><?= $totalKg; ?></th>
            </tr>
            </tfoot>
        </table>
    </div>
    <div id="modal" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
<?php

$js = <<< JS
    $('body').delegate('.load-content','click',function (e) {
        e.preventDefault();
        let url = $(this).attr("href");
        $("#modal").modal("show");
        $("#modal").find(".modal-body").load(url);
    });
    $('body').delegate('.print-content','click',function (e) {
        e.preventDefault();
        printDivById('print-div');
    });
    function printDivById(content_id) {
        let new_content = $("#"+content_id).html();
        $('.wrapper').hide();
        $('body').append("<div id='new_content_print'>"+new_content+"</div>");
        window.print();
        $('#new_content_print').remove();
        $('.wrapper').show();
        return false;
    }
JS;
$this->registerJs($js, \yii\web\View::POS_READY);