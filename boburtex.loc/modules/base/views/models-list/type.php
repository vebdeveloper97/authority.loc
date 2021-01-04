<?php
use app\assets\AppAsset;

use kartik\select2\Select2;

AppAsset::register($this);
?>

<?php
echo Select2::widget([
    'name' => 'vala',
    'data' => $model->cp['child'],
]);
