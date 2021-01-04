<?php

use app\modules\admin\models\UsersHrDepartments;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UsersHrDepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Section permission');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-hr-departments-index">
    <?php if (Yii::$app->user->can('users-hr-departments/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'users-hr-departments_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'username',
                'label' => Yii::t('app', 'Username'),
                'value' => function($model){
                    return  $model->user->username;
                },
            ],
            [
                'attribute' => 'hr_departments_id',
                'value' => function($model){
                    return $model->getBelongToDepartments();
                },
                'format' => 'raw',
                'filter' => $searchModel->departments
            ],
            [
                'attribute' => 'departments_2',
                'value' => function($model){
                    return $model->getBelongToDepartments2();
                },
                'format' => 'raw',
                'filter' => $searchModel->getDepartments()
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->getUserName();
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatusList($model->status);
                },
                'filter' => UsersHrDepartments::getStatusList(),
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class' => "btn btn-xs btn-primary update-dialog",
                            'data-form-id' => $model->id
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            $url,
                            [
                                'title' => Yii::t('yii', 'Delete'),
                                'class' => 'btn btn-xs btn-danger',
                                'data-confirm' => 'Are you sure you want to delete?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>


<?php Pjax::end(); ?>

<?php
$css = <<<CSS
    span.each-department-tags {
        display:inline-block;
        font-size: 80%;
        background-color:#3c8dbc;
        margin: 1px 5px;
        border-radius: 3px;
        color:#fff;
        padding:3px;
    }
CSS;
    $this->registerCss($css)
    ?>
</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'users-hr-departments',
    'crud_name' => 'users-hr-departments',
    'modal_id' => 'users-hr-departments-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Section permission') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'users-hr-departments_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
