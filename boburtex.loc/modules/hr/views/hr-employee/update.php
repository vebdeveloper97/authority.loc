<?php

use yii\helpers\Html;
use app\widgets\helpers\Script;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $skills \app\modules\hr\models\EmployeeRelSkills[] */

$this->title = Yii::t('app', 'Update: {name}', [
    'name' => $model->fish,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fish, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hr-employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'attachment' => $attachment,
        'study' => $study,
        'work' => $work,
        'imageUploadForm' => $imageUploadForm,
        'attachmentAll' => $attachmentAll,
        'attachmentAllOldImages' => $attachmentAllOldImages,
        'skills' => $skills,

    ]) ?>

</div>
<?php if($url == 'http://boburtex.loc/uz/hr/hr-employee/create'): ?>
<?php Script::begin(); ?>
<script>
    $(function () {
        let href = $('a[href="#w7-tab1"]');
        let href1 = $('a[href="#w7-tab0"]');
        let tab1 = $('.tab-content').children('#w7-tab0');
        let tab2 = $('.tab-content').children('#w7-tab1');
        href.parents('li').addClass('active');
        tab2.addClass('active');
        href1.parents('li').removeClass('active');
        tab1.removeClass('active');
    })
</script>
<?php Script::end(); ?>
<?php endif; ?>
