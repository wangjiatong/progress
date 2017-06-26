<?php
namespace app\models\behaviors;
use yii\base\Model;
use app\models\models\User;
use Yii;


class ValidateAccountStatus extends Model
{
    public static function isActive()
    {
        $status = User::findOne(Yii::$app->user->identity->id)->status;
        if($status == 1)
        {
            return true;
        }
        return false;
    }
}