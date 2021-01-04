<?php
/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $info app\models\UsersInfo */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
        'info' => $info,
    ]) ?>

</div>
