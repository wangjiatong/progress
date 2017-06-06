<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\models\Project;
use Yii;
use app\models\relations\ProjectUserRelations;
use app\models\relations\app\models\relations;

class ProjectForm extends Model
{
    public $project_name;
    public $project_content;
    public $starter;
    public $partner;
    public $created_at;

    public function rules()
    {
        return [
            [['project_name', 'project_content', 'partner'], 'required'],    
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'project_name' => '项目名称',
            'project_content' => '项目内容',
            'partner' => '项目参与人',    
        ];
    }
    
    public function create()
    {
        if(!$this->validate())
        {
            return null;
        }
        $project = new Project();
        $project->project_name = $this->project_name;
        $project->project_content = $this->project_content;
        $project->starter = Yii::$app->user->identity->id;
        $partner_arr = $this->partner;
        /** 
         * 如果没有勾选自己
         * 则将自己的id加入参与人id数组
        **/
        $partner_str = $partner_arr;
        if(!in_array($project->starter, $partner_arr))
        {
            $partner_str[] += $project->starter;
        }
        $partner_arr = $partner_str;
        $partner_str = implode(', ', $partner_str);
        $project->partner = $partner_str;
        $project->created_at = Date('Y-m-d');
        if($project->save())
        {
            /**
             * 遍历参与人id数组
             * 生成对应的项目-用户关系
             */
            foreach ($partner_arr as $p)
            {
                $project_user = new ProjectUserRelations();
                $project_user->project_id = $project->id;
                $project_user->user_id = $p;
                $project_user->save();
            }
            return true;
        }else{
            return false;
        }
        
    }
    
    
    
    
    
    
    
    
}