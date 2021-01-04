<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Unit */

$this->title = Yii::t('app', 'Create Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

$js = <<<JS
    $('#unit-name').keyup(function(e) {
        var code = $('#unit-code');
        code.val($(this).val().replace(/ |'|"/gi, '_').toUpperCase());
    });
JS;

$this->registerJs($js, $this::POS_READY);
?>
