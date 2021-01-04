<?php
use yii\helpers\Html;
use app\widgets\helpers\Script;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$slug = $this->context->slug;
$t = Yii::$app->request->get('t',1);
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="toquv-documents-view">
        <div class="pull-right" style="margin-bottom: 15px;">
            <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
            <?php if (Yii::$app->user->can('doc/kirim_mato/update')): ?>
                <?php if($model->status != $model::STATUS_SAVED):?>
                    <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $slug,'t' => $t], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 't' => $t, 'slug' => $slug],
                        ['class' => 'btn btn-success']) ?>
                <?php endif;?>
            <?php endif;?>
            <?php if (Yii::$app->user->can('doc/kirim_mato/delete')): ?>
                <?php if($model->status != $model::STATUS_SAVED):?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $slug, 't' => $t], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif;?>
            <?php endif;?>
        </div>
        <table class="table table-bordered table-responsive">
            <tr>
                <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->musteri->name ?></td>
                <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->musteri_responsible ?></td>
                <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
            </tr>
            <tr>
                <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
                <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            </tr>
            <tr>
                <td colspan="2"><strong><?= Yii::t('app', 'Asos')?></strong>: <?= $model->add_info ?></td>
            </tr>
        </table>
        <div class="center-text">
            <?php $items = $model->getMatoView($model->id, $t); ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Mato</th>
                        <th>En/Gramaj</th>
                        <th>Rangi</th>
                        <th>Model</th>
                        <th>Partiya № / Mijoz Partiya</th>
                        <th>Rulon soni</th>
                        <th>Miqdori (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalRoll = 0;
                    $totalKg = 0;
                    foreach ($items as $key=> $item):?>
                        <tr>
                            <td><?= ($key+1);?></td>
                            <td class="expand-party" data-bss-id = "<?= $item['id']; ?>">
                                <?php
                                if($item['is_accessory'] == 1){
                                    echo "{$item['mato']}-{$item['ne']}-{$item['ip']}|{$item['pus_fine']}";
                                }else{
                                    echo "{$item['mato']}-{$item['ip']}";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($item['is_accessory'] == 1){
                                    echo "{$item['mato_en']} sm /{$item['gramaj']} gr/m<sup>2</sup>";
                                }else{
                                    echo Yii::t('app','Aksessuar');
                                }
                                ?>
                            </td>
                            <td><?= "{$item['ctone']} {$item['color_id']} {$item['pantone']}"?></td>
                            <td><?= $item['model']?></td>
                            <td><?= "{$item['partiya_no']}/{$item['mijoz_part']}"?></td>
                            <td><?= $item['count_rulon']?></td>
                            <td><?= $item['rulon_kg']?></td>
                        </tr>
                    <?php
                    $totalKg += $item['rulon_kg'];
                    $totalRoll += $item['count_rulon'];
                    endforeach;?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                        <td class="text-bold"><?= $totalRoll?></td>
                        <td class="text-bold"><?= $totalKg; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php
$header = Yii::t('app','Partiya rulonlari');
$slug = Yii::$app->request->get('slug');
$loadRollUrl = Url::to(['load-rolls','slug' => $slug]);
Modal::begin([
    'header' => "<h2>{$header}</h2>",
    'id' => 'expandRollModal'
  ]);
Modal::end();
?>
<?php Script::begin();?>
    <script>
        $('.expand-party').on('click', function (e) {
            $('#expandRollModal').modal('show');
            let id = $(this).data('bss-id');
            $('#expandRollModal .modal-body').load('<?= $loadRollUrl; ?>?id='+id+'&t=<?= $t;?>');
        });
    </script>
<?php Script::end()?>
