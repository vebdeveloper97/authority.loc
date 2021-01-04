<?php
    /** @var $post app\models\Post */
?>

<?php if(count($post)): ?>
    <?php foreach($post as $key => $row): ?>
        <div class="col-lg-3">
            <h3><?=$row['title']?></h3>
            <p><?=$row['content']?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
