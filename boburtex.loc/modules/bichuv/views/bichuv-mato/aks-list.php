<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 02.05.20 0:08
 */


use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\bichuv\models\BichuvDoc;
use yii\helpers\Html;use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models \app\modules\bichuv\models\BichuvMatoOrderItems[] */

$tayyor_emas = Yii::t('app', 'Tayyor emas');
$tayyor = Yii::t('app', 'Tayyor');
$responsible_text = ($responsible)?"Javobgar shaxs o'zgartirish":"Javobgar shaxs tayinlash";
?>
<div>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $models,
        'theme' => 'bs',
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 100,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success hidden',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'type' => 'hiddenInput',
                'name' => 'id',
            ],
            [
                'name' => 'name',
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'class' => 'name',
                    'disabled' => true
                ],
                'headerOptions' => [
                    'style' => 'width: 40%;',
                ]
            ],
            [
                'name' => 'count',
                'title' => Yii::t('app', 'Berilishi kerak(dona)'),
                'options' => [
                    'class' => 'document_qty',
                    'disabled' => true
                ],
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Berilishi kerak (kg)'),
                'options' => [
                    'class' => 'tabular-cell-mato roll-fact number',
                    'disabled' => true
                ],
            ],
            [
                'name' => 'status',
                'title' => Yii::t('app', 'Holati'),
                'type' => 'checkbox',
                'value' => function($model){
                    if($model->status==1){
                        return 0;
                    }else{
                        return 1;
                    }
                },
                'options' => function($model) use($tayyor,$tayyor_emas){
                    if($model->status==1){
                        return [
                            'label' =>  '<div class="checkbox__text">'.$tayyor_emas.'</div>',
                            'class' => 'tabular-cell-status checkbox_input',
                            'disabled' => true
                        ];
                    }else{
                        return [
                            'label' =>  '<div class="checkbox__text">'.$tayyor.'</div>',
                            'class' => 'tabular-cell-status',
                            'disabled' => true
                        ];
                    }
                },
            ],
        ]
    ]);
    ?>
    <?php ActiveForm::end();
    if(empty($responsible)||$responsible['type']==1) {?>
        <?= Html::a(Yii::t('app', $responsible_text), ["save-responsible", 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-success']);
    }?>
</div>
<?php
$css = <<< CSS
.checkbox > label input {
	position: absolute;
	z-index: -1;
	opacity: 0;
	margin: 10px 0 0 20px;
}
.checkbox__text {
	position: relative;
	padding: 0 0 0 60px;
	cursor: pointer;
}
.checkbox__text:before {
	content: '';
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 26px;
	border-radius: 13px;
	background: #CDD1DA;
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2);
	transition: .2s;
}
.checkbox__text:after {
	content: '';
	position: absolute;
	top: -2px;
	left: 2px;
	width: 22px;
	height: 22px;
	border-radius: 10px;
	background: #FFF;
	box-shadow: 0 2px 5px rgba(0,0,0,.3);
	transition: .2s;
}
.checkbox > label input:checked + .checkbox__text:before {
	background: #9FD468;
}
.checkbox > label input:checked + .checkbox__text:after {
	left: 26px;
}
.checkbox > label input:focus + .checkbox__text:before {
	box-shadow: inset 0 2px 3px rgba(0,0,0,.2), 0 0 0 3px rgba(255,255,0,.7);
}
.list-cell__button{
    display: none;
}
CSS;
$this->registerCss($css);
