<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php foreach ($models as $m):?>
    <div class="list-group">
        <a href="project/view?id=<?=$m['id']?>" class="list-group-item">   
        <span class="badge">14</span>
        <?= $m['project_name'] ?>
        </a>
    </div>
    <?php endforeach;?>
</div>
