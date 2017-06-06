<?php

namespace app\models\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "progress".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $speaker_id
 * @property string $comment
 * @property string $created_at
 */
class Progress extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'progress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['id', 'pro_id', 'speaker_id', 'comment', 'created_at'], 'required'],
//             [['id', 'pro_id', 'speaker_id'], 'integer'],
            [['comment'], 'string'],
//             [['created_at'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => '项目名称',
            'speaker_id' => '汇报人',
            'comment' => '项目进展',
            'created_at' => '创建时间',
        ];
    }
}
