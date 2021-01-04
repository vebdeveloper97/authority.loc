<?php

    use kartik\select2\Select2;
    use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\WhDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $countPending  */

$this->title = Yii::t('app', 'Kirim');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><?= Yii::t('app', 'Kirim') ?></a></li>
        <li class="">
            <a href="<?= Url::to('/base/wh-document/pending/index')?>">
                <?= Yii::t('app', 'Qabul qilish')?>
                &nbsp;<span class="badge bg-gray pull-right"> <?= $countPending['count'] ?></span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="wh-document-index">
                <?php if (Yii::$app->user->can('wh-document/kirim/create')): ?>
                    <p class="pull-right no-print">
                        <?= Html::a('<span class="fa fa-plus"></span>', ['wh-document/kirim/create'], ['class' => 'btn btn-sm btn-success']) ?>
                        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= Html::button('<i class="fa fa-print print-btn"></i>',
                            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
                    </p>
                <?php endif; ?>

                <?php //echo $this->render('/wh-document/_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterRowOptions' => ['class' => 'filters no-print'],
                    'options' => ['style' => 'font-size:11px;'],
                    'filterModel' => $searchModel,
                    'rowOptions' => function($model){
                        if($model->status == $model::STATUS_ACTIVE)
                            return [
                                'class' => 'warning'
                            ];
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'doc_number',
                            'label' => Yii::t('app','Hujjat'),
                            'value' => function($model){
                                return '<b>'.$model->doc_number.'</b><br>'
                                    .'<small><i>'.date('d.m.Y', strtotime($model->reg_date)).'</i></small>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'musteri_id',
                            'label' => Yii::t('app','Yetkazib beruvchi'),
                            'value' => function($model){
                                return "<b>".$model->musteri->name . "</b><br><small><i>" . $model->musteri_responsible . "</i></small>";
                            },
                            'format' => 'raw',
                            'filter' => $searchModel->getMusteries()
                        ],
                        [
                            'attribute' => 'to_department',
                            'label' => Yii::t('app','Qayerga'),
                            'value' => function($model){
                                return "<b>".$model->toDepartment->name ."</b><br><small><i>". $model->toEmployee->user_fio . "</i></small>";
                            },
                            'format' => 'raw',
                            'filter' => $searchModel->getDepartments()
                        ],
                        [
                            'attribute' => 'add_info',
                            'label' => Yii::t('app','Add Info'),
                        ],
                        [
                            'attribute' => 'created_by',
                            'contentOptions' => ['style' => 'width:10%;'],
                            'value' => function($model){
                                return $model->author->user_fio
                                    ."<br><small><i>" .
                                    date('d.m.Y H:i',$model->created_at) .
                                    "</i></small>";
                            },
                            'format' => 'html',
                            'filter' => Select2::widget([
                                'model' =>  $searchModel,
                                'attribute' => 'created_by',
                                'data' => \app\modules\base\models\WhDocument::getAuthorList(),
                                'language' => 'ru',
                                'options' => [
                                    'prompt' => '',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}{view}{delete}',
                            'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                            'visibleButtons' => [
                                'view' => Yii::$app->user->can('wh-document/kirim/view'),
                                'update' => function($model) {
                                    return Yii::$app->user->can('wh-document/kirim/update') && $model->status !== $model::STATUS_SAVED;
                                },
                                'delete' => function($model) {
                                    return Yii::$app->user->can('wh-document/kirim/delete') && $model->status !== $model::STATUS_SAVED;
                                }
                            ],
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('app', 'Update'),
                                        'class'=>"btn btn-xs btn-success"
                                    ]);
                                },
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('app', 'View'),
                                        'class'=>"btn btn-xs btn-default"
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                        'title' => Yii::t('app', 'Delete'),
                                        'class' => "btn btn-xs btn-danger",
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]);
                                },

                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                $slug = Yii::$app->request->get('slug');
                                if ($action === 'update') {
                                    $url = Url::to(["update",'id'=> $model->id, 'slug' => $slug]);
                                    return $url;
                                }
                                if ($action === 'view') {
                                    $url = Url::to(["view",'id'=> $model->id,'slug' => $slug]);
                                    return $url;
                                }
                                if ($action === 'delete') {
                                    $url = Url::to(["delete",'id' => $model->id,'slug' => $slug]);
                                    return $url;
                                }
                                if ($action === 'return') {
                                    $url = Url::to(["return",'id' => $model->id,'slug' => $model::DOC_TYPE_RETURN_LABEL]);
                                    return $url;
                                }
                            }
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>

<?php
    $css = <<< Css
.select2-container--krajee strong.select2-results__group{
display:none;
}

.select2-container--krajee ul.select2-results__options>li.select2-results__option[aria-selected] {
font-size: 11px;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
right: 5px;
opacity: 0.5;
z-index: 999;
font-size: 18px;
top: -7px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
top: 60%;
}
Css;
    $this->registerCss($css);