<?php
namespace app\controllers\common;

use yii\web\Controller;
use Yii;
use app\models\models\User;
use yii\bootstrap\Alert;
use yii\filters\AccessControl;
use app\models\behaviors\ValidateAccountStatus;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access'=> [
                'class' => AccessControl::className(),
//                 'denyCallback' => function ($rule, $action) {
//                     //因为虽然未通过激活验证，但账号已是登录态所以要先注销
//                     if(!Yii::$app->user->isGuest)
//                     {
//                         Yii::$app->user->logout();
//                         $this->sendSessionMessage('user_is_not_active', '您的账号尚未激活，暂无法使用！请联系管理员为您开通。');
//                         return $this->redirect(['user/login']);
//                     }
//                 },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action){
                            return ValidateAccountStatus::isActive();    
                        },
                        'denyCallback' => function ($rule, $action) {
                        //因为虽然未通过激活验证，但账号已是登录态所以要先注销
                            if(!Yii::$app->user->isGuest)
                            {
                                Yii::$app->user->logout();
                                $this->sendSessionMessage('user_is_not_active', '您的账号尚未激活，暂无法使用！请联系管理员为您开通。');
                                return $this->redirect(['user/login']);
                            }
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['captcha'],
                        'roles' => ['?'],    
                    ],
                    [
                        'allow' => false,
                        'actions' => ['index'],
                        'roles' => ['?'],    
                    ],
                ],
            ],
        ];
    }
    //判断当前用户是否为管理员
    public function isAdmin()
    {
        $is_admin = User::findOne(Yii::$app->user->identity->id)->is_admin;
        if($is_admin == 1)
        {
            return true;
        }else{
            return false;
        }
    }
    //当确定某数组中含有某值时，删除该值,并返回删除该值后的数组
    public function in_array_delete($value, $array)
    {
        $res = [];        
        foreach($array as $k => $v)
        {
            if($v == $value)
            {
                unset($array[$k]);
                continue;
            }
            $res[$k] = $v;
        }
        return $res;
    }
    //写入session操作状态
    public function sendSessionMessage($code, $message)
    {
        Yii::$app->getSession()->setFlash($code, $message);
    }
    //在视图页显示session消息
    public static function displaySessionMessage($code, $class = 'success')
    {
        if(Yii::$app->getSession()->hasFlash($code))
        {
            echo Alert::widget([
                    'options' => [
                        'class' => 'alert-'.$class, //这里是提示框的class
                    ],
                    'body' => Yii::$app->getSession()->getFlash($code), //消息体
                ]);
        }
    }
    
    
    
    
    
    
    
}