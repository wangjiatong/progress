<?php
namespace app\controllers;

use app\controllers\common\BaseController;
use app\models\forms\SignupForm;
use Yii;
use app\models\forms\LoginForm;

class UserController extends BaseController
{
    public function actionSignup()
    {
        $model = new SignupForm();
        if($model->load(Yii::$app->request->post()) && $model->signup())
        {
            return parent::goHome();
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionLogin()
    {
        if(!Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login())
        {
            return $this->goHome();
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
}