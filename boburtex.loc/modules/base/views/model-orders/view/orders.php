<?php
use yii\grid\GridView;
use yii\helpers\Html;
/* @var $dataProvider app\modules\base\models\ModelOrdersItems */
?>

<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['style' => 'display: none'],
        'options' => ['style' => 'font-size:11px;'],
        'filterModel' => false,
        'rowOptions' => function($model){
            return ['style'=> ($model->status!=2)?'':'background:#EF5350'];
        },
        'layout' => "{items}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width:25px;'],
            ],
            [
                'attribute' => 'id',
                'value' => function($model) {
                    return 'SM-'.$model->id;
                }
            ],
            [
                'attribute' => 'photo',
                'label' => Yii::t('app', "Rasmlar"),
                'value' => function($model) {
                    $image = ($model->modelsList->image)
                        ?"<img alt='".$model->modelsList->article."' src='/web/".$model->modelsList->image."' class='thumbnail imgPreview round' style='width:80px;height:80px;'> "
                        :'';
                    return $image;
                },
                'options' => ['width' => '90px'],
                'format' => 'html'
            ],
            [
                'attribute' => 'models_list_id',
                'value' => function($model){
                    //$image = ($model->modelsList->image)?"<img src='/web/".$model->modelsList->image."' class='thumbnail imgPreview round' style='width:80px;height:80px;'> ":'';
                    return $model->modelsList->name. " (".$model->modelsList->article .")";
                },
            ],
            [
                'attribute' => 'model_var_id',
                'value' => function($model){
                    //$image = ($model->modelVar->image)?"<img src='/web/".$model->modelVar->image."' class='thumbnail imgPreview round' style='width:80px;height:80px;'> ":'';
                    return  $model->modelVar->name .  $model->modelVar->color; // ' ' . $model->modelVar->code .
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'price',
                'value' => function($model){
                    return  number_format($model->price,2, '.', '')." ".$model->pb->name;
                },
                'format' => 'html'
            ],
            'add_info:ntext',
            [
                'attribute' => 'load_date',
                'format' =>  ['date', 'php:d.m.Y'],
                'options' => ['width' => '80px']
            ],
            [
                'attribute' => 'priority',
                'value' => function($model){
                    return ($model->priority)?$model->getPriorityList($model->priority):$model->priority;
                }
            ],
            'season',
            [
                'attribute' => 'baski_id',
                'value' => function($model){
                    return $model->baski;
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'prints_id',
                'value' => function($model){
                    return $model->prints;
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'stone_id',
                'value' => function($model){
                    return $model->stone;
                },
                'format' => 'html'
            ],
            /*[
                'attribute' => 'price',
                'value' => function($model){
                    return $model->price. " ".$model->pb['name'] ."";
                },
                'format' => 'html'
            ],*/
            /*[
                'attribute' => 'size_type',
                'label' => Yii::t('app','Size Type ID'),
                'value' => function($model){
                    return $model->modelOrdersItemsSizes[0]->size->sizeType['name'];
                }
            ],*/
            [
                'attribute' => 'size',
                'label' => Yii::t('app','Size'),
                'value' => function($model){
                    return $model->sizeList;
                },
                'format' => 'html',
                'options' => ['width' => '100px']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{new-order}{copy-order}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'copy-order' => function($model) {
                        return Yii::$app->user->can('model-orders/update');
                    },
                ],
                'buttons' => [
                    'copy-order' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['update-items', 'id' => $model->id], [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <div id="order-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app','Buyurtma')?></h3>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</div>
<?php
$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .print_div,.stone_div{
        width: 70px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        position: relative;
        margin-bottom: 3px;
    }
    .list_prints,.list_stone{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
    .check_button{
        position: absolute;
        bottom: -18px;
        left: 30%;
    }
CSS;
$this->registerCss($css);
$js = <<< JS
    $('body').delegate('.add-order', 'click', function(e){
        e.preventDefault();
        $('#order-modal').modal('show');
        $('#order-modal').find('.modal-body').load($(this).attr('href'));
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);