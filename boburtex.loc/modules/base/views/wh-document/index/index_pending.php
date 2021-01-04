<?php

    use kartik\select2\Select2;
    use yii\helpers\Html;
use yii\grid\GridView;
    use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\WhDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $countPending  */

$this->title = Yii::t('app', 'Qabul qilish');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="">
            <a href="<?= Url::to('/base/wh-document/kirim/index')?>">
                <?= Yii::t('app', 'Kirim')?>
            </a>
        </li>
        <li class="active">
            <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                <?= Yii::t('app', 'Qabul qilish') ?>
                &nbsp;<span class="badge bg-gray pull-right"> <?= $countPending['count'] ?></span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="wh-document-index">
                <p class="pull-right no-print">
                    <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
                    <?= Html::button('<i class="fa fa-print print-btn"></i>',
                        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
                </p>

                <?php //echo $this->render('/wh-document/_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterRowOptions' => ['class' => 'filters no-print'],
                    'options' => ['style' => 'font-size:11px;'],
                    'rowOptions' => function($model){
                        if($model->status == $model::STATUS_ACTIVE)
                            return [
                                'class' => 'warning'
                            ];
                    },
                    'filterModel' => $searchModel,
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
                            'attribute' => 'from_department',
                            'label' => Yii::t('app','From Department'),
                            'value' => function($model){
                                return "<b>".$model->fromDepartment->name .
                                    "</b><br><small><i>". $model->fromEmployee->user_fio . "</i></small>";
                            },
                            'format' => 'raw',
                            'filter' => $searchModel->getDepartments(true)
                        ],
                        [
                            'attribute' => 'to_department',
                            'label' => Yii::t('app','Qayerga'),
                            'value' => function($model){
                                return "<b>".$model->toDepartment->name ."</b><br><small><i>".
                                    $model->toEmployee->user_fio . "</i></small>";
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
                            'template' => '{view}',
                            'contentOptions' => ['class' => 'no-print','style' => 'width:1%;'],
                            'visibleButtons' => [
                                'view' => Yii::$app->user->can('wh-document/pending/view'),
                            ],
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('app', 'View'),
                                        'class'=>"btn btn-xs btn-default"
                                    ]);
                                },

                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                $slug = Yii::$app->request->get('slug');
                                if ($action === 'view') {
                                    $url = Url::to(["view",'id'=> $model->id,'slug' => $slug]);
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
