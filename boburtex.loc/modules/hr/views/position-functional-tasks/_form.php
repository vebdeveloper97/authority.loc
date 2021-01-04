<?php

use app\modules\hr\models\PositionFunctionalTasks;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\hr\assets\TinymceAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\PositionFunctionalTasks */
/* @var $form yii\widgets\ActiveForm */

TinymceAsset::register($this);
?>

<div class="position-functional-tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'status')->dropDownList(PositionFunctionalTasks::getStatusList()) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'tasks')->textarea(['id' => 'tasksArea']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS
tinymce.init({
    selector: '#tasksArea',

    plugins: [
        'advlist link image lists code help table wordcount',
        'emoticons', // emoji icons
        'imagetools'
    ],
    menubar: 'file edit view insert format table help',
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image link | emoticons',

    height: 500,
    language: 'ru',

    /* image config */
    images_upload_handler: function (blobInfo, success, failure) {
        let xhr, formData;

        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '/hr/upload/upload-image');

        xhr.onload = function() {
            let json;

            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }

            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }

            success(json.location);
        };

        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        /* append csrf token */
        formData.append(yii.getCsrfParam(), yii.getCsrfToken());

        xhr.send(formData);
    },

    relative_urls : false,
    convert_urls : true,
    image_caption: true, // figure tag
    extended_valid_elements: 'figure[class|name|id],figcaption[class|name|id]',
});
JS;
$this->registerJs($js);

