<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
// use yii\bootstrap\Modal;
// use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use app\models\models\User;
?>
<ol class="breadcrumb">
  <li><a href="#">项目</a></li>
  <li class="active">发起项目</li>
</ol>
<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'project_name')->textInput() ?>

<?= $form->field($model, 'project_content')->widget(Redactor::className()) ?>

<?= $form->field($model, 'partner')->checkboxList(User::find()->select(['name'])->indexBy('id')->column()); ?>

<?= Html::submitButton('提交')?>

<?php ActiveForm::end() ?>
