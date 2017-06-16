<?php

namespace app\models\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $passwd
 * @property string $email
 * @property string $phone
 * @property string $name
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'passwd', 'email', 'phone', 'name'], 'required'],
            [['username', 'passwd', 'email', 'phone'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 10],
            ['company', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'passwd' => '密码',
            'email' => '电子邮箱',
            'phone' => '电话',
            'name' => '姓名',
            'company' => '所属公司',
        ];
    }
    
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
//         throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
//         throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $auth_key;
//         throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->one();
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->passwd === $password;
    }
    
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    //获取账号状态
    public static function userStatus($status)
    {
        switch ($status)
        {
            case 1: return '已激活'; break;
            case 0: return '未激活'; break;
        }
    }
    //设置账号激活状态, $status = 0 或 1
    public static function setStatus($user_id, $status)
    {
        $user = User::findOne($user_id);
        //如果用户本身的状态和要设置的状态不一样
        if($user->status !== $status)
        {
            $user->status = $status;
            return $user->save();
        }
    }
}
