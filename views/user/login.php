<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\controllers\common\BaseController;

$this->title = '登录';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-login">
    <ol class="breadcrumb">
      <li class="active">登录</li>
    </ol>
    <h1><?= Html::encode($this->title) ?></h1>
    <!-- 注册成功后跳转登录页提示注册成功 -->
    <?= BaseController::displaySessionMessage('signup_success') ?>
    <!-- 如果账号未激活，则提示 -->
    <?= BaseController::displaySessionMessage('user_is_not_active', 'danger') ?>
    <p></p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'passwd')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>
        <div class="col-lg-offset-1">
            <p>还没有账号？<?= Html::a('点击注册', ['user/signup']) ?></p>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
    </div>
</div>
