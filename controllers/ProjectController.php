<?php
namespace app\controllers;

use app\controllers\common\BaseController;
use app\models\forms\ProjectForm;
use Yii;
use app\models\models\Project;
use app\models\relations\ProjectUserRelations;
use app\models\models\Progress;
use app\models\models\UnreadMessage;
use yii\web\NotFoundHttpException;
use app\models\models\User;
use app\models\relations\NewProjectRelations;
use app\models\email\SendEmail;

class ProjectController extends BaseController
{
    public function actionIndex()
    {
        $my_id = Yii::$app->user->identity->id;
        if($projects_id = ProjectUserRelations::find()->where(['user_id' => $my_id])->orderBy('id desc')->all())
        {
            foreach ($projects_id as $p)
            {
                $project = Project::find()->where(['id' => $p['project_id']])->asArray()->one();
                $projects[] = $project;
            }
             
            return $this->render('index', [
                'models' => $projects,
            ]);
        }
        throw new NotFoundHttpException('您尚未参与任何项目或未发起任何项目，故无法查看！');
    }
    
    public function actionCreate()
    {
        $model = new ProjectForm();
        if($model->load(Yii::$app->request->post()) && $model->create())
        {
            //项目创建成功后向参与人发通知邮件
            $partner = $model->partner;
            foreach ($partner as $p)
            {
                $user = User::findOne($p);
                $email = SendEmail::sendEmail(
                    $user->email,
                    '翌银玖德：您已参与到由['.$user->name.']发起的['.$model->project_name.']项目中。',
                    '您已参与到由['.$user->name.']发起的['.$model->project_name.']项目中。'
                );
            }
            Yii::$app->getSession()->setFlash('success', '项目创建成功！');
            return $this->redirect(['project/mine']);
        }
       return $this->render('create', [
            'model' => $model,
        ]);

    }
    
