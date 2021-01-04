<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $attachment app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $study app\modules\hr\models\HrEmployeeStudy */
/* @var $work app\modules\hr\models\HrEmployeeWorkPlace */
/* @var $attachmentAll app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $imageUploadForm \app\models\UploadForm */
/* @var $skills \app\modules\hr\models\EmployeeRelSkills[] */

$this->title = Yii::t('app', 'Add Employee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-employee-create">

    <?= $this->render('_form', [
        'model' => $model,
        'attachment' => $attachment,
        'study' => $study,
        'work' => $work,
        'attachmentAll' => $attachmentAll,
        'imageUploadForm' => $imageUploadForm,
        'skills' => $skills,
    ]) ?>

</div>
