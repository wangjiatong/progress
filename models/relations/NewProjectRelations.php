<?php

namespace app\models\relations;

use Yii;

/**
 * This is the model class for table "new_project".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $is_new
 */
class NewProjectRelations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'new_project';
    }

    /**
     * @inheritdoc
     */
//     public function rules()
//     {
//         return [
//             [['project_id', 'user_id', 'is_new'], 'required'],
//             [['project_id', 'user_id', 'is_new'], 'integer'],
//         ];
//     }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '新项目-用户 主键',
            'project_id' => '新项目id',
            'user_id' => '参与人id',
            'is_new' => '是否为新项目 1=>是，0=>否',
        ];
    }
}