    public function actionView($id)
    {
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
             * 则为每个参与人生成未读消息模型（不包括自己）
             */
            if($comment->save())
            {
                $partner = Project::findOne($id)->partner;
                $partner = explode(', ', $partner);
                foreach ($partner as $p)
                {
                    if($p == $comment->speaker_id)
                    {
                        continue;
                    }
                    $unread_message = new UnreadMessage();
                    $unread_message->project_id = $id;
                    $unread_message->progress_id = $comment->id;
                    $unread_message->user_id = $p;
                    $unread_message->status = 0;
                    $unread_message->created_at = Date('Y-m-d H:i:s');
                    $unread_message->save();
                    
                }
                Yii::$app->getSession()->setFlash('progress_added', '提交进度汇报成功！');
                return $this->redirect(['project/detail', 'id' => $id]);
            }
        }
        return $this->render('view', [
            'model' => $this->findMyModel($id),
            'comment' => $comment,
        ]);
    }
    
    public function actionDetail($id)
    {
        //阅览过新项目后删除 新项目-用户的关系
        if($new_project = NewProjectRelations::find()->where(['project_id' => $id, 'user_id' => Yii::$app->user->identity->id])->one())
        {
            $new_project->delete();
        }
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
                    if($p == $comment->speaker_id)
                    {
                        continue;
                    }
                    $unread_message = new UnreadMessage();
                    $unread_message->project_id = $id;
                    $unread_message->progress_id = $comment->id;
                    $unread_message->user_id = $p;
                    $unread_message->status = 0;
                    $unread_message->created_at = Date('Y-m-d H:i:s');
                    $unread_message->save();
        
                }
                Yii::$app->getSession()->setFlash('progress_added', '提交进度汇报成功！');
                return $this->refresh();
            }
        }
        //取到对应项目的进度汇报
        $progresses = Progress::find()->where(['project_id' => $id])->orderBy('created_at desc')->asArray()->all();
        return $this->render('detail', [
            'model' => $this->findMyModel($id),
            'progresses' => $progresses,
            'comment' => $comment,
        ]);
    }
    //查看自己发起项目的方法
    public function actionMine()
    {
        if($project = $this->myProject())
        {
            return $this->render('mine', [
                'model' => $project,
            ]);
        }
        throw new NotFoundHttpException('您尚无任何已发起项目，故无法查看！');
    }
    //删除自己发起项目的方法
    public function actionDelete($id)
    {
        $project = Project::findOne($id);
        $starter = $project->starter;
        if($starter == Yii::$app->user->identity->id)
        {
            if($project->delete())
            {
                if(Progress::find()->where(['project_id' => $id])->one())
                {
                    Progress::deleteAll('project_id=:id', [':id' => $id]);
                }
                if(UnreadMessage::find()->where(['project_id' => $id])->one())
                {
                    UnreadMessage::deleteAll('project_id=:id', [':id' => $id]);
                }
                if(ProjectUserRelations::find()->where(['project_id' => $id])->one())
                {
                    ProjectUserRelations::deleteAll('project_id=:id', [':id' => $id]);
                }
                if(NewProjectRelations::find()->where(['project_id' => $id])->one())
                {
                    NewProjectRelations::deleteAll('project_id=:id', [':id' => $id]);
                }
                $this->sendSessionMessage('project_deleted', '项目删除成功！');
                return $this->redirect(['project/mine']);
            }else{
                throw new NotFoundHttpException('项目删除失败！');
            }
        }else{
            throw new NotFoundHttpException('该项目不是由您发起，您无权删除！');
        }
    }
    //修改自己发起项目的方法
    public function actionUpdate($id)
    {
        $model = Project::findOne($id);
        $model->scenario = 'update';
        $starter = $model->starter;
        if($model->load(Yii::$app->request->post()) && $model->update())
        {
            Yii::$app->getSession()->setFlash('project_updated', '项目修改成功！');
            return $this->redirect(['project/detail', 
                'id' => $model->id,
            ]);
        }
        if($starter == Yii::$app->user->identity->id)
        {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    //删除自己汇报的方法
    public function actionDeleteMyComment($id)
    {
        if($commentToDelete = $this->findMyComment($id))
        {
            $commentToDelete->delete();
            
            if(UnreadMessage::find()->where(['progress_id' => $id])->one())
            {
                UnreadMessage::deleteAll('progress_id=:id', [':id' => $id]);
            }
            Yii::$app->getSession()->setFlash('comment_deleted', '删除进度汇报成功！');
            
            return $this->redirect(['project/detail', 'id' => $commentToDelete['project_id']]);
        }
        throw new NotFoundHttpException('删除进度汇报失败！');
    }
    //管理参与人页面
    public function actionPartner($id)
    {
        $project = Project::findOne($id);
        if($project->starter == Yii::$app->user->identity->id)
        {       
            return $this->render('partner', [
                'project' => $project,
            ]);
        }
    }
    //删除参与人的操作
    public function actionDeletePartner($user_id, $project_id)
    {
        $project = Project::findOne($project_id);
        if($project->starter == Yii::$app->user->identity->id)
        {
            $old_partner = explode(', ', $project->partner);
            $new_partner = $this->in_array_delete($user_id, $old_partner);
            $project->partner = implode(', ', $new_partner);
            if($project->save())
            {
                if(ProjectUserRelations::deleteAll('user_id=:user_id and project_id=:project_id', [':user_id' => $user_id, ':project_id' => $project_id]) &&
                    NewProjectRelations::deleteAll('project_id=:project_id and user_id=:user_id', [':project_id' => $project_id, ':user_id' => $user_id]) &&
                    UnreadMessage::deleteAll('project_id=:project_id and user_id=:user_id', [':project_id' => $project_id, ':user_id' => $user_id]))
                {
                    $this->sendSessionMessage('partner_deleted', '删除参与人成功！');
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
            
        }
    }
    //添加参与人的页面
    public function actionPartnerAdd($id)
    {
        $project = Project::findOne($id);
        if($project->starter == Yii::$app->user->identity->id)
        {
            $oldPartner = explode(', ', Project::findOne($id)->partner);
            $allUser = User::find()->select('id')->where(['status' => 1])->all();
            $newPartner = [];
            foreach ($allUser as $a)
            {
                if(!in_array($a['id'], $oldPartner))
                {
                    $newPartner[] = $a['id'];
                }
            }
            
            if($newPartner == null)
            {
                $this->sendSessionMessage('no_new_partner', '暂无可以新增的参与人！');
                return $this->redirect(Yii::$app->request->referrer);
            }
            
            return $this->render('partnerAdd', [
                'newPartner' => $newPartner,
                'id' => $id,
            ]);
        }
    }
    //添加参与人的操作
    public function actionAddPartner($user_id, $project_id)
    {
        $project = Project::findOne($project_id);
        if($project->starter == Yii::$app->user->identity->id)
        {
            $old_partner = explode(', ', $project->partner);
            $old_partner[] = $user_id;
            $project->partner = implode(', ', $old_partner);
            if($project->save())
            {
                $project_user = new ProjectUserRelations();
                $project_user->user_id = $user_id;
                $project_user->project_id = $project_id;
                //为添加的新参与人生成新项目-用户关系
                $new_project = new NewProjectRelations();
                $new_project->project_id = $project_id;
                $new_project->user_id = $user_id;
                if($project_user->save() && $new_project->save())
                {
                    $this->sendSessionMessage('new_partner_added', '添加参与人成功！');
                    return $this->redirect(['project/partner', 'id' => $project_id]);
                }
            }
    
        }
    }
    //修改合同状态
    public function actionSetStatus($id)
    {
        
    }
    
    
    
    
    //用于判断并查找自己所参与项目的方法
    protected function findMyModel($id)
    {
        $my_id = Yii::$app->user->identity->id;
        if($project_user_relations = ProjectUserRelations::find()->where(['user_id' => $my_id, 'project_id' => $id])->one())
        {
            return Project::findOne($project_user_relations->project_id);
        }
        return $this->redirect(['error/index']);
    }
    
    //用户判断并查找自己发起项目的方法
    protected function myProject()
    {
        if($projects = Project::find()->where(['starter' => Yii::$app->user->identity->id])->orderBy('id desc')->all())
        {
            return $projects;
        }
    }
    
    protected function findMyComment($id)
    {
        if($comment = Progress::find()->where(['id' => $id, 'speaker_id' => Yii::$app->user->identity->id])->one())
        {
            return $comment;
        }
    }
    
}