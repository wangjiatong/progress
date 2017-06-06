<?php

namespace app\models\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "unread_message".
 *
 * @property integer $id
 * @property integer $progress_id
 * @property integer $user_id
 * @property integer $status
 * @property varchar $created_at
 */
class UnreadMessage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unread_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['id', 'progress_id', 'user_id'], 'required'],
//             [['id', 'progress_id', 'user_id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'progress_id' => 'Progress ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'created_at' => 'created_at',
        ];
    }
}
