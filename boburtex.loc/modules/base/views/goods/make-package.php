<?php

use app\assets\ReactAsset;

$this->title = Yii::t('app', "Qadoq tayyorlash");
ReactAsset::register($this);
?>

    <div id="root">
    </div>
<?php
$this->registerJs("
    $('body').delegate('.editable-custom','blur', function(e){
        let content = $(this).text(); 
        let clName = $(this).attr('data-cl'); 
        let all = $('#root').find('.'+clName); 
        if(all){ 
            all.map(function(key, val){ 
                $(this).text(content); 
            });
        }
    });
");
?>