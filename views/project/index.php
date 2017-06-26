<?php
use app\models\relations\NewProjectRelations;



?>
<ol class="breadcrumb">
  <li><a href="#">项目</a></li>
  <li class="active">查看项目</li>
</ol>
<div class="list-group">
    <?php foreach($models as $m):?>
        <a href="detail?id=<?= $m['id'] ?>" class="list-group-item">
        <?= $m['project_name'] ?> 
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <?php 
                if(NewProjectRelations::find()
                ->where(['project_id' => $m['id'], 'user_id' => Yii::$app->user->identity->id, 'is_new' => 1])
                ->one()){
                    echo "<span class='badge'>新</span>";    
                }
            ?>
        </a>
    <?php endforeach;?>
</div>