<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\models\User;

class SignupForm extends Model
{
    public $username;
    public $passwd;
    public $email;
    public $phone;
    public $name;
    
    public function rules()
    {
        return [
            ['username', 'unique', 'targetClass' => 'app\models\models\User'],
            [['username', 'passwd', 'email', 'phone', 'name'], 'required', 'message' => '该项为必填！'],
            [['username', 'passwd', 'email', 'phone'], 'string', 'max' => 30, 'tooLong' => '最多为30个字符！'],
            [['name'], 'string', 'max' => 10, 'tooLong' => '最多为10个字符！'],    
            ['email', 'email', 'message' => '邮箱格式错误！'],
            ['email', 'trim'],
            ['email', 'unique', 'targetClass' => 'app\models\models\User'],
            ['phone', 'unique', 'targetClass' => 'app\models\models\User'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'passwd' => '密码',
            'email' => '电子邮箱',
            'phone' => '电话号码',
            'name' => '姓名',  
        ];
    }
    
    public function signup()
    {
        if(!$this->validate())
        {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->passwd = $this->passwd;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->name = $this->name;
        return $user->save() ? true : false;
    }
    
}