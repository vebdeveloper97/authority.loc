<?php


/* @var $this \yii\web\View */
/* @var $results array */

$this->title = Yii::t('app','Qabul qilingan maxsulotlar');

?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result):?>
            <tr>
                <td><?= $result['model_no'];?></td>
                <td><?= $result['size'];?></td>
                <td><?= $result['quantity'];?></td>
                <td><?= $result['model_no'];?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

