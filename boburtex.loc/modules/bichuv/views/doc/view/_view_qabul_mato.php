<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\YiiAsset;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\wms\models\WmsMatoInfo;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
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
YiiAsset::register($this);
?>
<?php $form = \yii\widgets\ActiveForm::begin(['options' => ['class' =>'form-group']]);?>
<div class="toquv-documents-view">

    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (P::can('doc/qabul_mato/update')): ?>
            <?php if($model->is_returned == 0): ?>
            <?php if($model->status < $model::STATUS_SAVED):?>
                    <?= Html::submitButton(Yii::t('app', 'Qabul qilish'), ['class' => 'btn btn-success']) ?>
            <?php endif;?>
                <?= Html::a(Yii::t('app', 'Back'), ["index", 'slug' => $this->context->slug],
                    ['class' => 'btn btn-info']) ?>
            <?php endif;?>
        <?php endif;?>
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
            <td><strong><?= Yii::t('app', 'Musteri ID')?></strong>: <?= $model->musteri->name ?></td>
            <td><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>

    <div class="center-text">
        <table class=" table table-striped table-hover table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?=Yii::t('app','Model')?></th>
                    <th><?=Yii::t('app','Name')?></th>
                    <th><?=Yii::t('app','Partiya No')?></th>
                    <th><?=Yii::t('app','Musteri Partiya No')?></th>
                    <th><?=Yii::t('app','Miqdori(kg)')?></th>
                    <?php if($model->status < $model::STATUS_SAVED):?>
                        <th><?=Yii::t('app','Miqdori(qabul)')?></th>
                    <?php else:?>
                        <th><?=Yii::t('app','Miqdori(fact)')?></th>
                    <?php endif;?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($model->bichuvDocItems as $key => $bichuvDocItem):?>
                <tr>
                    <td>
                        <?php
                        $data = BichuvDocItems::getOrderDataByModelOrdersItemsId($bichuvDocItem['model_orders_items_id']);
                        echo (!empty($data)) ? "<b>".$data['data']['model_name'] ."</b><br><small><i>".$data['data']['article'] . "</i></small>"  : '';
                        ?>
                    </td>
                    <td>
                        <?=WmsMatoInfo::getMaterialNameById($bichuvDocItem->entity_id) ?>
                    </td>
                    <td>
                        <?=$bichuvDocItem->party_no?>
                    </td>
                    <td>
                        <?=$bichuvDocItem->musteri_party_no?>
                    </td>
                    <td>
                        <?=$bichuvDocItem->quantity?>
                    </td>
                    <?php if($model->status < $model::STATUS_SAVED):?>
                        <td style="width: 200px;">
                            <?php
                                $format_quantity =  number_format($bichuvDocItem->quantity,0,'','');
                                $format_fact_quantity =  number_format($bichuvDocItem->fact_quantity,0,'','');
                            ?>
                            <?= $form->field($bichuvDocItem,'['.$key.']id')
                                ->hiddenInput(['class' => 'form-control '])->label(false); ?>

                            <?= $form->field($bichuvDocItem,'['.$key.']quantity')
                                ->hiddenInput([
                                    'class' => 'form-control quantity',
                                    'value' => number_format($bichuvDocItem->quantity,0,'',''),
                                ])->label(false); ?>

                            <?= $form->field($bichuvDocItem,'['.$key.']fact_quantity')
                                ->textInput([
                                    'value' => (!empty($bichuvDocItem->fact_quantity) ?  $bichuvDocItem->fact_quantity : $format_quantity),
                                    'class' => 'form-control fact_quantity',
                                    'type' => 'number',
//                                    'readonly' => (!empty($bichuvDocItem->fact_quantity) ?  true : false),

                                ])->label(false); ?>

                            <?= $form->field($bichuvDocItem,'['.$key.']add_info')
                                ->textarea([
                                    'rows' => 1,
                                    'cols' => 15,
                                    'class' => (($format_fact_quantity != 0 && $format_quantity != $format_fact_quantity) ? 'form-control' : 'form-control add_info'),
//                                    'readonly' => (($format_fact_quantity != 0 && $format_quantity != $format_fact_quantity) ? true : false),

                                ])->label(false); ?>

                        </td>
                    <?php else:?>
                        <td><?=$bichuvDocItem->fact_quantity."<br>".$bichuvDocItem->add_info?></td>
                    <?php endif;?>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    <?php \yii\widgets\ActiveForm::end();?>
    </div>
</div>
<?php
$js = <<<JS
    $('#w0').keypress(function(e) {
        if( e.which == 13 ) {
            return false;
        }
    });
    $('.fact_quantity').on('change',function(e) {
        e.preventDefault();
        const  __this = $(this);
        let parentTd = __this.parents('td');
        let quantity = parentTd.find('input.quantity');
        let addInfo = parentTd.find('textarea.add_info');
        if(quantity.val() == __this.val()){
            addInfo.val('');
            addInfo.hide();
        }else{
            addInfo.show();
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerCss(".add_info{display: none;}");
?>
