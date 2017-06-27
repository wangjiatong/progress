<?php
use yii\helpers\Html;
use app\controllers\common\BaseController;
?>
<?= BaseController::displaySessionMessage('user_is_not_admin', 'danger') ?>
<!-- <h4>错误发生！<small>如果您认为这是系统问题，请与管理员取得联系。</small></h4> -->
<p><?= Html::a('点击这里', ['site/index'])?>，返回首页。</p>