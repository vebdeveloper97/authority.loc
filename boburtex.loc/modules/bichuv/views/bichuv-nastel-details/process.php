<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 25.02.20 18:40
 */



/* @var $this \yii\web\View */
/* @var $roll array|false */
/* @var $roll_items array */
$this->title = Yii::t('app', 'Process');
if(!empty($this->context->slug) && !empty($this->context->type)){
    $this->params['breadcrumbs'][] = ['label' => $this->context->_process['name'], 'url' => ['index', 'slug' => $this->context->slug]];
}
if(!empty($this->context->slug) && !empty($this->context->type) && !empty($this->context->table)){
    $this->params['breadcrumbs'][] = ['label' => $this->context->_type['name'], 'url' => ['index', 'slug' => $this->context->slug, 'type' => $this->context->type]];
    $this->params['breadcrumbs'][] = ['label' => $this->context->_table['name'], 'url' => ['index', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $this->context->table]];
}
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="row">
    <div class="col-md-12">
        <p class="pull-right no-print">
            <?= Html::a(Yii::t('app', 'Orqaga qaytish'), ['index', 'slug' => $this->context->slug, 'type' => $this->context->type, 'table' => $this->context->table], ['class' => 'btn btn-sm btn-success']) ?>
        </p>
    </div>
</div>
<div class="bichuv-nastel-details-update">
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>
    <div class="row">
        <div class="col-md-4">
            <?php if($sub){ ?>
                <?=$form->field($sub[0],'id')->hiddenInput()->label(false)?>
                <?=$form->field($sub[0],'roll_remain')->textInput(['class' => 'number form-control'])?>
                <?=$form->field($sub[0],'remain')->textInput(['class' => 'number form-control'])?>
                <?=$form->field($sub[0],'otxod')->textInput(['class' => 'number form-control'])?>
            <?php
            }?>
        </div>
        <div class="col-md-8">
            <?php if ($roll_items){?>
                    <table class="table table-responsive table-bordered text-center">
                        <tbody>
                        <tr>
                            <th>
                                <?php echo Yii::t('app',"O'lcham")?>
                            </th>
                            <th>
                                <?php echo Yii::t('app','Ish soni(reja)')?>
                            </th>
                            <th>
                                <?php echo Yii::t('app','Ish soni(reja) (kg)')?>
                            </th>
                            <th>
                                <?php echo Yii::t('app','Ish soni(fakt)(dona)')?>
                            </th>
                            <th>
                                <?php echo Yii::t('app','Ish soni(fakt)(kg)')?>
                            </th>
                            <th>
                                <?php echo Yii::t('app','Brak')?>
                            </th>
                        </tr>
                        <?php $count = 0;
                        $count_weight = 0;
                        $count_fakt = 0;
                        $weight_fakt = 0;
                        $count_brak = 0;
                        foreach ($roll_items as $roll_item) { ?>
                            <tr>
                                <th>
                                    <?=$roll_item->size['name']?>
                                </th>
                                <th>
                                    <?=$roll_item['required_count']?>
                                </th>
                                <th>
                                    <?=$roll_item['required_weight']?>
                                </th>
                                <th>
                                    <?=$form->field($roll_item, 'count')->textInput(['name' => "BichuvNastelDetailItems[{$roll_item['id']}][count]", 'class' => 'number text-center form-control'])->label(false)?>
                                </th>
                                <th>
                                    <?=$form->field($roll_item, 'weight')->textInput(['name' => "BichuvNastelDetailItems[{$roll_item['id']}][weight]", 'class' => 'number text-center form-control'])->label(false)?>
                                </th>
                                <th>
                                    <?=$roll_item['brak']?>
                                </th>
                            </tr>
                            <?php $count += $roll_item['required_count']; $count_weight += $roll_item['required_weight']; $count_fakt += $roll_item['count']; $weight_fakt += $roll_item['weight']; $count_brak += $roll_item['brak']; }?>
                        <tr>
                            <th>
                                <?php echo Yii::t('app','Jami')?>
                            </th>
                            <th>
                                <?=$count?>
                            </th>
                            <th>
                                <?=$count_weight?>
                            </th>
                            <th>
                                <?=$count_fakt?>
                            </th>
                            <th>
                                <?=$weight_fakt?>
                            </th>
                            <th>
                                <?=$count_brak?>
                            </th>
                        </tr>
                        </tbody>
                    </table>
            <?php }?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton(Yii::t('app', "To'xtatib turish"), ['class' => 'btn btn-primary', 'name' => 'pause', 'value' => 1]) ?>
        <?= Html::submitButton(Yii::t('app', 'Save and finish'), ['class' => 'btn btn-danger', 'name' => 'finish', 'value' => 1]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
