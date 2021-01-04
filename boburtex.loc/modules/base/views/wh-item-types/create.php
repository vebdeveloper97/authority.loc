<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItemTypes */

$this->title = Yii::t('app', 'Create Wh Item Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-item-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

    $js = <<<JS
    $('#whitemtypes-name').keyup(function(e) {
        var code = $('#whitemtypes-code');
        code.val($(this).val().replace(/ |'|"/gi, '_').toUpperCase());
    });
JS;

    $this->registerJs($js, $this::POS_READY);
?>
