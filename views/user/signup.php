<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;



$this->title = '用户注册';
?>
<div class="user-signup">
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <h1><?= Html::encode($this->title) ?></h1>
            <p>请填写如下信息完成注册：</p>
        </div>
    </div>
    
    <?php $form = ActiveForm::begin() ?>
    
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <?= $form->field($model, 'username')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <?= $form->field($model, 'passwd')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <?= $form->field($model, 'phone')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-4">
            <?= Html::submitButton('注册', ['class' => 'btn btn-primary btn-block']) ?>
        </div>
    </div>
    
    <?php ActiveForm::end() ?>
    
</div>