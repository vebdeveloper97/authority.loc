<?php
/** @var $this \yii\web\View */
/** @var $skills \app\modules\hr\models\EmployeeRelSkills[] */

use app\modules\hr\models\HrEmployeeSkills;

if(empty($work) && empty($study)){
        ?>
        <p class="alert-alert-warning"><?=Yii::t('app', "Malumotlar mavjud emas!")?></p>
        <?php
    }
    else{
        ?>
        <div class="row">
            <div class="col-sm-6">
                <?php if(!empty($study)): ?>
                    <table class="table table-bordered">
                        <thead>
                        <th>N:</th><th><?=Yii::t('app','Place of study')?></th><th><?=Yii::t('app', 'Duration')?></th><th><?=Yii::t('app', 'Level')?></th>
                        </thead>
                        <tbody>
                        <?php foreach ($study as $item): ?>
                            <tr>
                                <td><?=$item['id']?></td>
                                <td><?=$item['where_studied']?></td>
                                <td><?='( '.$item['from'].' - '.$item['to'].' )'?></td>
                                <td><?=$item->studyDegree->name?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>

                <?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if(!empty($work)): ?>
                    <table class="table table-bordered">
                        <thead>
                        <th>N:</th><th><?=Yii::t('app','Previously worked places')?></th><th><?=Yii::t('app', 'Duration')?></th><th><?=Yii::t('app', 'Level')?></th>
                        </thead>
                        <tbody>
                        <?php foreach ($work as $item): ?>
                            <?php
                            $data = $item['to'];
                            if(empty($item['to'])){
                                $data = "<span style='color: red;'>".Yii::t('app', 'hozirda ishlayabdi')."</span>";
                            }
                            ?>
                            <tr>
                                <td><?=$item['id']?></td>
                                <td><?=$item['organization']?></td>
                                <td><?='( '.$item['from'].' - '.$data.' )'?></td>
                                <td><?=$item['position']?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>

                <?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if(!empty($skills)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <th>N:</th>
                            <th><?=Yii::t('app','Employee skills')?></th>
                            <th><?=Yii::t('app', 'Rate')?></th>
                            <th><?=Yii::t('app', 'Add Info')?></th>
                        </thead>
                        <tbody>
                        <?php
                        $cnt = 1
                        ?>
                        <?php foreach ($skills as $item): ?>

                            <tr>
                                <td><?= $cnt++ ?></td>
                                <td><?= HrEmployeeSkills::getSkillById($item['employee_skills_id'])?></td>
                                <td><?=$item['rate'].'%'?></td>
                                <td><?=$item['add_info']?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>

                <?php endif; ?>
            </div>
        </div>

        <?php
    }
    ?>