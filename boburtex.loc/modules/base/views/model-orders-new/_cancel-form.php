<?php
/** @var $this \yii\web\View */
/* @var $commentForm \app\modules\base\models\ModelOrdersCommentVarRel */

use kartik\tree\TreeViewInput;
use yii\helpers\Html;

?>

<?php \yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'header' => Yii::t('app', 'Indicate the reasons'),
    'options' => [
        'tabindex' => false,
    ],
    'id' => 'model_order_cancel_modal',
//    'size' => 'modal-sm',
]);

 ?>
    <div id="modal_content">
        <?php $form = \yii\widgets\ActiveForm::begin([]) ?>

            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($commentForm, 'type')
                        ->label(false)
                        ->radioList(['1' =>Yii::t('app','Order'), '2' => Yii::t('app','Variation')], ['separator'=>'<br/>']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?= TreeViewInput::widget([
                            'id' => 'comment',
                            'name' => $commentForm->formName().'[comments]',
                            'query' => \app\modules\base\models\ModelOrdersComment::find()->addOrderBy('root, lft'),
                            'headingOptions' => ['label' => Yii::t('app', "Comment")],
                            'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                            'fontAwesome' => true,
                            'asDropdown' => true,
                            'multiple' => true,
                            'options' => ['disabled' => false],
                            'dropdownConfig' => [
                                'input' => [
                                    'placeholder' => Yii::t('app', 'Select...')
                                ]
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?=$form->field($commentForm, 'comment')->textarea(['rows' => 2]); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div style="margin-top: 15px">
                        <div class="form-group">
                            <?= Html::submitButton(
                                Yii::t('app', 'Save'),
                                [
                                    'class' => 'btn btn-success btn-flat',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Do you really want to cancel the {entity}?', ['entity' => Yii::t('app', 'variation')]),
                                        'method' => 'post',
                                    ]
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
<?php \yii\bootstrap\Modal::end() ?>




