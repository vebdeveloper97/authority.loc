<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItemCategory */

$this->title = Yii::t('app', 'Create Wh Item Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Item Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-item-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

    $js = <<<JS
    $('#whitemcategory-name').keyup(function(e) {
        var code = $('#whitemcategory-code');
        code.val($(this).val().replace(/ |"/gi, '_').toUpperCase());
    });
JS;

    $this->registerJs($js, $this::POS_READY);
?>