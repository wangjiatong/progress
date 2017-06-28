<?php

namespace app\models\models;

use Yii;
use yii\db\ActiveRecord;
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
class Project extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['project_name', 'project_content'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name', 'project_content', 'partner'], 'required'],
            [['project_content'], 'string', 'on' => ['update']],
            ['project_name', 'string', 'max' => 30, 'on' => ['update']],
            [['project_name', 'project_content'], 'required', 'on' => 'update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_name' => '项目名称',
            'project_content' => '项目内容',
            'starter' => '发起人',
            'partner' => '参与人',
            'created_at' => '创建时间',
        ];
    }
    //更改项目状态，$id：项目id，$status：项目状态，默认为1进项中，0为结束，2为立项成功
    public static function setStatus($id, $status)
    {
        if($project = self::findOne($id))
        {
            $project->status = $status;
            if($project->save())
            {
                return true;
            }
            return false;
        }
        return false;
    }
    //根据项目状态显示文字提示
    public function getStatus()
    {
        switch ($this->status)
        {
            case 0: return '已结束'; break;
            case 1: return '立项中'; break;
            case 2: return '进行中'; break;
        }
    }
    
}
