<?php

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="row form-group">
        <div class="col-md-12">
            <div class="baski-items">
                <div class="multiple-input">
                    <table class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"
                                style="width: 200px;"><?=Yii::t('app','Name')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info"
                                style="width: 200px;">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__stone_attachments"
                                style="width: auto;">
                                <?=Yii::t('app','Attachments')?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; foreach ($baski as $key){?>
                            <tr id="row<?=$i?>" class="multiple-input-list__item" data-row-index="<?=$i?>">
                                <td class="list-cell__name">
                                    <div class="form-group">
                                    <span class="thumbnail"><?=$key['name']?>
                                    </span>
                                    </div>
                                </td>
                                <td class="list-cell__add_info">
                                    <div class="form-group">
                                    <span class="thumbnail" style="margin-bottom:10px;min-height: 24px" tabindex="1"
                                          rows="1">
                                        <?=$key['add_info']?>
                                    </span>
                                    </div>
                                </td>
                                <td class="list-cell__stone_attachments row">
                                    <div class="form-group">
                                        <?php foreach ($key->modelVarBaskiRelAttaches as $image){?>
                                            <label class="upload upload-mini imgPreview" src="/web/<?=$image->attachment['path']?>" style="background-image: url(/web/<?=$image->attachment['path']?>);">
                                            </label>
                                        <?php }?>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>