<?php

use app\modules\mechanical\models\SpareInspection;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\PermissionHelper as P;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\mechanical\models\search\SpareInspectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title=Yii::t('app', 'Machine control');
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="form">
            <input type="text" class="searchTerm search-mashine" placeholder="<?=Yii::t('app','Search')?>">
        </div>
    </div>
</div>

<div class="spare-inspection-index">
    <div class='wrapper'>
        <?php if (!empty($spareList)):?>
            <?php foreach ($spareList as $item):?>
                <label class="spare-mashine" data-id="<?=$item['id']?>">
                    <div>
                        <div class='circle'></div>
                        <span><?=$item['name']?></span>
                    </div>
                </label>
            <?php endforeach;?>
        <?php endif;?>
    </div>
</div>

<?php $this->registerCss("
 body .spare-inspection-index .wrapper {
	 background: white;
	 width: 100%;
	 padding: 10px 0px;
	 margin: 0 auto;
	 overflow: hidden;
	 text-align: center;
	 position: relative;
}
 body .spare-inspection-index .wrapper label {
//	 width: 200px;
	 height: 200px;
	 background: #4CCADB;
	 display: inline-block;
	 border-radius: 2px;
	 margin: 10px;
	 cursor: pointer;
}
 body .spare-inspection-index .wrapper label:hover{
    background: #00B4CC;
    -webkit-box-shadow: 0px 0px 33px -12px rgba(0,0,0,0.69);
    -moz-box-shadow: 0px 0px 33px -12px rgba(0,0,0,0.69);
    box-shadow: 0px 0px 33px -12px rgba(0,0,0,0.69);
 }
 body .spare-inspection-index .wrapper label span {
	 color: white;
	 font-size: 13px;
	 position: relative;
	 top: 10px;
	 padding: 0 12px;
}
 body .spare-inspection-index .wrapper label p {
	 margin: 25px;
	 color: white;
	 display: none;
	 position: absolute;
	 bottom: 10px;
	 left: 0;
	 right: 0;
	 width: 150px;
	 margin: 0 auto;
	 top: 25px;
	 font-size: 10px;
}
 body .spare-inspection-index .wrapper label a {
	 display: none;
	 color: white;
	 position: absolute;
	 bottom: 10px;
	 left: 0;
	 font-size: 10px;
	 right: 0;
	 margin: 0 auto;
}
 body .spare-inspection-index .wrapper .circle {
	 visibility: visible;
	 width: 40px;
	 height: 40px;
	 border: 3px solid white;
	 border-radius: 100%;
	 opacity: 0.3;
	 margin: 60px auto 0px auto;
	 -webkit-animation: circle 1s infinite;
	/* Chrome, Safari, Opera */
	 animation: circle 1s infinite;
}
    
/* Animations */
 @-webkit-keyframes circle {
	 0% {
		 transform: scale(1);
	}
	 50% {
		 transform: scale(1.1);
	}
	 100% {
		 transform: scale(1);
	}
}

.search {
  width: 100%;
  position: relative;
  display: flex;
}

.searchTerm {
  width: 100%;
  border: 3px solid #00B4CC;
  padding: 15px;
  height: 20px;
  border-radius: 5px;
  outline: none;
  color: #9DBFAF;
}

.searchTerm:focus{
  color: #00B4CC;
}
");?>

<?php $url =  Url::to(['search-mashine','slug' => $this->context->slug])?>
<?php
Modal::begin([
    'size' => Modal::SIZE_LARGE,
    'id' => 'mashine-modal'
]);?>
    <h1 id="modal-content"></h1>
<?php
Modal::end();
?>


<?php
$js = <<< JS
    const __body = $('body');
    
    __body.addClass('sidebar-collapse');

    /** Qidiruv mashina **/
    $('body').delegate(".search-mashine","keyup",function(){
        _this = this;
        $.each($(".spare-mashine"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }               
        });
    });
    
    /** History mashine **/
    __body.delegate('.spare-mashine','click',function(e) {
          let id = $(this).data('id');
          e.preventDefault();
          $('#mashine-modal').modal('show').find('#modal-content').load('history', { id: id });
    })
    
JS;
?>
<?php $this->registerJs($js, \yii\web\View::POS_READY)?>
