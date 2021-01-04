<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 15.05.20 18:47
 */



/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvOutcomeProductsPackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tikuv Outcome Products Packs');
$this->params['breadcrumbs'][] = $this->title;

use yii\grid\GridView;
use yii\helpers\Html;
use app\components\PermissionHelper as P;
?>
<div class="tikuv-outcome-products-pack-index">
    <?php if (P::can('tikuv-outcome-products-pack/create')): ?>
        <p class="pull-right no-print">
            <?= Html::a('<span class="fa fa-plus"></span>', ['usluga-form'], ['class' => 'btn btn-sm btn-success']) ?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
    <?php endif; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'musteri_id',
                'value' => function($model) {
                    return "<b>" . $model->musteri->name . "</b>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getMusteris(),
                'filterInputOptions' => [
                    'class' => 'form-control select3'
                ],
            ],
            [
                'attribute' => 'from_musteri',
                'value' => function($model){
                    return $model->fromMusteri->name;
                },
                'filter' => \app\modules\usluga\models\UslugaDoc::getMusteries(null,3),
                'filterInputOptions' => [
                    'class' => 'form-control select3'
                ],
            ],
            [
                'attribute' => 'nastel_no',
                'value' => function($model){
                    $nastel_list = $model->nastel_list;
                    return (!empty($nastel_list))?"<code>'".join("','",$nastel_list)."'</code>":"<code>'".$model->nastel_no."'</code>";
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'model_and_variations',
                'label' => Yii::t('app','Model va ranglari'),
                'value' =>  function($model){
                    $modelData = $model->getModelListInfo();
                    return "<p class='text-bold'>".$modelData['model']."</p>".$modelData['model_var_code'];
                },
                'options' => ['class' => 'text-center'],
                'format' => 'raw',
                'headerOptions' => ['style' => 'white-space: normal;width:20%'],
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"O'lcham / Soni"),
                'value' => function($model){
                    $result = $model->getWorkCount();
                    $size = "<div> {$result['size']}</div>";
                    $count = "<div> <b>{$result['count']}</b></div>";
                    $sort_1 =  " <i class='text-center text-green text-bold'>".$result['sort_1'].'</i>';
                    $sort_2 =  $result['sort_2']? ",  <i class='text-center text-aqua text-bold'>".$result['sort_2'].'</i>':'';
                    $brak = $result['brak']?", <i class='text-center text-red text-bold'>".$result['brak'].'</i>':'';
                    return $list = "<div class='text-center'>".$size.'<small>('.$sort_1.$sort_2.$brak.')</small>'.$count.'</div>';
                },
                'format' => 'raw'
            ],
            'add_info:ntext',
            [
                'attribute' => 'created_by',
                'value' => function($model) {
                    return $model->user->user_fio . "<br><small><i>" . date('d.m.y H:i', $model->updated_at) . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getUsers(),
                'contentOptions' => [
                    'style' => 'width:8%',
                ]
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $class = $model->status == 3 ? 'btn btn-xs btn-default' : 'btn btn-xs btn-success';
                    return Html::button($model->status == 3 ? Yii::t('app', 'Qabul qilingan') : Yii::t('app', 'Qabul qilinmagan'), ['class' => $class]);
                },
                'format' => 'raw',
                'filter' => [1=>Yii::t('app', 'Qabul qilinmagan'), 4=>Yii::t('app', 'Qabul qilingan')],
                'contentOptions' => [
                    'style' => 'width:8%',
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{usluga-form}{usluga-view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('tikuv-outcome-products-pack/view'),
                    'usluga-form' => function($model) {
                        return P::can('tikuv-outcome-products-pack/update') && $model->status === $model::STATUS_ACTIVE;
                    },
                    'delete' => function($model) {
                        return P::can('tikuv-outcome-products-pack/delete') && $model->status === $model::STATUS_ACTIVE;
                    }
                ],
                'buttons' => [
                    'usluga-form' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success mr1"
                        ]);
                    },
                    'usluga-view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary mr1"
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
            ],
        ],
    ]); ?>
</div>
<?php
$this->registerCssFile("/select2/select3.min.css");
$this->registerJsFile("/select2/select3.min.js", ['depends' => "yii\web\YiiAsset"]);