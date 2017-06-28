<?php
use yii\bootstrap\ActiveForm;
use yii\redactor\widgets\Redactor;
use yii\bootstrap\Html;
use app\models\models\User;
use yii\bootstrap\Alert;
use app\controllers\common\BaseController;

$partner_arr = explode(', ', $model->partner);
$color_arr = ['primary', 'success', 'info', 'warning', 'danger'];
//项目状态按钮颜色
function buttonColor($status)
{
    switch ($status)
    {
        case 0: return 'warning'; break;
        case 1: return 'info'; break;
        case 2: return 'success'; break;
    }
}
?>
<div class="page-header">
    <h3><?= $model->project_name ?>
<!--         <small>项目状态：</small> -->
        <!-- 项目状态 开始 -->
        <div class="btn-group">
          <button type="button" class="btn btn-<?= buttonColor($model->status) ?> dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $model->getStatus() ?> <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><?= Html::a('立项中', ['project/set-status', 'id' => $model->id, 'status' => 1], ['data' => ['confirm' => "你确定要更改项目状态为[立项中]吗？"]])?></li>
            <li><?= Html::a('进行中', ['project/set-status', 'id' => $model->id, 'status' => 2], ['data' => ['confirm' => "你确定要更改项目状态为[进行中]吗？"]])?></li>
            <li><?= Html::a('已结束', ['project/set-status', 'id' => $model->id, 'status' => 0], ['data' => ['confirm' => "你确定要更改项目状态为[已结束]吗？"]])?></li>
          </ul>
        </div>
        <!-- 项目状态 结束 -->
    </h3>
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
        <?= BaseController::displaySessionMessage('project_status_changed') ?>
        <?= $model->project_content ?>
        <br/>
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
        <?php $i = 0; ?>
        <?php foreach ($progresses as $progress): ?>
                <?php 
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
        <?= Html::a('管理参与人', ['project/partner', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => '你确定要编辑参与人吗？',
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