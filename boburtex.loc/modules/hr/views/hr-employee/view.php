<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $skills \app\modules\hr\models\EmployeeRelSkills[] */
$this->registerCss("
    #my_images{
        position: absolute;
        top: 13%;
        right: 10px;
        box-shadow: 0px 0px 5px grey;
    }
");
$this->title = $model->fish;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('hr-employee/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('hr-employee/delete')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
<?php }?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?=Yii::t('app','Asosiy ma\'lumotlar')?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?=Yii::t('app','Qo\'shimcha ma\'lumotlar')?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><?=Yii::t('app','Fayllar')?></a>
    </li>
</ul>
<div class="tab-content" id="myTabContent" style="padding: 10px 0">
    <div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="hr-employee-view">
           <?=  $this->render('view/view_main',[
                   'model' => $model
           ])?>
        </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <?=  $this->render('view/view_additional',[
            'work' => $work,
            'study' => $study,
            'skills' => $skills,
        ])?>
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <?=  $this->render('view/view_files',[
            'attachment' => $attachment,
            'study' => $study
        ])?>
    </div>
</div>



<?php

?>
<?php
$js = <<<JS
    $('#myTab a[href="#home"]').tab('show')
JS;
$this->registerJs($js,yii\web\View::POS_READY);
?>