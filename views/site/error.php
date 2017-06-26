<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\controllers\common\BaseController;

$this->title = $name;
?>
<div class="site-error">

    <?= BaseController::displaySessionMessage('project_deleted') ?>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

<!--     <p> -->
<!--         The above error occurred while the Web server was processing your request. -->
<!--     </p> -->
<!--     <p> -->
<!--         Please contact us if you think this is a server error. Thank you. -->
<!--     </p> -->

</div>
