<?php
if (!empty($attachments)):?>
    <div class="row">
        <?php foreach ($attachments as $attachment):?>
            <div class="col-md-2">
                <img src="<?=$attachment['path']?>" class="img-thumbnail" alt="<?=$attachment['name']?>">
            </div>
        <?php endforeach;?>
    </div>
<?php endif;?>