<?php

use app\modules\bichuv\models\BichuvGivenRollItems;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var array $roll app\modules\bichuv\models\BichuvGivenRollItems */

$this->title = $roll['nastel_no'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Nastel Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bichuv-nastel-details-view">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="5" class="text-center text-red">
                <?=$roll['nastel_no']?>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Buyurtmachi')?>
            </th>
            <th>
                <?=$roll['name']?>
            </th>
            <td>
                <?php echo Yii::t('app','Model nomi')?>
            </td>
            <th>
                <?=$roll['model']?>
            </th>
        </tr>
        <tr>
            <td>
                <?php echo Yii::t('app','Partiya raqami')?>
            </td>
            <th>
                <?=$roll['party_no']?>
            </th>
            <td>
                <?php echo Yii::t('app','Mijoz partiya raqami')?>
            </td>
            <th>
                <?=$roll['musteri_party_no']?>
            </th>
        </tr>
        <tr>
            <th></th><th></th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Mato nomi')?>
            </th>
            <th colspan="3">
                <?=$roll['mato']?>
            </th>
        </tr>
        <tr>
            <td>
                <?php echo Yii::t('app','Mato og\'irligi')?>
            </td>
            <th>
                <?=$roll['rulon_kg']?>
            </th>
            <td>
                <?php echo Yii::t('app','Eni')?>
            </td>
            <th>
                <?=$roll['en']?>
            </th>
        </tr>
        <tr>
            <td>
                <?php echo Yii::t('app','Rulon soni')?>
            </td>
            <th>
                <?=$roll['rulon_count']?>
            </th>
            <td>
                <?php echo Yii::t('app','Gramaj')?>
            </td>
            <th>
                <?=$roll['gramaj']?>
            </th>
        </tr>
        <tr>
            <td>
                <?php echo Yii::t('app','Mato turi')?>
            </td>
            <th>
                <?=$roll['party_no']?>
            </th>
            <td>
                <?php echo Yii::t('app','Pus/Fine')?>
            </td>
            <th>
                <?=$roll['pus_fine']?>
            </th>
        </tr>
        <tr>
            <td>
                <?php echo Yii::t('app','Mato rangi')?>
            </td>
            <th>
                <?=$roll['ctone']?>
            </th>
            <td>
                <?php echo Yii::t('app','Ne')?>
            </td>
            <th>
                <?=$roll['ne']?>
            </th>
        </tr>
        <tr>
            <td>
                <?php echo Yii::t('app','TPX kodi')?>
            </td>
            <th>
                <?=$roll['pantone']?>
            </th>
            <td>
                <?php echo Yii::t('app',"Iplik turi")?>
            </td>
            <th>
                <?=$roll['thread']?>
            </th>
        </tr>
        </thead>
    </table>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive table-bordered text-center">
                <tbody>
                <tr>
                    <th>
                        <?php echo Yii::t('app',"O'lcham")?>
                    </th>
                    <th>
                        <?php echo Yii::t('app','Ish soni(reja)')?>
                    </th>
                </tr>
                <?php $count = 0; foreach ($roll_items as $roll_item) { $item = $roll_item['required_count'] ?? $roll_item['required_weight'];?>
                    <tr>
                        <th>
                            <?=$roll_item['size']?>
                        </th>
                        <th>
                            <?=$item?>
                        </th>
                    </tr>
                    <?php $count += $item; }?>
                <tr>
                    <th>
                        <?php echo Yii::t('app','Jami')?>
                    </th>
                    <th>
                        <?=$count?>
                    </th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="submit" class="saveButton" id="saveButton" data-id="<?=$roll['bgri_id']?>" data-toggle="modal" data-target="#beginConfirmModal">
                <?=Yii::t('app','Boshlash')?>
            </button>
        </div>
    </div>
</div>
