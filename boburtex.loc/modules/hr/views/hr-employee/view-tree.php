<?php

use app\assets\AppAsset;
use app\components\CustomTreeImage\CustomTreeImage as TreeImage;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this \yii\web\View */
/* @var $dept mixed */
/* @var $employees mixed */
/* @var $model \app\modules\hr\models\HrEmployeeForm */
?>
<div class="row setting-box no-print">
    <div class="button-toggle">
        <span class="fa fa-plus"></span>
    </div>
    <div class="setting-box-in">
        <?php $url = \yii\helpers\Url::to(['get-employee-via-ajax']) ?>
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'employee_id')
                ->widget(Select2::className(), [
                    'data' => [],
                    'options' => [
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => $url,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) {
                                    return { q:params.term }; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(data) {
                                    return "<div class=\'list-group-item\' data-id="+data.id+">"+data.text+"</div>"; 
                            }'),
                        'templateSelection' => new JsExpression('function (data) { 
                                return  data.text; 
                            }'),
                    ],
                    'pluginEvents' => [
                        'select2:open' => new JsExpression("function(e){
                                var el = document.getElementById('select2-hremployeeform-employee_id-results');
                                     new Sortable(el, {
                                             group: {
                                                    name: 'shared',
                                                    put: false 
                                                },
                                                animation: 150,
                                                sort: false,
                                                onMove: function (evt) {
                                                    if (evt.to.childElementCount > 0) {
                                                        return false;
                                                    }
                                                }
                                                
                                        });
                                        
                            }")
                    ]
                ])->label(Yii::t('app', 'Hodim FIO')); ?>
            <?php ActiveForm::end() ?>
        </div>
        <div class="drop-box col-md-4">
            <div id="dropZone" style="z-index: 10022;">
            </div>
            <span class="fa fa-trash-o text-red fa-3x"></span>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= TreeImage::widget([
            'query' => $dept,
            'root' => Yii::t('app', 'Organizational structure'),
            'icon' => 'user',
            'iconRoot' => 'building',
        ]);
        ?>
    </div>
</div>
<?php
$confirm = Yii::t('app',"Siz rostdan bu elementni o\'chirmoqchimisiz?");
$this->registerJsFile('/js/Sortable.min.js', ['depends' => AppAsset::className()]);
$this->registerJs("
let two = $('body').find('.tree-each-node');
if(two){
    two.map(function(key, item){
        let id = $(item).data('id');
        var obj = document.getElementById('treeEachNode-'+id);
        new Sortable(obj, {
            group: 'shared',
            animation: 150,
            onAdd: function (evt) {
                	        
	        },
	        onMove: function (evt) {
                if (evt.to.childElementCount > 0) {
                    return false;
                }
            }
        });    
    })
}
    let els = document.getElementById('dropZone');
    new Sortable(els, {
          group: 'shared', 
          onAdd: function (evt) {
            let k = confirm('$confirm');
            if(k){
                let item = evt.item;
                item.parentNode.removeChild(item);
            }
          }
    });
$('.button-toggle').on('click', function(e){
    let fa = $(this).find('.fa');
    if(fa.hasClass('fa-plus')){
        fa.removeClass('fa-plus');
        fa.addClass('fa-minus');
    }else{
        fa.removeClass('fa-minus');
        fa.addClass('fa-plus');
    }
    $('.setting-box-in').toggleClass('fade'); 
});
");
$this->registerCss("
.list-group-item{
    color:red;
}
a.tree-each-node > div{
    border:1px dashed #ccc;
    width:100%;
    height:25px; 
}
#dropZone{
    height: 50px;
    border: 2px dashed red;
    text-align: center;
    width: 260px;
}
.setting-box {
    position:fixed;
    z-index:1001;
    width:300px;
    text-align:center;
    margin:auto 0;
    border:2px solid #ccc;
    padding:5px;
    right:5%;
}
.button-toggle {
    float:left;
    border:2px solid #03A9F4;
    padding:2px 7px;
    cursor:pointer;
}
.button-toggle:hover{
    border:2px solid #03A9F4;
    background:rgb(168, 215, 236);
}
.drop-box span.fa{
    position: absolute;
    top: 4px;
    left: 45px;
}
");

?>

