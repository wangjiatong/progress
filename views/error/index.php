<?php
use yii\helpers\Html;
?>

<h4>错误发生！<small>如果您认为这是系统问题，请与管理员取得联系。</small></h4>
<p><?= Html::a('点击这里', Yii::$app->request->referrer)?>，返回到之前所在页面。</p>