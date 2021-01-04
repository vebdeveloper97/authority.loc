<?php

use app\modules\hr\models\HrDepartmentResponsiblePerson;
use app\modules\hr\models\HrDepartmentsInfo;
use app\modules\hr\models\HrOrganizationInfo;
use kartik\form\ActiveForm;
use kartik\tree\Module;
use kartik\tree\TreeView;
use kartik\tree\models\Tree;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Tree $node
 * @var HrDepartmentsInfo $infoModel
 * @var \app\modules\hr\models\UploadForm $uploadForm
 * @var ActiveForm $form
 * @var array $formOptions
 * @var string $keyAttribute
 * @var string $nameAttribute
 * @var string $iconAttribute
 * @var string $iconTypeAttribute
 * @var array|string $iconsList
 * @var string $formAction
 * @var array $breadcrumbs
 * @var array $nodeAddlViews
 * @var mixed $currUrl
 * @var boolean $isAdmin
 * @var boolean $showIDAttribute
 * @var boolean $showNameAttribute
 * @var boolean $showFormButtons
 * @var boolean $allowNewRoots
 * @var string $nodeSelected
 * @var string $nodeTitle
 * @var string $nodeTitlePlural
 * @var array $params
 * @var string $keyField
 * @var string $nodeView
 * @var string $nodeAddlViews
 * @var array $nodeViewButtonLabels
 * @var string $noNodesMessage
 * @var boolean $softDelete
 * @var string $modelClass
 * @var string $defaultBtnCss
 * @var string $treeManageHash
 * @var string $treeSaveHash
 * @var string $treeRemoveHash
 * @var string $treeMoveHash
 * @var string $hideCssClass
 */

?>

<div class="row">
    <div class="col-sm-12">
        <?= $form->field($node, 'token')->textInput(); ?>
    </div>
</div>

<?php if ($infoModel !== null): ?>
    <?php if ($infoModel instanceof HrDepartmentsInfo): ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'tel')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'address')->textarea(['rows' => 4]) ?>
            </div>
        </div>
    <?php elseif ($infoModel instanceof HrOrganizationInfo): ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'tel')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'address')->textarea(['rows' => 4]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($infoModel, 'add_info')->textarea(['rows' => 4]) ?>
            </div>
        </div>
        <?php if (false): ?>
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($uploadForm, 'file')->widget(FileInput::classname(), [
//                'options' => ['accept' => 'image/*'],
                    ]); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<?php
/** Ma'sul shaxslar ro'yxati */
$responsiblePersons = null;
if (isset($node->id)) {
    $query = HrDepartmentResponsiblePerson::find();
    $query->joinWith(['hrEmployee' => function($q) {
        $q->select(['id', 'fish']);
    }]);
    /*$query->joinWith(['hrDepartment' => function($q) {
        $q->select(['id', 'name']);
    }]);*/
    $query->andWhere(['hr_department_id' => $node->id]);
    $query->addOrderBy(['hr_department_id' => SORT_ASC, 'start_date' => SORT_DESC]);

    $responsiblePersons = $query->all();
}

?>

<?php if ($responsiblePersons): ?>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <th>#</th>
                    <th><?= Yii::t('app', 'Responsible person') ?></th>
                    <th><?= Yii::t('app', 'Date of appointment') ?></th>
                    <th><?= Yii::t('app', 'End date') ?></th>
                    <th><?= Yii::t('app', 'Status') ?></th>
                </thead>
                <?php $cnt = 1; ?>
                <?php foreach ($responsiblePersons as $responsiblePerson): ?>
                    <tr class="<?= $responsiblePerson->end_date == null ? 'success' : '' ?>">
                        <td><?= $cnt++?></td>
                        <td><?= $responsiblePerson->hrEmployee->fish ?></td>
                        <td><?= $responsiblePerson->start_date ?></td>
                        <td><?= $responsiblePerson->end_date ?></td>
                        <td><?= HrDepartmentResponsiblePerson::getStatusList($responsiblePerson->status) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php endif; ?>
