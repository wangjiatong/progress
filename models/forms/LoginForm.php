<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\models\User;
use Yii;

class LoginForm extends Model
{
    public $username;
    public $passwd;
    public $rememberMe = true;
    
    private $_user = false;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'passwd'], 'required', 'message' => '此项不可为空！'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['passwd', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'passwd' => '密码',   
            'rememberMe' => '保持登录',
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
    
            if (!$user || !$user->validatePassword($this->passwd)) {
                $this->addError($attribute, '用户名或密码错误！');
            }
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
    
        return $this->_user;
    }
    
    
    
    
    
    
    
}