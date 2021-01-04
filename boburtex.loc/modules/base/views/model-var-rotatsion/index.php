<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelVarRotatsionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Model Var Rotatsion');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-var-rotatsion-index">
    <?php if (!Yii::$app->user->can('model-var-rotatsion/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'model-var-rotatsion_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'name',
            'add_info:ntext',
            [
                  'attribute' => 'attachments',
                  'value' => function($model){
                        $img = '';
                        foreach ($model->modelVarRotatsionRelAttaches as $image){
                            if($image->attachment["path"]){
                                $img .= '<img class="imgPreview img-thumbnail" src="/web/'.$image->attachment["path"].'" style="width: 40px;height: 5vh;">';
                            }
                        }
                        return '
                            <div class="multiple-input-list__item">
                                <div class="field-modelvar-attachments form-group">'.
                                    $img.'
                                </div>
                            </div>';
                  },
                  'format' => 'raw'
            ],
            //'status',
            //'created_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('model-var-rotatsion/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('model-var-rotatsion/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('model-var-rotatsion/delete'); // && $model->status !== $model::STATUS_SAVED;
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
    'model' => 'model-var-rotatsion',
    'crud_name' => 'model-var-rotatsion',
    'modal_id' => 'model-var-rotatsion-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Model Var Rotatsion') . '</h3>',
    'active_from_class' => 'customAjaxFormRotatsion',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'model-var-rotatsion_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>

<?php
$imageUrl = Yii::$app->urlManager->createUrl(['base/models-variations/attachment-upload']);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
$("body").delegate(".addAttach","click",function(){
    let t = $(this);
    let num = 1*t.attr("num");
    t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hidden"></span></label>');
    t.attr("num",num+1);
});
$("body").delegate("input.uploadImage", "change", function(){
    let a = $(this).parent();
    let b = a.parent();
    if (this.files[0]) {
        let fr = new FileReader();
        let fd = new FormData;
        let input = $(this);
        let fon = "";
        fd.append('img', input.prop('files')[0]);
        fr.addEventListener("load", function () {
           fon = fr.result;
        }, false);
        fr.readAsDataURL(this.files[0]);
        a.css("background-image","url(/img/loading_my.gif)");
        $.ajax({
            url: '{$imageUrl}',
            data: fd,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                if(data.status == 1){
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hidden");
                    s.html("<input type='hidden' name='attachments[]' value='"+data.id+"'>");
                }
            },
            error: function(error){
                alert(error.responseText);
            }
        });
        //b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
    }
});
$(document).on('click', ".udalit", function(e){
    e.preventDefault();
    //$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
    $(this).parent().remove();
});
JS;
$this->registerJs($js,View::POS_READY);

