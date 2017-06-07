<?php
use app\models\models\User;
use yii\redactor\widgets\Redactor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\models\models\UnreadMessage;
use app\models\models\Progress;
$user_ids = explode(', ', $model->partner);
?>
<div class="panel panel-info">
  <div class="panel-heading">项目名称：<?= $model->project_name ?></div>
  <div class="panel-body">
    <?= $model->project_content ?>
  </div>
    <ul class="list-group">
        <li class="list-group-item">发起人：<?= User::findOne($model->starter)->name ?></li>
        <li class="list-group-item">参与人：
            <?php foreach ($user_ids as $u){
                echo User::findOne($u)->name . " ";
            }?>
        </li>
        <li class="list-group-item">立项时间：<?= $model->created_at ?></li>
<!--         <li class="list-group-item">Porta ac consectetur ac</li> -->
<!--         <li class="list-group-item">Vestibulum at eros</li> -->
    </ul>
  <div class="panel-footer">最新进展：
  </div>
        <?php
            if($progress_ids = UnreadMessage::find()->where(['project_id' => $model->id, 'status' => 0, 'user_id' => Yii::$app->user->identity->id])->orderBy('created_at desc')->asArray()->all())
            {
                foreach ($progress_ids as $p)
                {
                    $progress = Progress::find()->where(['id' => $p['progress_id']])->asArray()->one();
        ?>
                <div class="list-group">
                  <a href="" class="list-group-item">
                    <h4 class="list-group-item-heading"><?= User::findOne($progress['speaker_id'])->name ?> <small><?= $progress['created_at'] ?></small></h4>
                    <p class="list-group-item-text"><?= $progress['comment'] ?></p>
                  </a>
                </div>
        <?php 
                }
            }
        ?>
</div>
  <div>
  <?php
        foreach ($progress_ids as $p)
        {
            $message = UnreadMessage::find()->where(['project_id' => $model->id, 'user_id' => Yii::$app->user->identity->id, 'progress_id' => $p['progress_id']])->one();
            $message->status = 1;
            $message->save();
        }
  ?>
  <?php $form = ActiveForm::begin() ?>
  <?= $form->field($comment, 'comment')->widget(Redactor::className()) ?>
  <?= Html::submitButton('提交') ?>
  <?php ActiveForm::end() ?>
  </div>