<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 16.03.20 19:19
 */

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View */
/* @var $modelList null|static */
/* @var $variations \app\modules\base\models\ModelsVariations[] */
$var_id = Yii::$app->request->post('var_id');
?>
<div class="col-md-12 flex-container-variations">
    <?php $i = 1; if($variations){
        foreach ($variations as $key){
            if($key->id){?>
                <div class="thumbnail variations_div" <?=(!empty($var_id)&&$var_id==$key['id'])?'style="background:lime;"':''?>>
                    <div class="row">
                        <div class="col-md-12 parent_var">
                            <span class="num_var"><?=$i?></span>
                            <p></p>
                            <?php if(!Yii::$app->request->isAjax) {?>
                            <p>
                                <?=(!empty($key->modelVarRelAttaches[0]->attachment['path']))?
                                    Html::img("/web/".$key->modelVarRelAttaches[0]->attachment['path'],
                                        ['class'=>'thumbnail imageVariationMain imgPreview']
                                    ):'';
                                ?>
                            </p>
                            <?php }?>
                            <div class="item_var_name"> <b><?=$key->name?> <i><small>(<?=$key->code?>)</small></i></b></div>
                            <div class="" style="width: 100%;">
                                <div style="min-width: 100px;width: 100%;" title="<?= $key->colorPantone['code']?>">
                                    <span class="" style="background: rgb(<?=$key->colorPantone['r']?>,<?=$key->colorPantone['g']?>,<?=$key->colorPantone['b']?>);" title="<?= $key->colorPantone['code']?>">
                                        <span style="opacity: 0;">
                                            <?=substr($key->colorPantone['code'],-3)?>
                                        </span>
                                    </span>
                                    <b><?= $key->colorPantone['code']?></b>
                                </div>
                                <div style="min-width: 100px;width: 100%;" title="<?= $key->boyoqhonaColor['color_id']; ?>">
                                    <span class="" style="background: <?= $key->boyoqhonaColor['color'];?>;">
                                        <span style="opacity: 0;">
                                            <?=substr($key->boyoqhonaColor['color_id'],-3)?>
                                        </span>
                                    </span>
                                    <b> <?= $key->boyoqhonaColor['color_id']; ?> </b>
                                </div>
                            </div>
                            <div class="button-var">
                                <button type="button" class="btn btn-success btn-xs check_btn_var" data-id="<?=$key->id?>"  <?=(!empty($var_id)&&$var_id==$key['id'])?'style="display:none;"':''?>><?php echo Yii::t('app','Tanlash')?></button>
                                <button type="button" class="checked_btn_var btn-success btn btn-xs <?=(empty($var_id)||$var_id!=$key['id'])?'hidden':''?>" ><i class="fa fa-check"></i></button>
                                <button type="button" class="btn btn-default btn-xs view_var"><i class="fa fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="col-md-9 parentViewVariation hidden">
                            <?= Tabs::widget([
                                'items' => [
                                    [
                                        'label' => Yii::t('app','Detal uchun rang va matolar'),
                                        'content' => $this->render('view/_colors', [
                                            'colors' => $key->modelsVariationColors,
                                        ]),
                                        'active' => true,
                                        'options' =>[
                                            'style' => 'padding-top:20px'
                                        ]
                                    ],
                                    /*[
                                        'label' => Yii::t('app','Add Info'),
                                        'content' => $key->add_info,
                                    ],*/
                                    /*[
                                        'label' => Yii::t('app','Variation attachments'),
                                        'url' => '#!',
                                        'linkOptions' => [
                                            'src' => Url::to([
                                                '/base/models-variations/view','id'=>$key['id'],'active'=>'attachments','num'=>$i
                                            ])
                                        ]
                                    ],*/
                                ],
                                'options' =>[
                                    'style' => 'margin-top:-20px',
                                    'class' => 'viewVariation'
                                ]
                            ]);?>
                        </div>
                    </div>
                </div>
                <?php $i++;}}
    }?>
</div>
<?php
$css = <<< CSS
    .flex-container-variations{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .variations_div{
        width: 7vw;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        text-align: center;
    }
    .variations_div *{
        font-size: 1em;
    }
    .variations_div .imgPreview{
        width: 100%;
    }
    .open-var{
        width: 90vw;
    }
    .num_var{
        position: absolute;
        top: 0;
        left: 20px;
    }
    .parent_var{
        min-height: 65px;
    }
    .button-var{
        padding-top: 5px;
    }
CSS;

$viewVarUrl = Yii::$app->urlManager->createUrl('base/models-variations/colors');
$js = <<< JS
    $('body').delegate('.view_var', 'click', function(e){
        let t = $(this);
        let parent = t.parents('.variations_div');
        if(t.attr('data-status')=='no_load'){
            parent.find('.parentViewVariation').load('$viewVarUrl?id='+t.attr('data-id'));
        }
        if(t.attr('data-status')!='open'){
            t.parents('.parent_var').removeClass('col-md-12').addClass('col-md-3');
            parent.addClass('open-var');
            parent.find('.parentViewVariation').removeClass('hidden');
            t.attr('data-status','open');
            t.find('i').removeClass('fa-eye').addClass('fa-close');
            t.removeClass('btn-default').addClass('btn-danger');
        }else{
            t.parents('.parent_var').removeClass('col-md-3').addClass('col-md-12');
            parent.removeClass('open-var');
            parent.find('.parentViewVariation').addClass('hidden');
            t.attr('data-status','hidden');
            t.find('i').removeClass('fa-close').addClass('fa-eye');
            t.removeClass('btn-danger').addClass('btn-default');
        }
    });
    $('body').delegate('.check_btn_var', 'click', function(e){
        let t = $(this);
        let rm_parent = t.parents('.rmParent');
        rm_parent.find('.check_btn_var').show();
        rm_parent.find('.checked_btn_var').addClass('hidden');
        rm_parent.find('.variations_div').css('background','none');
        rm_parent.find('.model_var_id').val(t.attr('data-id')).trigger('change');
        let name = t.parents('.parent_var').find('.item_var_name').html();
        rm_parent.find('.var_name').html(name);
        t.parents('.variations_div').css('background','lime');
        t.hide();
        t.next().removeClass('hidden');
    });
JS;
if(!Yii::$app->request->isAjax) {
    $this->registerCss($css);
    $this->registerJs($js, \yii\web\View::POS_READY);
}