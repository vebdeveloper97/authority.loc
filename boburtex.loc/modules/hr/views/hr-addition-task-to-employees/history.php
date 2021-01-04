<?php
/**
 * @var $results
 */
?>
<?php foreach ($models as $model): ?>
    <div class="box box-default box-solid">
        <div class="box-header">
            <h5><?= $model->hrEmployee->fish.":".$model['reg_date'] ?></h5>
        </div>
        <div class="box-body">

            <table class="responstable table-bordered">
                <tr>
                    <th>№</th>
                    <th><?= Yii::t('app', 'Assigned tasks') ?></th>
                    <th><?= Yii::t('app', 'Done') ?></th>
                </tr>
                <?php
                foreach ($model->hrAdditionTaskItems as $index => $item):?>
                    <tr>
                        <td>
                            <?= ++$index; ?>
                        </td>
                        <td>
                            <?= $item['task'] ?>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                     style="width: <?= $item['rate'] ?>%;"><?= $item['rate'] ?>%
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tr>
                <?php endforeach; ?>

            </table>
        </div>
    </div>
<?php endforeach; ?>
<?php
$this->registerCssFile("/css/my_table.css");
$this->registerCss("
table{
    border-collapse: inherit;
   
}
.modal-body{
    max-height: 80vh;
    overflow-y: scroll;
}
.progress-bar{
    background: #01A659;
}
.progress{
    background: #E3E3E3;
    margin: 0;
}
");
?>
