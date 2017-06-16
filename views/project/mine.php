<?php
use yii\bootstrap\Alert;


?>
<ol class="breadcrumb">
  <li><a href="/project/index">项目</a></li>
  <li class="active">我的项目</li>
</ol>
<?php 
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
?>
<div class="list-group">
    <?php foreach($model as $m):?>
        <a href="detail?id=<?= $m['id'] ?>" class="list-group-item"><?= $m['project_name'] ?> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
    <?php endforeach;?>
</div>
