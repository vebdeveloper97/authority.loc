<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\CustomEditableColumn\CustomEditableColumn as EditableColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvIpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Ip');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="toquv-ip-index">

        <?php if (Yii::$app->user->can('toquv-ip/create')): ?>
            <span class="pull-right">
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                ['value' => \yii\helpers\Url::to(['create']), 'class' => 'create-dialog btn btn-sm btn-success']) ?>
        </span>
            <br>
        <?php endif; ?>


        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php Pjax::begin(['id' => 'toquv-departments_pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'ne_id',
                    'value' => function($model) {
                        return $model->neName;
                    }
                ],
                [
                    'attribute'=>'thread_id',
                    'value' => function($model) {
                        return $model->threadName;
                    }
                ],
                'name',
                [
                    'attribute'=>'color_id',
                    'value' => function($model) {
                        return $model->colorName;
                    }
                ],
                [
                    'attribute' => 'rawMaterialConsist',
                    'format' => 'raw',
                ],
                /*[
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
                            'pk' => $model->id,
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
                ],*/
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'visibleButtons' => [
                        'view' => Yii::$app->user->can('toquv-ip/view'),
                        'update' => Yii::$app->user->can('toquv-ip/update'),
                        'delete' => Yii::$app->user->can('toquv-ip/delete')
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
    'model' => 'toquv-ip',
    'modal_id' => 'toquv-ip-modal',
    'modal_header' => Yii::t('app','Toquv Ip'),
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'toquv-departments_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>


<?php Modal::begin([
    'id' => 'modal',
    'size' => 'modal-sm',
    'header' => '<h3></h3>',
]); ?>
        <div class="form-group ">
            <label class="control-label" for="toquvip-name"><?= Yii::t('app', 'Name'); ?></label>
            <input type="text" id="newItemName" class="form-control" name="ToquvIp[name]" maxlength="50"
                   aria-required="true" aria-invalid="true">
        </div>
        <br>
        <div class="form-group">
            <span class="btn btn-success" onClick="create()"><?= Yii::t('app', 'Save')?></span>
        </div>
<?php Modal::end();


?>

<?php $this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/toquv-ip.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
?>