<?php

use app\modules\toquv\models\ToquvRawMaterials;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvAksModelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Aks Models');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-aks-model-index">
    <?php if (Yii::$app->user->can('toquv-aks-model/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'toquv-aks-model_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'trm.name',
                'label' => Yii::t('app', 'Turi'),
                'format' => 'raw',
            ],
            'name',
            'code',
            [
                'attribute' => 'image',
                'value' => function($model){
                    $image = ($model->image)?"<img src='/web/".$model->image."' class='thumbnail imgPreview round' style='width:80px;border-radius: 100px;height:80px;'> ":'';
                    return $image;
                },
                'format' => 'html'
            ],
            'width',
            'height',
            'qavat',
            [
                'attribute' => 'rawMaterialConsist',
                'format' => 'raw',
            ],
            [
                'attribute' => 'rawMaterialIp',
                'format' => 'raw',
            ],
            //'palasa',
            //'price',
            //'pb_id',
            //'musteri_id',
            //'color_pantone_id',
            //'color_boyoq_id',
            //'raw_material_type',
            //'color_type',
            //'status',
            //'created_by',
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('toquv-aks-model/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('toquv-aks-model/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('toquv-aks-model/delete'); // && $model->status !== $model::STATUS_SAVED;
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
    'model' => 'toquv-aks-model',
    'crud_name' => 'toquv-aks-model',
    'modal_id' => 'toquv-aks-model-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Toquv Aksessuar Model') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'toquv-aks-model_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
$js = <<< JS
    $('body').delegate('.clone', 'click', function(e){
        let row = $(this).parents('tr');
        let indeks = 1*row.attr('data-row-index');
        let ne = row.find('.toquv_ne_id');
        let thread = row.find('.toquv_thread_id');
        let color_pantone_id = row.find('.color_pantone_id');
        let percentage = row.find('.aks_percentage');
        let new_percentage = (1*percentage.attr('data-percentage'))/((1*$('.'+percentage.attr('data-class')).length)+1);
        percentage.val(new_percentage);
        $('#documentitems_id').multipleInput('add');
        let lastObj = $('#documentitems_id table tbody tr:last');
        lastObj.find('.toquv_ne_id').html(ne.html()).val(ne.val()).trigger('change');
        lastObj.find('.toquv_thread_id').html(thread.html()).val(thread.val()).trigger('change');
        lastObj.find('.aks_ip_name').val($('#toquvaksmodelitem-'+indeks+'-name').val());
        lastObj.find('.toquv_ip_color_id').val($('#toquvaksmodelitem-'+indeks+'-toquv_ip_color_id').val()).trigger('change');
        lastObj.find('.aks_parent_percentage').val($('#toquvaksmodelitem-'+indeks+'-parent_percentage').val()).trigger('change');
        lastObj.find('.aks_ip_id').val($('#toquvaksmodelitem-'+indeks+'-ip_id').val()).trigger('change');
        lastObj.find('.color_pantone_id').html(color_pantone_id.html()).val(color_pantone_id.val()).trigger('change');
        lastObj.find('.aks_percentage').attr('data-percentage',percentage.attr('data-percentage')).attr('data-class',percentage.attr('data-class')).addClass(percentage.attr('data-class'));
        $('.'+percentage.attr('data-class')).val(new_percentage);
        /*lastObj.find('.aks_height').val($('#toquvaksmodelitem-'+indeks+'-height').val());
        lastObj.find('.aks_height_sm').val($('#toquvaksmodelitem-'+indeks+'-height_sm').val());*/
    });
    $('body').delegate('#documentitems_id','afterDeleteRow', function (e, row, index) {
        console.log(row);
    });
    $("body").delegate("input#fileImageAks", "change", function(){
        let ext = this.value.split('.').pop();
        if(ext=='jpeg'||ext=='jpg'||ext=='gif'||ext=='bmp'||ext=='png'){
            var a = $(this).parent();
            var b = a.parent();
            if (this.files[0]) {
                    var fr = new FileReader();
                fr.addEventListener("load", function () {
                    a.css("background-image","url(" + fr.result + ")");
                    $('#textImageAks').removeAttr('disabled');
                    $('#textImageAks').val(fr.result);
                }, false);
                fr.readAsDataURL(this.files[0]);
            }
        }else{
            alert('Siz rasm tanlamadingiz');
        }
    });
    $("body").mouseover(function(){
        $('.infoError').remove();
    });
    $('body').delegate('.aks_height', 'keyup change', function(e){
        let sum = 0;
        let t = $(this);
        let num = 1*t.val();
        let top = t.offset().top;
        let left = t.offset().left+100;
        let height_sm = t.parents('tr').find('.aks_height_sm');
        let height = $('#toquvaksmodel-height');
        $('.infoError').remove();
        $('.aks_height').each(function () {
            if($(this).val() !== ''){
                sum += 1*$(this).val();
            }
        });
        if(sum  > 100){
            e.preventDefault();
            $(this).val(100-(sum-num));
            $("body").append('<span class="infoError" style="top: '+top+'px;left: '+left+'px;">Must be less than 100!<br></span>');
        }
        let summa = ($(this).val()*height.val())/100;
        height_sm.val(parseFloat(summa));
    });
    $('body').delegate('.aks_height_sm', 'keyup change', function(e){
        let sum = 0;
        let t = $(this);
        let num = 1*t.val();
        let top = t.offset().top;
        let left = t.offset().left+100;
        let height_foiz = t.parents('tr').find('.aks_height');
        let height = 1*$('#toquvaksmodel-height').val();
        $('.infoError').remove();
        $('.aks_height_sm').each(function () {
            if($(this).val() !== ''){
                sum += 1*$(this).val();
            }
        });
        if(sum  > height){
            e.preventDefault();
            $(this).val(height-(sum-num));
            $("body").append('<span class="infoError" style="top: '+top+'px;left: '+left+'px;">Must be less than '+height+' !<br></span>');
        }
        let summa = ($(this).val()*100)/height;
        height_foiz.val(parseFloat(summa));
    });
    $('body').delegate('#toquvaksmodel-height', 'keyup change', function(e){
        let height = $(this).val();
        $('.aks_height').each(function (index,value) {
            if($(this).val() !== ''){
                let height_sm = $(this).parents('tr').find('.aks_height_sm');
                let summa = ($(this).val()*height)/100;
                height_sm.val(parseFloat(summa));
            }
        });
    });
    $('body').delegate('.aks_percentage', 'keyup change', function(e){
        let sum = 0;
        let t = $(this);
        let num = 1*t.val();
        let top = t.offset().top;
        let left = t.offset().left+100;
        $('.infoError').remove();
        $('.aks_percentage').each(function () {
            if($(this).val() !== ''){
                sum += 1*$(this).val();
            }
        });
        if(sum  > 100){
            e.preventDefault();
            $(this).val(100-(sum-num));
            $("body").append('<span class="infoError" style="top: '+top+'px;left: '+left+'px;">Must be less than 100!<br></span>');
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);