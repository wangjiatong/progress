<?php

/* @var $this yii\web\View */
use app\models\models\UnreadMessage;
$this->title = 'My Yii Application';
?>
<div class="site-index">
    <ol class="breadcrumb">
      <li class="active">首页</a></li>
    </ol>
    <?php foreach ($models as $m): ?>
    <?php $message_number = count(UnreadMessage::find()->where(['project_id' =>$m['id'], 'user_id' => Yii::$app->user->identity->id, 'status' => 0])->asArray()->all());//接收新消息数 ?>
    <?php if($message_number !== 0){?>
    <div class="list-group">
        <a href="project/view?id=<?=$m['id']?>" class="list-group-item">   
        <span class="badge"><?= $message_number ?></span><!-- 显示新消息条数 -->
        <?= $m['project_name'] ?>
        </a>
    </div>
    <?php } ?>
    <?php endforeach;?>
    <div class="list-group">
      <a href="project/create" class="list-group-item">发起项目 <span class="glyphicon glyphicon-pencil" aria-hidden="true"></a>
      <a href="project/index" class="list-group-item">查看项目 <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>
      <a href="project/mime" class="list-group-item">我的项目 <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></a>
    </div>
</div>
