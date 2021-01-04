<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<?=DetailView::widget([
    'model' => $model,
    'attributes' => [
        'table_number',
        'card_number',
        'fish',
        [
            'attribute' => 'hr_nation_id',
            'value' => function ($model) {
                return $model->hrNation->name;//(!empty($model['hr_nation_id'])) ? \app\modules\hr\models\HrNation::getNationList($model['hr_nation_id']) : '';
            }
        ],
        'address',
        'phone',
        'birth_date',
        [
            'attribute' => 'gender',
            'value' => function ($model) {
                return (!empty($model['gender'])) ? \app\models\Constants::getGenderList($model['gender']) : '';
            }
        ],
        'pasport_series',
        'by_whom',
        'inn',
        'inps',
        'military_rank',
        'serviceability',
        'special_account_num',
        'military_register_num'
    ],
]);?>