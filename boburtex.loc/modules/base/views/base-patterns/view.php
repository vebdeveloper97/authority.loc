<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatterns */
/* @var $searchModel \app\modules\base\models\BasePatternsSearch */
/* @var $modelItems app\modules\base\models\BasePatternItems */
/* @var $variantCount \app\modules\base\models\BasePatternsVariations */
/* @var $modelVar \app\modules\base\models\ModelOrdersVariations */

$this->title = "{$model->code} {$model->name}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Patterns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pull-right">
    <?= Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info', 'style' => 'padding: 8px']) ?>
</div>
<?= Collapse::widget([
      'items' => [
          [
              'label' => Yii::t('app',"Qolip ma'lumotlari"),
              'content' => $this->render('_pattern', ['model' => $model]),
         ],
      ],
])?>
<?php if($model->status != $model::STATUS_SAVED):?>
<div class="base-pattern-items-form">
    <?= $this->render('_form_item', ['model' => $modelItems, 'id' => $model->id]) ?>
</div>
<?php endif;?>
<h4><?= Yii::t('app', "Qolip andoza detal ro'yxatlari"); ?>:</h4>
<div class="base-pattern-items-box">
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <?php foreach ($variantCount as $k => $v): ?>
                <?php if($k == 0): ?>
                    <li role="presentation" class="active"><a href="#home1<?=$k?>" role="tab" data-toggle="tab" aria-controls="home1"><?=Yii::t('app', 'Variant '.$v['variant_no'])?> </a></li>
                <?php else: ?>
                    <li  role="presentation"><a href="#home1<?=$k?>" role="tab" data-toggle="tab" aria-controls="home1"><?=Yii::t('app', 'Variant '.$v['variant_no'])?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if(
                        $model->status == \app\modules\base\models\BasePatterns::STATUS_INACTIVE
                    ): ?>
                <li><a role="tab" href="<?=\yii\helpers\Url::to(['new-variant', 'id' => $model->id])?>"><?="<span class='fa fa-plus-circle btn btn-success btn-sm'></span>"?></a></li>
            <?php endif; ?>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php foreach ($variantCount as $k => $v): ?>
                <?php if($k == 0): ?>
                    <div role="tabpanel" class="tab-pane active" id="home1<?=$k?>">
                        <?php
                            echo $this->render('_variant', [
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'modelItems' => $modelItems,
                                'var_id' => $v['id']
                            ]);
                        ?>
                    </div>
                <?php else: ?>
                    <div role="tabpanel" class="tab-pane" id="home1<?=$k?>">
                        <?php
                           echo $this->render('_variant', [
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'modelItems' => $modelItems,
                                'var_id' => $v['id']
                            ]);
                        ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php
    Modal::begin([
        'header' => '<h4>'.Yii::t('app','Yaratilgan qolipni buyurtmaga biriktirish').'</h4>',
        'size' => 'modal-md',
        'options' => [
            'id' => 'orders_patterns'
        ]
    ]);
        ?>
    <div class="container-modal">
        <div class="row">
            <div class="col-sm-12">
                <?php

                    $f = \yii\widgets\ActiveForm::begin([
                        'action' => ['orders-pattern', 'id' => $model->id]
                    ]);
                        echo $f->field($modelVar, 'model_orders_id')->widget(\kartik\select2\Select2::class,[
                           'data' => $model->getSuccessOrders($model->id)
                        ]);
                        echo $f->field($modelVar, 'base_patterns_id')->widget(\kartik\select2\Select2::class,[
                           'data' => $model->getPatterns($model->id)
                        ]);
                        echo Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']);
                    \yii\widgets\ActiveForm::end();
                ?>
            </div>
        </div>
    </div>
<?php
    Modal::end();
?>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'base-pattern-items',
    'crud_name' => 'base-pattern-items',
    'modal_id' => 'base-pattern-items-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Qolip andoza detal') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'base-pattern-items_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
</div>
<?php
$this->registerCss("
.base-pattern-items-form {
    padding:25px;
    border:1px solid #2196F3;
    margin-bottom:25px;
}
.base-pattern-items-box {
    border:1px solid #03A9F4;
    padding:25px 5px; 
}");
?>
