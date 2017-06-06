<?php
namespace app\controllers;

use app\controllers\common\BaseController;
use app\models\forms\ProjectForm;
use Yii;
use app\models\models\Project;
use app\models\relations\ProjectUserRelations;
use app\models\models\Progress;
use app\models\models\UnreadMessage;
use app\models\models\app\models\models;
use yii\bootstrap\Alert;

class ProjectController extends BaseController
{
//     public function actionIndex()
//     {
//         $my_id = Yii::$app->user->identity->id;
//         if($projects = ProjectUserRelations::find()->where(['user_id' => $my_id])->all())
//         {
//             return $this->render('index', [
//                 'model' => $projects,
//             ]);
//         }
//         return $this->redirect(['error/index']);
//     }
    
    public function actionCreate()
    {
        $model = new ProjectForm();    
        if($model->load(Yii::$app->request->post()) && $model->create())
        {
            return $this->redirect(['site/index']);
        }
           return $this->render('create', [
                'model' => $model,
            ]);

    }
    
    public function actionView($id)
    {
        //获取用于显示项目页面的模型
        $model = $this->findMyModel($id);
        //生成用于项目留言的模型
        $comment = new Progress();
        //判断POST
        if($comment->load(Yii::$app->request->post()))
        {
            $comment->project_id = $id;
            $comment->speaker_id = Yii::$app->user->identity->id;
            $comment->created_at = Date('Y-m-d H:i:s');
            /*
             * 如果项目进展保存成功
             * 则为每个参与人生成未读消息模型
             */
            if($comment->save())
            {
                $partner = Project::findOne($id)->partner;
                $partner = explode(', ', $partner);
                foreach ($partner as $p)
                {
                    $unread_message = new UnreadMessage();
                    $unread_message->project_id = $id;
                    $unread_message->progress_id = $comment->id;
                    $unread_message->user_id = $p;
                    $unread_message->status = 0;
                    $unread_message->created_at = Date('Y-m-d H:i:s');
                    $unread_message->save();
                    
                }
                return $this->refresh();
            }
        }
        return $this->render('view', [
            'model' => $model,
            'comment' => $comment,
        ]);
    }
    
    
    
    
    
    
    
    public function findMyModel($id)
    {
//         if(!($project = Project::find()->where(['id' => $id])->one()))
//         {
//             return $this->redirect(['error/index']);
//         }
//         $partner_str = $project->partner;
//         $partner_arr = explode(', ', $partner_str);
//         $my_id = Yii::$app->user->identity->id;
//         if(in_array($my_id, $partner_arr))
//         {
//             return $project;
//         }else{
//             return $this->redirect(['error/index']);
//         }
        
        
        
        $my_id = Yii::$app->user->identity->id;
        if($project_user_relations = ProjectUserRelations::find()->where(['user_id' => $my_id, 'project_id' => $id])->one())
        {
            return Project::findOne($project_user_relations->project_id);
        }
        return $this->redirect(['error/index']);
    }
    
}