<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\request_log\models\RequestLog;
use backend\modules\logger\models\RequestLogSearch;

/* @var $this yii\web\View */
/* @var $searchModel RequestLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Request Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box request-log-index">

    <div class="box-header">
        <h1 class="box-title"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                [
                    'attribute' => 'service',
                    'value'     => static function (RequestLog $model) {
                        return Html::a($model->service, ['view', 'id' => $model->id]);
                    },
                    'format'    => 'raw',
                ],
                [
                    'attribute'      => 'body',
                    'contentOptions' => ['class' => 'word-break'],
                ],
                'date:datetime',
            ],
        ]); ?>
    </div>
</div>
