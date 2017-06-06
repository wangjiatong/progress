<?php

namespace app\models\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $project_name
 * @property string $project_content
 * @property integer $starter
 * @property string $partner
 * @property string $created_at
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name', 'project_content', 'starter', 'partner', 'created_at'], 'required'],
            [['project_content'], 'string'],
            [['starter'], 'integer'],
            [['project_name', 'created_at'], 'string', 'max' => 30],
            [['partner'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_name' => 'Project Name',
            'project_content' => 'Project Content',
            'starter' => 'Starter',
            'partner' => 'Partner',
            'created_at' => 'Created At',
        ];
    }
}
