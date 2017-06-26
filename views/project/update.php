<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\redactor\widgets\Redactor;
?>
<ol class="breadcrumb">
  <li><a href="#">项目</a></li>
  <li class="active">修改项目</li>
</ol>
<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'project_name')->textInput() ?>

<?= $form->field($model, 'project_content')->widget(Redactor::className()) ?>

<?= Html::submitButton('提交', ['class' => 'btn btn-primary'])?>

<?= Html::a('取消', Yii::$app->request->referrer, [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => '你确定要取消修改该项目吗？',
    ],
])?>

<?php ActiveForm::end() ?>
