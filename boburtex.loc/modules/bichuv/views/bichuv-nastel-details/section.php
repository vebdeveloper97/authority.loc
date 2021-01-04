<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this \yii\web\View */
/* @var $nastels array|\yii\db\ActiveRecord[] */
$url = \yii\helpers\Url::to('nastel-detail-info');
$this->title = Yii::t('app', 'Nastel ro\'yxati');
if(!empty($this->context->slug) && !empty($this->context->type)){
    $this->params['breadcrumbs'][] = ['label' => $this->context->_process['name'], 'url' => ['index', 'slug' => $this->context->slug]];
}
if(!empty($this->context->slug) && !empty($this->context->type) && !empty($this->context->table)){
    $this->params['breadcrumbs'][] = ['label' => $this->context->_type['name'], 'url' => ['index', 'slug' => $this->context->slug, 'type' => $this->context->type]];
    $this->params['breadcrumbs'][] = ['label' => $this->context->_table['name'], 'url' => ['index', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $this->context->table]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nastel-details-container">
    <p class="pull-right no-print">
        <?= Html::a(Yii::t('app', 'Orqaga qaytish'), ['index', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $this->context->table], ['class' => 'btn btn-sm btn-success']) ?>
    </p>
    <div class="text-center">
        <input type="text" id="search_nastel" class="form-control" placeholder="<?php echo Yii::t('app','Qidirish')?>">
    </div>
    <div class="nastel-detail-box flex-container">
        <?php foreach ($nastels as $nastel) {?>
            <div class="nastel-items default_button bg-gray-light" default-url="<?=$url?>" data-form-id="<?=$nastel['bgri_id']?>">
                <h3><?=$nastel['nastel_party']?></h3>
                <p> <b><?=$nastel['mato'] ?? ''?></b></p>
                <p><?php echo Yii::t('app','Miqdori(kg)')?> : <b><?=$nastel['rulon_kg']?></b></p>
                <p><?php echo Yii::t('app','Rulon soni')?> : <b><?=$nastel['rulon_count']?></b></p>
            </div>
        <?php }?>
    </div>
</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'bichuv-given-roll-items',
    'crud_name' => 'bichuv-nastel-details',
    'modal_id' => 'bichuv-nastel-details-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Bichuv Nastel Details') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-nastel-details_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
    \yii\bootstrap\Modal::begin([
        'id' => 'beginConfirmModal',
        'header' => Yii::t('app','Siz rostdan ham bu jarayonni boshlamoqchisiz?'),
        'size' => 'modal-lg',
        'options' => [
            'style' => 'background: black;'
        ]
    ]);
?>
    <form action="<?=\yii\helpers\Url::to('process-list')?>" method="post">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <input type="hidden" id="roll_id" name="id">
        <div class="text-center">
            <button type="button" class="saveButton bg-red" data-dismiss="modal" aria-hidden="true">
                <?=Yii::t('app','Bekor qilish')?>
            </button>
            <button type="submit" class="saveButton">
                <?=Yii::t('app','Boshlash')?>
            </button>
        </div>
    </form>
<?php
    \yii\bootstrap\Modal::end()
?>
<?php
$js = <<< JS
    // $('body').delegate('.radio_panel', 'click', function() {
    //     let input = $(this).find('input[type="radio"]');
    //     (!input.is(':checked')) ? input.prop('checked',true) : input.prop('checked',false);
    // });
    $('body').delegate('.makine_checkbox', 'click', function() {
        $('.saveButton').removeAttr('disabled').removeClass('opacity_05');
    });
    $('body').delegate('#saveButton', 'click', function() {
        let id = $(this).attr('data-id');
        $('#roll_id').val(id);
    });
    $('body').delegate("#search_nastel","keyup",function(){
        _this = this;
        let list = [];
        $.each($(".nastel-items"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list.push($(this).data('form-id'));
        });
        if($(".nastel-items:visible").length<1){
            $.ajax({
                url: '',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: $(_this).val(),
                    list: list
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.list;
                    dataList.map(function(key) {
                          li += '<div class="nastel-items default_button bg-gray-light" default-url="{$url}" data-form-id="'+key['bgri_id']+'">'+
                                    '<h3>'+key['nastel_party']+' </h3>'+
                                    '<p> <b>'+key['mato']+'</b></p>'+
                                    '<p>Miqdori(kg) : <b>'+key['rulon_kg']+'</b></p>'+
                                    '<p>Rulon soni : <b>'+key['rulon_count']+'</b></p>'+
                                '</div>';
                    });
                    $('.nastel-detail-box').append(li);
                }
            });
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
#search_nastel{
    width: 50%;
    margin: 0 auto;
    height: 30px;
    border: 1px solid;
    background: #C5CAE9;
    color: #000;
    font-weight: bold;
    font-size: 24px;
    text-align: center;
}
.flex-container{
    display: flex;
    flex-direction: row; 
    flex-wrap: wrap; 
    align-content: center; 
    justify-content: center;
}
.nastel-items{
    margin-right: 10px;
    width: 180px;
    height: 120px;
    display: flex;
    flex-direction: column; 
    flex-wrap: wrap; 
    align-content: center; 
    justify-content: center;
}
.nastel-items p{
    margin-bottom: 0;
}
.opacity_05{
    opacity: 0.5;
}
.radio_panel{
    cursor: pointer;
}
.modal-header{
    font-size: 35px;
    text-align: center;
}
.nastel-items{
    border: 1px solid black;
    cursor: pointer;
    margin-top: 10px;
    text-align:center;
}
.saveButton {
   background: #6977EB;
   background-image: -webkit-linear-gradient(top, #6977EB, #0E4A80);
   background-image: -moz-linear-gradient(top, #6977EB, #0E4A80);
   background-image: -ms-linear-gradient(top, #6977EB, #0E4A80);
   background-image: -o-linear-gradient(top, #6977EB, #0E4A80);
   background-image: linear-gradient(to bottom, #6977EB, #0E4A80);
   -webkit-border-radius: 20px;
   -moz-border-radius: 20px;
   border-radius: 20px;
   height: 65px;
   line-height: 26px;
   color: #FFFFFF;
   font-family: Verdana;
   width: 500px;
   font-size: 26px;
   font-weight: 600;
   padding: 3px;
   box-shadow: inset -3px 4px 90px 19px #2F3A6F;
   -webkit-box-shadow: inset -3px 4px 90px 19px #2F3A6F;
   -moz-box-shadow: inset -3px 4px 90px 19px #2F3A6F;
   text-shadow: 1px 1px 20px #000000;
   border: solid #337FED 1px;
   text-decoration: none;
   display: inline-block;
   cursor: pointer;
}
.bg-red{
    background: red;
   background-image: -webkit-linear-gradient(top, #FF1744, #F50057);
   background-image: -moz-linear-gradient(top, #FF1744, #F50057);
   background-image: -ms-linear-gradient(top, #FF1744, #F50057);
   background-image: -o-linear-gradient(top, #FF1744, #F50057);
   background-image: linear-gradient(to bottom, #FF1744, #F50057);
   box-shadow: inset -3px 4px 90px 19px #9B0000;
   -webkit-box-shadow: inset -3px 4px 90px 19px #9B0000;
   -moz-box-shadow: inset -3px 4px 90px 19px #9B0000;
}
.saveButton:hover {
   border: solid #337FED 1px;
   background: #0F4CAF;
   background-image: -webkit-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: -moz-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: -ms-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: -o-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: linear-gradient(to bottom, #0F4CAF, #3D94F6);
   -webkit-border-radius: 19px;
   -moz-border-radius: 19px;
   border-radius: 19px;
   text-decoration: none;
}
@keyframes click-wave {
  0% {
    height: 40px;
    width: 40px;
    opacity: 0.35;
    position: relative;
  }
  100% {
    height: 200px;
    width: 200px;
    margin-left: -80px;
    margin-top: -80px;
    opacity: 0;
  }
}

.option-input {
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  -o-appearance: none;
  appearance: none;
  position: relative;
  top: 1px;
  right: 0;
  bottom: 0;
  left: -2px;;
  height: 40px;
  width: 40px;
  transition: all 0.15s ease-out 0s;
  background: #cbd1d8;
  border: none;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  margin-right: 0.5rem;
  outline: none;
  z-index: 1000;
}
.option-input:hover {
  background: #9faab7;
}
.option-input:checked {
  background: #40e0d0;
}
.option-input:checked::before {
  height: 40px;
  width: 40px;
  position: absolute;
  content: '✔';
  display: inline-block;
  font-size: 26.66667px;
  text-align: center;
  line-height: 40px;
}
.option-input:checked::after {
  -webkit-animation: click-wave 0.65s;
  -moz-animation: click-wave 0.65s;
  animation: click-wave 0.65s;
  background: #40e0d0;
  content: '';
  display: block;
  position: relative;
  z-index: 100;
}
.option-input.radio {
  border-radius: 50%;
}
.option-input.radio::after {
  border-radius: 50%;
}
.radio_div label {
  display: flex; 
  float: left; 
  margin-right: 10px; 
  align-content: center; 
  align-items: center; 
  font-size: 25px; 
  justify-content: center;
}

CSS;
$this->registerCss($css);