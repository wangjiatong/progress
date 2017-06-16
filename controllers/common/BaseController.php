<?php
namespace app\controllers\common;

use yii\web\Controller;
use Yii;
use app\models\models\User;
use yii\bootstrap\Alert;

class BaseController extends Controller
{
    //判断当前用户是否为管理员
    public function isAdmin()
    {
        $is_admin = User::findOne(Yii::$app->user->identity->id)->is_admin;
        if($is_admin ==1)
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
    public static function displaySessionMessage($code)
    {
        if(Yii::$app->getSession()->hasFlash($code))
        {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-success', //这里是提示框的class
                ],
                'body' => Yii::$app->getSession()->getFlash($code), //消息体
            ]);
        }
    }
    
    
    
    
    
    
    
}