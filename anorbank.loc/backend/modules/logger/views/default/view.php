<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $requests common\modules\request_log\models\RequestLog[] */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Request Logs'), 'url' => ['index']];

?>
<?php foreach ($requests as $i => $model): ?>
    <?php
    if ($i === 0) {
        $this->title = $model->id;
        $this->params['breadcrumbs'][] = $this->title;
    }
    ?>
    <div class="box request-log-view">

        <div class="box-header">
            <h1 class="box-title"><?= Html::encode($model->type) ?></h1>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'service',
                    'date',
                    'body',
                ],
            ]) ?>
        </div>
    </div>
<?php endforeach; ?>
