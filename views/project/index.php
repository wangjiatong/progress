<?php




?>
<ol class="breadcrumb">
  <li><a href="#">项目</a></li>
  <li class="active">查看项目</li>
</ol>
<div class="list-group">
    <?php foreach($models as $m):?>
        <a href="detail?id=<?= $m['id'] ?>" class="list-group-item"><?= $m['project_name'] ?> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
    <?php endforeach;?>
</div>