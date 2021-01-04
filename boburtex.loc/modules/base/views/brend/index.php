<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\BrendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Brends');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brend-index">
    <?php if (Yii::$app->user->can('brend/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'brend_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'full_name',
            'code',
            [
                'attribute'  => 'image',
                'value' => function($model){
                    return ($model->image)?"<img src='{$model->image}' style='max-width: 100px;max-height: 100px;' class='imgPreview'>":"";
                },
                'format' => 'raw'
            ],
            //'token',
            //'status',
            //'created_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('brend/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('brend/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('brend/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-primary view-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'brend',
    'crud_name' => 'brend',
    'modal_id' => 'brend-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Brend') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'brend_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>

<?php
$this->registerJsFile('/js/image-preview.js',['depends'=>AppAsset::className()]);
$js = <<< JS
$("body").delegate("input.upload-image", "change", function(){
    let ext = this.value.split('.').pop();
    if(ext=='jpeg'||ext=='jpg'||ext=='gif'||ext=='bmp'||ext=='png'){
        var a = $(this).parent();
        var b = a.parent();
        if (this.files[0]) {
                var fr = new FileReader();
    
            fr.addEventListener("load", function () {
                a.css("background-image","url(" + fr.result + ")");
                $('#textImage').val(fr.result);
                $('#remove').attr('name','remove');
            }, false);
            fr.readAsDataURL(this.files[0]);
        }
    }else{
        alert('Siz rasm tanlamadingiz');
    }
});
JS;
$this->registerJs($js,View::POS_READY);

