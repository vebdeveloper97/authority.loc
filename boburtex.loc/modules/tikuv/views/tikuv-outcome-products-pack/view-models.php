<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvDoc */
/* @var $this yii\web\View */
/* @var $changeModel app\modules\tikuv\models\ChangeModelForm */
/* @var $modelRelDocs[] app\modules\tikuv\models\ModelRelDoc */
/* @var $searchModel app\modules\tikuv\models\TikuvDocItems */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '№{number} - {date}', [
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index"]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$url_order = Url::to(['get-order-items']);
$url_var = Url::to(['get-model-variations']);
?>

<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?= Html::button('<span class="fa fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
        <?php if (Yii::$app->user->can('doc/qabul_kesim/view')): ?>
            <?php if ($model->status != $model::STATUS_SAVED): ?>
                <?php if (Yii::$app->user->can('doc/qabul_kesim/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app', 'Qayerdan') ?></strong>: <?= $model->fromDepartment->name ?></td>
            <td><strong><?= Yii::t('app', 'Kimga') ?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Javobgar shaxs') ?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app', 'Javobgar shaxs') ?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Imzo') ?></strong> _____________________</td>
            <td><strong><?= Yii::t('app', 'Imzo') ?></strong> _____________________</td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Musteri ID') ?></strong>: <?= $model->musteri->name; ?></td>
            <td><strong><?= Yii::t('app', 'Add Info') ?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?php
        $items = $model->getSliceItems();
        $models = null;
        if(empty($modelRelDocs)||$model->is_change_model==1){
            $models = $model->getBelongToModelList();
            $orderLists = $model->getOrderItemModelList(true);
        }
        ?>
        <h4><?= Yii::t('app', "Kesim"); ?></h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Nastel Party'); ?></th>
                <th><?= Yii::t('app', "O'lcham"); ?></th>
                <th><?= Yii::t('app', 'Soni'); ?></th>
                <th><?= Yii::t('app', "O'rtacha ish og'irligi (gr)"); ?></th>
                <th><?= Yii::t('app', 'Miqdori(kg)'); ?></th>
                <th><?php echo Yii::t('app','Holati')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalRoll = 0;
            $totalKg = 0;
            $check_model_price = true;
            foreach ($items as $key => $item):?>
                <?php $model_production = \app\modules\bichuv\models\ModelRelProduction::find()->joinWith('bichuvGivenRoll bgr')->where(['bgr.nastel_party'=>$item['nastel_party_no'],'is_accepted'=>1])->asArray()->one();
                    if(empty($model_production)){
                        $check_model_price = false;
                    }
                ?>
                <tr>
                    <td><?= ($key + 1); ?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party_no'] ?>
                    </td>
                    <td><?= $item['size']; ?></td>
                    <td><?= number_format($item['quantity'], 0, '.', ' '); ?></td>
                    <td><?= number_format($item['work_weight'], 0) ?></td>
                    <td><?= $item['quantity'] * $item['work_weight'] / 1000; ?></td>
                    <td>
                        <?php if($model_production){?>
                            <span class="btn btn-success"><?=Yii::t('app','Model uchun narx tasdiqlangan!');?></span>
                        <?php }else{?>
                            <span class="btn btn-danger"><?=Yii::t('app','Model uchun narx tasdiqlanmagan!');?></span>
                        <?php }?>
                    </td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
                $totalRoll += $item['quantity'] * $item['work_weight'] / 1000;
            endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td></td>
                <td class="text-bold"><?= $totalRoll ?></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
        <h4><?= Yii::t('app', "Model ma'lumotlari"); ?></h4>
        <table class="table table-bordered">
            <?php if (!empty($models)): ?>
            <thead>
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', 'Konveyer'); ?></th>
                    <th><?= Yii::t('app', 'Nastel'); ?></th>
                    <th><?= Yii::t('app', 'Buyurtma'); ?></th>
                    <th><?= Yii::t('app', 'Yangi buyurtma'); ?></th>
                    <th><?= Yii::t('app', 'Model'); ?></th>
                    <th><?= Yii::t('app', "Yangi model"); ?></th>
                    <th><?= Yii::t('app', "Rangi"); ?></th>
                    <th><?= Yii::t('app', "Yangi rangi"); ?></th>
                    <th><?= Yii::t('app', "Panton rang kodi"); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php $form = \yii\widgets\ActiveForm::begin(); ?>
            <?php foreach ($models as $key => $item):
                $modelLists = $model->getOrderItemModelList(null,$item['order_id']);
                $modelListsAttr = $model->getOrderItemModelList(null,$item['order_id'],false,true);
                ?>
                <tr>
                    <td><?= ($key + 1) ?></td>
                    <td><?= $item['konveyer']; ?></td>
                    <td><?= $item['nastel_no']; ?></td>
                    <td><?= "{$item['musteri']} - <b>{$item['doc_number']}</b> ({$item['qty']})"; ?></td>
                    <td style="width: 250px;">
                        <?= $form->field($changeModel, "[{$item['nastel_no']}]order_id")->widget(\kartik\select2\Select2::className(), [
                            'data' => $orderLists,
                            'options' => [
                                'value' => $item['order_id'],
                                'placeholder' => Yii::t('app','Select'),
                                'required' => true
                            ],
                            'pluginEvents' => [
                                "change" => new JsExpression("function(e) { 
                                    let id = $(this).val();
                                    if(id){
                                        let modelVar = $('#orderItemId-{$key}');
                                            $.ajax({
                                                url:'{$url_order}?id='+id,
                                                success: function(response){
                                                    if(response.status){
                                                        let items = response.items;
                                                        modelVar.html('');
                                                        items.map(function(val, k){
                                                            var newOption = new Option(val.model_no+'-'+val.model_name+'-'+val.code, val.moi_id, false, false);
                                                            newOption.setAttribute('data-pantone-id', val.color_pantone_id);                                               
                                                            newOption.setAttribute('data-price', val.price);                                                   
                                                            newOption.setAttribute('data-pb-id', val.pb_id);                                                   
                                                            newOption.setAttribute('data-model-no', val.model_no);                                                   
                                                            newOption.setAttribute('data-model-name', val.model_name);                                                   
                                                            newOption.setAttribute('data-model-id', val.model_id);                                                   
                                                            modelVar.append(newOption);
                                                        });
                                                        modelVar.trigger('change');
                                                    }else{
                                                       modelVar.html('');
                                                    }
                                                }
                                            });
                                    }
                                }"),
                                "select2:clear" => new JsExpression("function(e) { 
                                    let modelVar = $('#modelVarId-{$key}');
                                    modelVar.html(''); 
                                }"),
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'escapeMarkup' => new JsExpression(
                                    "function (markup) { return markup; }"
                                ),
                                'templateResult' => new JsExpression(
                                    "function(data) { return data.text; }"
                                ),
                                'templateSelection' => new JsExpression(
                                    "function (data) { return data.text; }"
                                ),
                            ],
                        ])->label(false) ?>
                    </td>
                    <td>
                        <?= $item['article'] . " " . $item['name']; ?>
                        <?= $form->field($changeModel,"[{$item['nastel_no']}]model_no")->hiddenInput(['value' => $item['article'],'id' => 'modelNo'.$key])->label(false)?>
                        <?= $form->field($changeModel,"[{$item['nastel_no']}]model_name")->hiddenInput(['value' => $item['name'],'id' => 'modelName'.$key])->label(false)?>
                        <?= $form->field($changeModel,"[{$item['nastel_no']}]color_pantone_id")->hiddenInput(['id' => 'colorPantoneId'.$key, 'value' => $item['color_pantone_id']])->label(false)?>
                        <?= $form->field($changeModel,"[{$item['nastel_no']}]model_id")->hiddenInput(['id' => 'modelId'.$key, 'value' => $item['model_id']])->label(false)?>
                        <?= $form->field($changeModel,"[{$item['nastel_no']}]price")->hiddenInput(['id' => 'price'.$key, 'value' => $item['price']])->label(false)?>
                        <?= $form->field($changeModel,"[{$item['nastel_no']}]pb_id")->hiddenInput(['id' => 'pbId'.$key, 'value' => $item['pb_id']])->label(false)?>
                    </td>
                    <td style="width: 250px;">
                        <?= $form->field($changeModel, "[{$item['nastel_no']}]order_item_id")->widget(\kartik\select2\Select2::className(), [
                            'data' => $modelLists,
                            'options' => [
                                'value' => $item['order_item_id'],
                                'placeholder' => Yii::t('app','Select'),
                                'required' => true,
                                'id' => "orderItemId-{$key}",
                                'options' => $modelListsAttr
                            ],
                            'pluginEvents' => [
                                "change" => new JsExpression("function(e) { 
                                    let id = $(this).val();
                                    if(id){
                                        let modelVar = $('#modelVarId-{$key}');
                                            $.ajax({
                                                url:'{$url_var}?id='+id,
                                                success: function(response){
                                                    if(response.status){
                                                        let items = response.items;
                                                        modelVar.html('');
                                                        items.map(function(val, k){
                                                            var newOption = new Option(val.pantone+'-'+val.code, val.model_var_id, false, false);
                                                            newOption.setAttribute('data-pantone-id', val.color_pantone_id);                                                
                                                            modelVar.append(newOption);
                                                        });
                                                        modelVar.trigger('change');
                                                    }else{
                                                       modelVar.html('');
                                                    }
                                                }
                                            });
                                    }
                                    let pantoneId = $('option:selected', this).attr('data-pantone-id');
                                     let price = $('option:selected', this).attr('data-price');
                                     let pb = $('option:selected', this).attr('data-pb-id');
                                     let modelNo = $('option:selected', this).attr('data-model-no');
                                     let modelName = $('option:selected', this).attr('data-model-name');
                                     let modelId = $('option:selected', this).attr('data-model-id');
                                     
                                     $('#colorPantoneId".$key."').val(pantoneId);
                                     $('#price".$key."').val(price);
                                     $('#pbId".$key."').val(pb);
                                     $('#modelNo".$key."').val(modelNo);
                                     $('#modelName".$key."').val(modelName);
                                     $('#modelId".$key."').val(modelId);
                                }"),
                                "select2:clear" => new JsExpression("function(e) { 
                                    let modelVar = $('#modelVarId-{$key}');
                                    modelVar.html(''); 
                                }"),
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'escapeMarkup' => new JsExpression(
                                    "function (markup) { return markup; }"
                                ),
                                'templateResult' => new JsExpression(
                                    "function(data) { return data.text; }"
                                ),
                                'templateSelection' => new JsExpression(
                                    "function (data) { return data.text; }"
                                ),
                            ],
                        ])->label(false) ?>
                    </td>
                    <td><?= $item['pantone']."-".$item['code']; ?></td>
                    <td><?= $form->field($changeModel, "[{$item['nastel_no']}]model_var_id")->widget(\kartik\select2\Select2::className(), [
                            'data' => [$item['model_var_id'] => $item['pantone']."-".$item['code']],
                            'options' => [
                                'value' => $item['model_var_id'],
                                'id' => "modelVarId-{$key}",
                                'required' => true,
                            ],
                            'pluginEvents' => [
                                'change' => new JsExpression("function(e) { 
                                                 let id = $(this).val();
                                                 let pantoneId = $('option:selected', this).attr('data-pantone-id');
                                                 $('#colorPantoneId".$key."').val(pantoneId);                                                 
                                             }
                                        ")
                            ],
                        ])->label(false) ?>
                    </td>
                    <td><?= $form->field($changeModel, "[{$item['nastel_no']}]color_id")->widget(\kartik\select2\Select2::className(), [
                            'data' => $changeModel->getColorPantone(),
                            'options' => [
                                'value' => !empty($item['color_id'])?$item['color_id']:$item['color_pantone_id'],
                                'id' => "modelColorId-{$key}",
                                'required' => true,
                                'placeholder' => Yii::t('app','Select')
                            ],
                            'pluginEvents' => [
                            ],
                        ])->label(false) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="10">
                        <div class="form-group">
                            <label for="changeNote" class="control-label"><?= Yii::t('app',"Model o'zgarish sababi (To'liq shaklda)");?></label>
                            <textarea name="changeNote" required class="form-control" id="changeNote" cols="30" rows="3"><?= $model->change_note; ?></textarea>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="10">
                        <div class="form-group pull-left">
                            <?php if($check_model_price){?>
                                <?= Html::submitButton(Yii::t('app','Saqlash va tasdiqlash'),['class' => 'btn btn-success'])?>
                            <?php }else{?>
                                <span class="btn btn-danger"><?php echo Yii::t('app','Barcha nastellar modeli uchun narx tasdiqlanmagan!')?></span>
                            <?php }?>
                        </div>
                    </td>
                </tr>
            <?php \yii\widgets\ActiveForm::end() ?>
            </tbody>
            <?php else:?>

                <thead>
                    <tr>
                        <th>№</th>
                        <th><?= Yii::t('app', 'Nastel'); ?></th>
                        <th><?= Yii::t('app', 'Buyurtma'); ?></th>
                        <th><?= Yii::t('app', 'Yangi buyurtma'); ?></th>
                        <th><?= Yii::t('app', 'Model'); ?></th>
                        <th><?= Yii::t('app', "Yangi model"); ?></th>
                        <th><?= Yii::t('app', "Rangi"); ?></th>
                        <th><?= Yii::t('app', "Yangi rangi"); ?></th>
                        <th><?= Yii::t('app', "Panton rang kodi"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($modelRelDocs)):?>
                    <?php foreach ($modelRelDocs as $key=>$modelRelDoc):
                            $item = \app\modules\bichuv\models\ModelRelProduction::findOne(['nastel_no' => $modelRelDoc['nastel_no']]);
                            $order = $item->order;
                            $new_order = $modelRelDoc->order;
                    ?>
                           <tr>
                               <td><?= ($key+1);?></td>
                               <td><?= $item['nastel_no']; ?></td>
                               <td><?= "{$order->musteri['name']} - <b>{$order['doc_number']}</b> ({$order['sum_item_qty']})"; ?></td>
                               <td><?= "{$new_order->musteri['name']} - <b>{$new_order['doc_number']}</b> ({$new_order['sum_item_qty']})"; ?></td>
                               <td><?= $item['modelsList']['article'].'-'.$item['modelsList']['name'];?></td>
                               <td><?= $modelRelDoc['modelList']['article'].'-'.$modelRelDoc['modelList']['name'];?></td>
                               <td><?php
                                   $var = $item['modelVariation'];
                                   if(!empty($var['colorPan'])){
                                       $color = $var['colorPan']['r'].",".$var['colorPan']['g'].",".$var['colorPan']['b'];
                                       echo "<div style='background: rgb(".$color.");width: 100%;height: 40px;border: 1px solid #000;'></div>
                                            <div>{$var['colorPan']['code']}</div>
                                            ";
                                   }
                                   ?>
                               </td>
                               <td><?php
                                   $var = $modelRelDoc['modelVar'];
                                   if(!empty($var['colorPan'])){
                                       $color = $var['colorPan']['r'].",".$var['colorPan']['g'].",".$var['colorPan']['b'];
                                       echo "<div style='background: rgb(".$color.");width: 100%;height: 40px;border: 1px solid #000;'></div><div>{$var['colorPan']['code']}</div>";
                                   }
                                   ?>
                               </td>
                               <td><?php
                                   $var = $modelRelDoc['colorPantone'];
                                   if(!empty($var)){
                                       $color = $var['r'].",".$var['g'].",".$var['b'];
                                       echo $var['name']." ".$var['code'];
                                   }else{
                                       echo $var['name'];
                                   }
                                   ?>
                               </td>
                           </tr>
                    <?php endforeach;?>
                          <tr>
                              <td colspan="9" class="text-bold text-red"><?= $model->change_note; ?></td>
                          </tr>
                    <?php else:?>
                        <tr>
                            <td colspan="9" class="text-bold text-red">
                                <?= Yii::t('app','Model uchun narx tasdiqlanmagan!');?>
                            </td>
                        </tr>
                    <?php endif;?>
                </tbody>
            <?php endif;?>
        </table>

    </div>
</div>
<?php

$css = <<<CSS
body{
 zoom: 0.9;
}
CSS;
$this->registerCss($css);