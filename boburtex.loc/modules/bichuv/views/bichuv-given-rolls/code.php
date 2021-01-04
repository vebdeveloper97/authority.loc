<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 16.02.20 16:02
 */


/* @var $this \yii\web\View */
/* @var $roll array|false */

use yii\helpers\Html; ?>
<title><?=Yii::t('app', 'Nastel aksessuar')?></title>
<div class="pull-right no-print" style="margin-bottom: 15px;">
    <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-content btn-sm']) ?>
</div>
<div id="print-div">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="4" class="text-center text-red">
                <?=$roll['nastel_no']?>
            </th>

            <th rowspan="10" class="text-center">
                <div class="barcodeImg"><div id="barcode"></div></div>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Buyurtmachi')?>
            </th>
            <th>
                <?=$roll['name']?>
            </th>
            <th>
                <?php echo Yii::t('app','Model nomi')?>
            </th>
            <th>
                <?=$roll['model']?>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Partiya raqami')?>
            </th>
            <th>
                <?=$roll['party_no']?>
            </th>
            <th>
                <?php echo Yii::t('app','Mijoz partiya raqami')?>
            </th>
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
            <th>
                <?php echo Yii::t('app','Mato og\'irligi')?>
            </th>
            <th>
                <?=$roll['rulon_kg']?>
            </th>
            <th>
                <?php echo Yii::t('app','Eni')?>
            </th>
            <th>
                <?=$roll['en']?>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Rulon soni')?>
            </th>
            <th>
                <?=$roll['rulon_count']?>
            </th>
            <th>
                <?php echo Yii::t('app','Gramaj')?>
            </th>
            <th>
                <?=$roll['gramaj']?>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Mato turi')?>
            </th>
            <th>
                <?=$roll['party_no']?>
            </th>
            <th>
                <?php echo Yii::t('app','Pus/Fine')?>
            </th>
            <th>
                <?=$roll['pus_fine']?>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','Mato rangi')?>
            </th>
            <th>
                <?=$roll['ctone']?>
            </th>
            <th>
                <?php echo Yii::t('app','Ne')?>
            </th>
            <th>
                <?=$roll['ne']?>
            </th>
        </tr>
        <tr>
            <th>
                <?php echo Yii::t('app','TPX kodi')?>
            </th>
            <th>
                <?=$roll['pantone']?>
            </th>
            <th>
                <?php echo Yii::t('app',"Iplik turi")?>
            </th>
            <th>
                <?=$roll['thread']?>
            </th>
        </tr>
        </thead>
    </table>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-responsive table-bordered tableLeft">
                <tbody>
                <tr>
                    <th>
                        <?php echo Yii::t('app','Nastel qavati')?>
                    </th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <th>
                        <?php echo Yii::t('app','Mashina nomeri')?>
                    </th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <th>
                        <?php echo Yii::t('app','Smena')?>
                    </th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <th>
                        <?php echo Yii::t('app','Otxod miqdori')?>
                    </th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <th>
                        <?php echo Yii::t('app',"O'rtacha ish og'irligi (gr)")?>
                    </th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <th>
                        <?php echo Yii::t('app','Beka uchun')?>
                    </th>
                    <th>
                    </th>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 noPaddingLeft">
            <table class="table table-responsive table-bordered tableRight">
                <tbody>
                <tr>
                    <th>
                        <?php echo Yii::t('app',"O'lcham")?>
                    </th>
                    <th>
                        <?php echo Yii::t('app','Ish soni(reja)')?>
                    </th>
                    <th>
                        <?php echo Yii::t('app','Ish soni(fakt)')?>
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
                    <th>

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
                    <th>

                    </th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('/js/module_bichuv/jquery-qrcode.min.js', ['depends' => \app\assets\AppAsset::className()]);
$nastel_id = "BND-".$roll['bgri_id'];
$js = <<< JS
$("#barcode").qrcode({
        render: 'image',
		text : "$nastel_id"
	});	
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
$css = <<< CSS
    .tableLeft tr th{
        width: 50%;
    }
    .tableLeft tr th:last-child{
        text-align: center;
    }
    .tableRight tr th{
        width: 33%;
        text-align: center;
    }
CSS;
$this->registerCss($css);