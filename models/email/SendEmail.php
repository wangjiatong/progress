<?php
namespace app\models\email;

use yii\base\Model;
use Yii;

class SendMail extends Model
{   
    public static function sendEmail($to, $subject, $body)
    {
        $mail = Yii::$app->mailer->compose();
        
        $mail->setTo($to);
        
        $mail->setSubject($subject);
        
        $mail->setFrom(['mail@ewinjade.com' => '翌银玖德']);
        
        $mail->setHtmlBody($body);
        
        $mail->send();
    }
}