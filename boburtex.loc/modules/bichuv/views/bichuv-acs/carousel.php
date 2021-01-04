<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php $count = count($image); for ($i = 0; $i < $count; $i++){?>
        <li data-target="#carousel-example-generic" data-slide-to="<?=$i?>" class="<?=($i==0)?'active':''?>"></li>
        <?php }?>
    </ol>
    <div class="carousel-inner" role="listbox">
        <?php $count = count($image); for ($i = 0; $i < $count; $i++){?>
        <div class="item <?=($i==0)?'active':''?>">
            <img src="<?=$image[$i]['path']?>" alt="Img">
            <div class="carousel-caption">
                <?=$model->name?>
                <a href="<?=Yii::$app->urlManager->createUrl(['bichuv/bichuv-acs/delete-image','id'=>$image[$i]['id']])?>" style="z-index: 999999" class="deleteImg"><i class="fa fa-trash"></i></a>
            </div>
        </div>
        <?php }?>
    </div>
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a>
</div>