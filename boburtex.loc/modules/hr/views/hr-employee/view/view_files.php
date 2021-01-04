<?php
?>


<div class="row">
<?php foreach ($attachment as $item):?>
    <?php if($item['extension'] == 'png' || $item['extension'] == 'jpg' || $item['extension'] == 'jpeg' || $item['extension'] == 'gif' ):?>
    <div class="col-lg-3">
        <img src='/web/<?=$item['path']?>' title=<?=$item['name']?> class='thumbnail imgPreview round' style='width:100%;'>
        <br>
    </div>
    <?php elseif($item['extension'] == 'docx' || $item['extension'] == 'doc'|| $item['extension'] == 'xls'|| $item['extension'] == 'xlsx'|| $item['extension'] == 'pdf'|| $item['extension'] == 'ppt'):?>
        <div class="col-lg-3 doc_block thumbnail">
            <span><?=$item['name']?></span><br>
            <a class="btn btn-info" href='/web/<?=$item['path']?>''><?=Yii::t('app','Ko\'chirib olish')?></a>
            <br>
        </div>
    <?php endif;?>
<?php endforeach;?>
</div>

<?php
$css = <<<CSS
.doc_block{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
CSS;
$this->registerCss($css);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>