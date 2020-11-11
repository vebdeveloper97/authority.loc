<?php
    use yii\helpers\Url;
    use yii\helpers\Html;

    /* @var $message \app\modules\admin\models\MessageUz */
?>
<div class="col-lg-8">
      <h4 align="center"><?=$message['title']?></h4>
    <p align="justify">
        <strong>
            <smal><?=$message['content']?></smal>
        </strong>
    </p>
    <p>
        <strong>
            <date><i class="glyphicon glyphicon-calendar"></i> <small><?=$message['date']?> <?=$message['author']?></small></date>
        </strong>
    </p>
    <p>
        <?php
            $attachments = \app\modules\admin\models\MessageAttachmentsUz::find()
                ->where(['message_id' => $message['id']])
                ->all();
            if(!empty($attachments)){
                foreach ($attachments as $k => $val){
                    $attach = \app\modules\admin\models\Attachments::findOne($val['attachments_id']);
                    ?>
                    <div class="col-lg-4">
                        <img src="<?=$attach->path?>" class="thumbnail" style="width: 200px; height: 150px" alt="">
                    </div>
                    <?php
                }
            }
            else{

            }
        ?>
    </p>
</div>