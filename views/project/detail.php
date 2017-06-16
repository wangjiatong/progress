<?php
use yii\bootstrap\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\bootstrap\Html;
use app\models\models\User;
use yii\bootstrap\Alert;

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
    <li role="presentation"><a href="#manage" aria-controls="manage" role="tab" data-toggle="tab">项目管理</a></li>
    <?php endif; ?>
    <!-- 菜单选项栏中的操作按钮  结束-->
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
        <br />
        <?= $model->project_content ?>
        <br/>
        <?php 
            if( Yii::$app->getSession()->hasFlash('comment_deleted') ) {
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-success', //这里是提示框的class
                    ],
                    'body' => Yii::$app->getSession()->getFlash('comment_deleted'), //消息体
                ]);
            }
        ?>
        <?php 
            if( Yii::$app->getSession()->hasFlash('progress_added') ) {
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-success', //这里是提示框的class
                    ],
                    'body' => Yii::$app->getSession()->getFlash('progress_added'), //消息体
                ]);
            }
        ?>
        <?php 
            if( Yii::$app->getSession()->hasFlash('project_updated') ) {
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-success', //这里是提示框的class
                    ],
                    'body' => Yii::$app->getSession()->getFlash('project_updated'), //消息体
                ]);
            }
        ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
        <br />
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
        <br />
        <?php foreach ($progresses as $progress): ?>
                <?php 
                    $i = 0;
                    if($i == 4)
                    {
                        $i = 0;
                    }
                ?>
                <div class="panel panel-<?= $color_arr[$i] ?>">
                    <div class="panel-heading"><?= User::findOne($progress['speaker_id'])->name ?>&nbsp;<?= $progress['created_at'] ?></div>
                        <div class="panel-body">
                            <?= $progress['comment'] ?>
                            <br />
                            <?php 
                                if($progress['speaker_id'] == Yii::$app->user->identity->id)
                                {
                                    echo Html::a('删除', ['project/delete-my-comment', 'id' => $progress['id']], [
                                            'class' => 'btn btn-danger btn-xs',
                                            'data' => [
                                                'confirm' => '你确定要删除该进度汇报吗？',
                                                'method' => 'post',   
                                            ],
                                        ]
                                        
                                    );
                                }                              
                            ?>
                        </div>              
                </div>
            <?php 
                $i++;
            ?>
        <?php endforeach; ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="manage">
    <br />
        <?= Html::a('修改项目', ['project/update', 'id' => $model->id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '你确定要对该项目进行修改？',
                'method' => 'post',    
            ],
        ]) ?>
        <?= Html::a('删除项目', ['project/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除该项目？',
                'method' => 'post',    
            ],
        ]) ?>
    </div>
    <br />
  </div>

</div>
<!-- 汇报表单 -->
<?php $form = ActiveForm::begin() ?>
<?= $form->field($comment, 'comment')->widget(Redactor::className()) ?>
<?= Html::submitButton('提交') ?>
<?php ActiveForm::end() ?>