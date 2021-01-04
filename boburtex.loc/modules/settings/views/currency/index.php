<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\CustomEditableColumn\CustomEditableColumn as EditableColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\settings\models\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Currencies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="currency-index">

    <?php if(Yii::$app->user->can('currency/create')):?>
        <span class="pull-right">
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                ['value' =>\yii\helpers\Url::to(['create']), 'class' => 'create-dialog btn btn-sm btn-success']) ?>
        </span>
        <br>
        <br>
    <?php endif;?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['id' => 'settings-currency_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'usd',
            'start_date',
            'add_info:ntext',
            [
                'class' => EditableColumn::class,
                'attribute' => 'status',
                'url' => ['change-status'],
                'type' => 'select',
                'value' => function ($model) {
                    $class = $model->status == 1 ? 'btn btn-xs btn-success' : 'btn btn-xs btn-danger';
                    return Html::button($model->getStatusList($model->status), ['class' => $class]);
                },
                'filter' => $searchModel->getStatusList(),
                'editableOptions' => function ($model) {
                    return [
                        'source' => $model->statusList,
                        'value' => $model->status,
                        'pk' => $model->id
                    ];
                },
                'clientOptions' => [

                    'display' => (new \yii\web\JsExpression("function(res, newVal) {
                            return false;
                        }")),

                    'success' => (new \yii\web\JsExpression("function(res, newVal) {
                            if(res.success) {
                                $('a[data-pk=' + res.id + ']').html(res.btn);
                            }
                        }"))
                ],
            ],
            //'updated_at',
            //'created_by',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('currency/view'),
                    'update' => Yii::$app->user->can('currency/update'),
                    'delete' => Yii::$app->user->can('currency/delete')
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'lead-update'),
                            'data-form-id' => $model->id, 'class' => "update-dialog btn btn-xs btn-primary mr1",
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                            'class' => "btn btn-xs btn-danger delete-dialog",
                            'data-form-id' => $model->id,
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'update') {
                        return "#";
                    }
                    if ($action === 'delete') {
                        return "#";
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>


</div>


<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'currency',
    'modal_id' => 'settings-currency_pjax-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Currency') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'settings-currency_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>