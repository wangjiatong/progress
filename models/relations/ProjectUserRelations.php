<?php

namespace app\models\relations;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project_user".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 */
class ProjectUserRelations extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_user';
    }

    /**
     * @inheritdoc
     */
//     public function rules()
//     {
//         return [
//             [['project_id', 'user_id'], 'required'],
//             [['project_id', 'user_id'], 'integer'],
//         ];
//     }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'user_id' => 'User ID',
        ];
    }
}
