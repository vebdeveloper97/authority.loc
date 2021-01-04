<?php

use app\modules\toquv\models\ToquvDocumentsSearch;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;
use app\modules\toquv\models\ToquvDocuments;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Documents {type}', ['type' => ToquvDocuments::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'number_and_date',
                'label' => Yii::t('app','Hujjat raqami va sanasi'),
                'value' => function($model){
                    $name = Yii::t('app','№{number} - {date}', ['number' => $model->doc_number,'date' => date('d.m.Y H:i', strtotime($model->reg_date))]);
                    $slug = Yii::$app->request->get('slug');
                    $url = Url::to(["view",'id'=> $model->id, 'slug' => $slug]);
                    return Html::a($name,$url);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'from_department',
                'label' => Yii::t('app','Qayerdan'),
                'value' => function($model){
                    return $model->fromDepartment->name;
                },
                'filter' => $searchModel->getDepartments(true)
            ],
            [
                'attribute' => 'to_department',
                'label' => Yii::t('app','Qayerga'),
                'value' => function($model){
                    return $model->toDepartment->name;
                },
                'filter' => $searchModel->getDepartments()
            ],

        ],
    ]); ?>
</div>
