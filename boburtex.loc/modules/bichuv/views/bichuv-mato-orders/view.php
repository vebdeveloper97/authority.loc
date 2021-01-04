<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\bichuv\models\BichuvDocResponsible;
use app\modules\bichuv\models\BichuvMatoOrders;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */
/* @var $responsible app\modules\bichuv\models\BichuvDocResponsible */
/* @var $is_view boolean */

if($is_view){
    $this->title = ($model->moi)?strip_tags($model->moi->info):$model->doc_number;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato buyurtma'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
\yii\web\YiiAsset::register($this);
$tayyor_emas = Yii::t('app', 'Tayyor emas');
$tayyor = Yii::t('app', 'Tayyor');
?>
<div class="bichuv-mato-orders-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('bichuv-mato-orders/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', "Saqlash va jo'natish"), ["save-and-finish", 'id' => $model->id],
                    ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('bichuv-mato-orders/delete')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($is_view):?>
            <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
        <?php endif;?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'doc_number',
            'reg_date',
            [
                'attribute' => 'info',
                'label' => Yii::t('app', 'Buyurtma'),
                'value' => function($model){
                    return ($model->moi)?$model->moi->info:'';
                },
                'format' => 'raw'
            ],
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\bichuv\models\BichuvMatoOrders::getStatusList($model->status))?app\modules\bichuv\models\BichuvMatoOrders::getStatusList($model->status):$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->updated_by))?\app\models\Users::findOne($model->updated_by)->user_fio:$model->updated_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model->updated_at<(60*60*24))?Yii::$app->formatter->format(date($model->updated_at), 'relativeTime'):date('d.m.Y H:i',$model->updated_at);
                }
            ],
        ],
    ]) ?>
    <?php if(!empty($models)){ $form = \yii\widgets\ActiveForm::begin()?>
        <div class="document-items">
            <?= CustomTabularInput::widget([
                'id' => 'documentitems_id',
                'form' => $form,
                'models' => $models,
                'theme' => 'bs',
                'showFooter' => true,
                'attributes' => [
                    [
                        'id' => 'footer_mato',
                        'value' => null
                    ],
                    [
                        'id' => 'footer_quantity',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_given',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_roll_count',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_count',
                        'value' => 0
                    ],
                ],
                'rowOptions' => [
                    'id' => 'row{multiple_index_documentitems_id}',
                    'data-row-index' => '{multiple_index_documentitems_id}'
                ],
                'max' => 100,
                'min' => 0,
                'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                'addButtonOptions' => [
                    'class' => 'hidden'
                ],
                'cloneButton' => false,
                'columns' => [
                    [
                        'name' => 'entity_type',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'entity_type'
                        ]
                    ],
                    [
                        'name' => 'entity_id',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'entity_id'
                        ]
                    ],
                    [
                        'name' => 'mop_id',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'mop_id'
                        ]
                    ],
                    [
                        'name' => 'name',
                        'title' => Yii::t('app', 'Maxsulot nomi'),
                        'options' => [
                            'class' => 'name',
                            'readOnly' => true
                        ],
                        'headerOptions' => [
                            'style' => 'width: 40%;',
                            'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                        ],
                    ],
                    [
                        'name' => 'quantity',
                        'title' => Yii::t('app', "So'ralgan (kg)"),
                        'options' => [
                            'class' => 'tabular-cell-summa quantity number',
                            'data-footer' => 'footer_quantity',
                            'data-summa' => 'quantity',
                            'readOnly' => true
                        ],
                        'headerOptions' => [
                            'style' => 'width: 140px;',
                        ]
                    ],
                    [
                        'name' => 'given_qty',
                        'title' => Yii::t('app', 'Berilgan (kg)'),
                        'options' => [
                            'class' => 'given_qty tabular-cell-summa',
                            'data-footer' => 'footer_given',
                            'data-summa' => 'given_qty',
                            'readonly' => true
                        ],
                        'value' => function($model){
                            return (!$model->isNewRecord)?$model->given:$model->given_qty;
                        },
                        'headerOptions' => [
                            'style' => 'width: 140px;',
                        ]
                    ],
                    [
                        'name' => 'roll_count',
                        'title' => Yii::t('app', 'Rulon soni'),
                        'options' => [
                            'class' => 'tabular-cell-summa roll-count number',
                            'data-footer' => 'footer_roll_count',
                            'data-summa' => 'roll-count',
                            'readOnly' => true
                        ],
                        'headerOptions' => [
                            'class' => 'incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'count',
                        'title' => Yii::t('app', 'Soni(dona)'),
                        'options' => [
                            'class' => 'tabular-cell-summa count number',
                            'data-footer' => 'footer_count',
                            'data-summa' => 'count',
                            'readOnly' => true
                        ],
                        'headerOptions' => [
                            'class' => 'incoming-multiple-input-cell'
                        ]
                    ],

                ]
            ]);
            ?>
        </div>
        <?php \yii\widgets\ActiveForm::end();
    }?>
    <?php if(!empty($models_aks)){ $form = \yii\widgets\ActiveForm::begin()?>
        <div class="document-items">
            <?= CustomTabularInput::widget([
                'id' => 'documentitems_aks_id',
                'form' => $form,
                'models' => $models_aks,
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
                        'title' => Yii::t('app', 'Aksessuar nomi'),
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
        </div>
        <?php \yii\widgets\ActiveForm::end();
    }?>
    <?=($responsible)?'<h4><small>'.Yii::t('app', "Tayyor bo'lmagan aksessuarlarga javobgar shaxs").':</small> '.$responsible->users->user_fio.'<p><small>'.Yii::t('app', 'Izox').':</small>'.$responsible->add_info:''?>
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
$js = <<< JS
    $('.tabular-cell-summa').each(function(index,value){
        let footer = $('#'+$(this).attr('data-footer'));
        let summa = $(this).parents('tbody').find('.'+$(this).attr('data-summa'));
        let sum = 0;
        summa.each(function(index,value) {
            let num = 1*$(this).val();
            let check = sum + num;
            if(!Number.isNaN(check)){
                sum += num;
            }
            console.log(num)
        });
        footer.html(sum);
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);