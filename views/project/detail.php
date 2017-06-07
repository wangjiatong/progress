<?php
use yii\bootstrap\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\bootstrap\Html;
use app\models\models\User;

$partner_arr = explode(', ', $model->partner);
$color_arr = ['primary', 'success', 'info', 'warning', 'danger'];
?>
<div class="page-header">
    <h3><?= $model->project_name ?> <small></small></h3>
</div>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">项目详情</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">项目信息</a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">进度汇报</a></li>
    <!-- 菜单选项栏中的操作按钮  开始-->
    <?php if($model->starter == Yii::$app->user->identity->id): ?>
    <div class="btn-group">
      <button type="button" class="btn btn-info">操作</button>
      <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="update?id=<?= $model->id ?>">修改</a></li>
        <li><a href="delete?id=<?= $model->id ?>">删除</a></li>
<!--         <li><a href="#">Something else here</a></li> -->
<!--         <li role="separator" class="divider"></li> -->
<!--         <li><a href="#">Separated link</a></li> -->
      </ul>
    </div>
    <?php endif; ?>
    <!-- 菜单选项栏中的操作按钮  结束-->
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
        <br />
        <?= $model->project_content ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
        <table class="table">
            <tr><td>发起人：</td><td><?= User::findOne($model->starter)->name ?></td></tr>
            <tr><td>发起时间：</td><td><?= $model->created_at ?></td></tr>
            <tr><td> 参与人：</td><td>    
                <?php     
                    foreach ($partner_arr as $p)
                    {
                        $name = User::findOne($p)->name;
                        $partner_str = $name . " ";
                        echo $partner_str;
                    }
                ?>
            </td></tr>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="messages">
        <?php foreach ($progresses as $progress): ?>
                <?php 
                    $i = 0;
                    if($i == 4)
                    {
                        $i = 0;
                    }
                ?>
                <div class="panel panel-<?= $color_arr[$i] ?>">
                    <div class="panel-heading"><?= User::findOne($progress['speaker_id'])->name ?><?= $progress['created_at'] ?></div>
                        <div class="panel-body">
                            <?= $progress['comment'] ?>
                        </div>
                </div>
            <?php 
                $i++;
            ?>
            
            
        <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- 汇报表单 -->
<?php $form = ActiveForm::begin() ?>
<?= $form->field($comment, 'comment')->widget(Redactor::className()) ?>
<?= Html::submitButton('提交') ?>
<?php ActiveForm::end() ?>