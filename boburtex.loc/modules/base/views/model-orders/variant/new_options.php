<?php

use app\modules\base\models\ModelOrdersItemsSearch;
use yii\grid\GridView;
use app\modules\base\models\ModelOrdersVariations;
use yii\helpers\Html;


/* @var $moiSearchModel ModelOrdersItemsSearch */
/* @var $moiDataProvider \yii\data\ActiveDataProvider */
/** @var $model \app\modules\base\models\ModelOrders */
/** @var $this \yii\web\View */
/* @var $variant_id ModelOrdersVariations */
/* @var $id \app\modules\base\models\ModelOrdersItems */
/* @var $new_variations ModelOrdersVariations  */
$materials = ModelOrdersVariations::getVariantMaterials($model->id, $new_variations['status'], $variant_id, $model->status);
$acs = ModelOrdersVariations::getVariationAcs($model->id,$new_variations['status'], $variant_id, $model->status);
?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $moiSearchModel->search(Yii::$app->request->queryParams,$id, $variant_id),
            'filterRowOptions' => ['style' => 'display: none'],
            'options' => ['style' => 'font-size:11px;'],
            'filterModel' => false,
            'rowOptions' => function($model){
                return ['style'=> ($model->status!=2)?'':'background:#EF5350'];
            },
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['style' => 'width:25px;'],
                ],
                [
                    'attribute' => 'id',
                    'value' => function($model) {
                        return 'SM-'.$model->id;
                    }
                ],
                [
                    'attribute' => 'photo',
                    'label' => Yii::t('app', "Rasmlar"),
                    'value' => function($model) {
                        $image = ($model->modelsList->image)
                            ?"<img alt='".$model->modelsList->article."' src='/web/".$model->modelsList->image."' class='thumbnail imgPreview round' style='width:80px;height:80px;'> "
                            :'';
                        return $image;
                    },
                    'options' => ['width' => '90px'],
                    'format' => 'html'
                ],
                [
                    'attribute' => 'models_list_id',
                    'value' => function($model){
                        return $model->modelsList->name. " (".$model->modelsList->article .")";
                    },
                ],
                [
                    'attribute' => 'model_var_id',
                    'value' => function($model){
                        return  $model->modelVar->name .  $model->modelVar->color;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'price',
                    'value' => function($model){
                        return  number_format($model->price,2, '.', '')." ".$model->pb->name;
                    },
                    'format' => 'html'
                ],
                'add_info:ntext',
                [
                    'attribute' => 'load_date',
                    'format' =>  ['date', 'php:d.m.Y'],
                    'options' => ['width' => '80px']
                ],

                [
                    'attribute' => 'model_var_info',
                ],
                'models_list_info',
                [
                    'attribute' => 'size',
                    'label' => Yii::t('app','Size'),
                    'value' => function($model){
                        return $model->sizeList;
                    },
                    'format' => 'html',
                    'options' => ['width' => '100px']
                ],
            ],
        ]); ?>
        <div id="order-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3><?php echo Yii::t('app','Buyurtma')?></h3>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered">

        <thead>
        <th>#</th>
        <th><?=Yii::t('app', 'Material')?></th>
        <th><?=Yii::t('app', 'En/gramaj')?></th>
        <th><?=Yii::t('app', 'Color')?></th>
        <th><?=Yii::t('app', 'Desen No')?> / <?= Yii::t('app', 'Baski type') ?></th>
        <th><?=Yii::t('app', 'Add Info')?></th>
        </thead>
        <tbody>
        <?php
        $cnt = 1;
        ?>
        <?php foreach ($materials as $item): ?>
            <tr>
                <td>
                    <?=$cnt++?>
                </td>
                <td>
                    <?= $item['rname']
                    . ' - ' . $item['ne']
                    . ' - ' . $item['thread']
                    . ' - ' . $item['pus_fine']?>
                </td>
                <td>
                    <?=$item['en'] . 'sm | ' . $item['gramaj'] . ' gr/m<sup>2</sup>'?>
                </td>
                <td>
                    <?= $item['cp_code']
                        ? $item['cp_name'] . ' (' . $item['cp_code'] . ') '
                        : $item['wc_name'] . ' (' . $item['wc_code'] . ') '?>
                </td>
                <td>
                    <?= $item['desen_name'] . ' (' . $item['desen_code'] . ')' . ' / ' . $item['baski_name'] ?>
                </td>
                <td>
                    <?= Html::encode($item['material_info']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <table class="table table-bordered">

        <thead>
        <th>#</th>
        <th><?=Yii::t('app', 'Artikul / Kodi')?></th>
        <th><?=Yii::t('app', 'Bichuv Acs')?></th>
        <th><?=Yii::t('app', 'Properties')?></th>
        <th><?=Yii::t('app', 'Quantity')?> </th>
        <th><?=Yii::t('app', 'Add Info')?> </th>
        </thead>
        <tbody>
        <?php
        $cnt = 1;
        ?>
        <?php foreach ($acs as $item): ?>
            <tr>
                <td>
                    <?=$cnt++?>
                </td>
                <td>
                    <?= $item['artikul'] ?>
                </td>
                <td>
                    <?=$item['acs_name']?>
                </td>
                <td>
                    <?= $item['acs_property_name'] ? $item['acs_property_name'] . ': ' . $item['acs_property_value'] : ''?>
                </td>
                <td>
                    <?= $item['order_acs_qty'] . ' (' . $item['unit_name'] . ')' ?>
                </td>
                <td>
                    <?= $item['order_acs_info'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php
$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .print_div,.stone_div{
        width: 70px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        position: relative;
        margin-bottom: 3px;
    }
    .list_prints,.list_stone{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
    .check_button{
        position: absolute;
        bottom: -18px;
        left: 30%;
    }
CSS;
$this->registerCss($css);
$js = <<< JS
    $('body').delegate('.add-order', 'click', function(e){
        e.preventDefault();
        $('#order-modal').modal('show');
        $('#order-modal').find('.modal-body').load($(this).attr('href'));
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);