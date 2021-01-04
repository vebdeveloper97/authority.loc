<?php
    use yii\helpers\Url;
    use app\components\TabularInput\CustomTabularInput;

    /* @var $pechat \app\modules\base\models\ModelsPechat */
    /* @var $pechatImages \app\modules\base\models\ModelsPechat */
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="pechat_items">
            <?=CustomTabularInput::widget([
                'models' => $pechat,
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
//                                                'isMultipleValue' => true,
//                                                'id' => 'attachedImage',
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
