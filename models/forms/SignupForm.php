<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\models\User;
use app\models\relations\UserCompanyRelations;
use yii\captcha\Captcha;

class SignupForm extends Model
{
    public $username;
    public $passwd;
    public $email;
    public $phone;
    public $name;
    public $company;
    public $verifyCode;
    
    public function rules()
    {
        return [
            ['username', 'unique', 'targetClass' => 'app\models\models\User', 'message' => '该账号已被占用！'],
            [['username', 'passwd', 'email', 'phone', 'name'], 'required', 'message' => '该项为必填！'],
            [['username', 'passwd', 'email', 'phone'], 'string', 'max' => 30, 'tooLong' => '最多为30个字符！'],
            [['name'], 'string', 'max' => 10, 'tooLong' => '最多为10个字符！'],    
            ['email', 'email', 'message' => '邮箱格式错误！'],
            ['email', 'trim'],
            ['email', 'unique', 'targetClass' => 'app\models\models\User', 'message' => '该邮箱已被占用！'],
            ['phone', 'unique', 'targetClass' => 'app\models\models\User', 'message' => '该电话号码已被占用！'],
            ['company', 'required'],
            ['verifyCode', 'captcha', 'message' => '验证码错误！'],
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
            'company' => '所属公司',
            'verifyCode' => '验证码',
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
        $user->is_admin = 0;
        $user->status = 0;
        $user->company = $this->company;
        $user->generateAuthKey();
        if($user->save())
        {
            $user_company = new UserCompanyRelations();
            $user_company->user_id = $user->id;
            $user_company->company_id = $user->company;
            if($user_company->save())
            {
                return true;
            }
        }
        return false;
//         return $user->save() ? true : false;
    }
    
}