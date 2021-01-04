<?php
use yii\helpers\Url;
use app\components\TabularInput\CustomTabularInput;

/* @var $naqsh \app\modules\base\models\ModelsNaqsh */
/* @var $naqshImages \app\modules\base\models\ModelsNaqsh */

?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="naqsh_items">
            <?=CustomTabularInput::widget([
                'models' => $naqsh,
                'addButtonOptions' => [
                    'class' => 'hidden',
                ],
                'removeButtonOptions' => [
                    'class' => 'btn-danger btn',
                ],
                'columns' => [
                    [
                        'name' => 'attachments_id',
                        'type' => \app\components\KCFinderInputWidgetCustom::class,
                        'title' => Yii::t('app', 'Add file'),
                        'options' => [
                            'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
                            'kcfBrowseOptions' => [
                                'langCode' => 'ru'
                            ],
                            'kcfOptions' => [
                                'uploadURL' =>  '/uploads',
                                'cookieDomain' => $_SERVER['SERVER_NAME'],
                                'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                'access' => [
                                    'files' => [
                                        'upload' => true,
                                        'delete' => true,
                                        'copy' => true,
                                        'move' => true,
                                        'rename' => true,
                                    ],
                                    'dirs' => [
                                        'create' => true,
                                        'delete' => true,
                                        'rename' => true,
                                    ],
                                ],
                                'thumbsDir' => 'thumbs',
                                'thumbWidth' => 150,
                                'thumbHeight' => 150,
                            ]
                        ],
                        'headerOptions' => [
                            'width' => '200px',
                        ],
                    ],
                    [
                        'name' => 'width',
                        'title' => Yii::t('app', "En(sm)"),
                        'options' => [
                            'options' => [
                                    'readonly' => true,
                            ]
                        ]
                    ],
                    [
                        'name' => 'height',
                        'title' => Yii::t('app', "Bo'yi(sm)"),
                        'options' => [
                            'options' => [
                                    'readonly' => true,
                            ]
                        ]
                    ],
                    [
                        'name' => 'base_details_list_id',
                        'title' => Yii::t('app', 'Base Details List'),
                        'options' => [
                            'options' => [
                                    'readonly' => true,
                            ]
                        ]
                    ],

                ]
            ])?>
        </div>
    </div>
</div>
