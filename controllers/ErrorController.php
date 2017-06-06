<?php
namespace app\controllers;

use app\controllers\common\BaseController;

class ErrorController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}