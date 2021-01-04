<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $modelList app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */
/* @var $acs \app\modules\bichuv\models\BichuvAcs  */
/* @var $oneAcs \app\modules\base\models\ModelsAcs */
/* @var $isModel */
?>
<?= Tabs::widget([
    'items' => [
        [
            'label' => Yii::t('app','Main Information'),
            'url' => Url::to(['/base/models-list/update','id'=>($_GET['id'])?$_GET['id']:'','active'=>'true']),
        ],
        [
            'label' => 'Raw materials and Acsessuars',
            'url' => Url::to(['/base/models-id/update','id'=>($_GET['id'])?$_GET['id']:'']),
        ],
        [
            'label' => 'Variations',
            'content' => $this->render('_forma', [
                'model' => $model,
                'isModel' => $isModel,
                'colors' => $colors,
                'attachments' => $attachments,
                'modelList' => $modelList,
                'acs' => $acs,
                'oneAcs' => $oneAcs
            ]),
            'active' => true
        ],
    ]
]);?>
