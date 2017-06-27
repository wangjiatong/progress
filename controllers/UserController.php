<?php
namespace app\controllers;

use app\controllers\common\BaseController;
use app\models\forms\SignupForm;
use Yii;
use app\models\forms\LoginForm;
use yii\web\NotFoundHttpException;
use app\models\search\UserSearch;
use app\models\models\User;
use app\models\relations\UserCompanyRelations;
use app\models\models\UnreadMessage;
use app\models\relations\ProjectUserRelations;
use app\models\models\Project;
use app\models\models\Progress;
use yii\filters\AccessControl;

class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'acess' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    $this->sendSessionMessage('user_is_not_admin', '您不是管理员，无权操作！');
                    return $this->redirect(['error/index']);
                },
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],    
                    ],    
                    [
                        'actions' => ['manage', 'enable-user', 'disable-user', 'delete-user'],    
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action){
                            return $this->isAdmin();    
                        },
                    ],
                ],
            ],      
        ];
    }
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }
    public function actionSignup()
    {
        if(!Yii::$app->user->isGuest)
        {
            throw new NotFoundHttpException('您已经注册过了！');
        }
        $model = new SignupForm();
        if($model->load(Yii::$app->request->post()) && $model->signup())
        {
            $this->sendSessionMessage('signup_success', '注册成功！请联系管理员为您激活账号以便使用。');
            return $this->redirect(['user/login']);
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
    public function actionLogin()
    {
        if(!Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->login())
        {
            return $this->goHome();
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    //用户管理页面
    public function actionManage()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    //激活用户
    public function actionEnableUser($id)
    {
        if($this->isAdmin())
        {
            if(User::setStatus($id, 1))
            {
                $this->sendSessionMessage('user_enabled', '激活用户成功！');
            }
            return $this->redirect(['user/manage']);
        }
        $this->sendSessionMessage('no_user_enable_access', '您不是管理员，无激活用户的权限！');
    }
    //取消激活
    public function actionDisableUser($id)
    {
        if($this->isAdmin())
        {
            if(User::setStatus($id, 0))
            {
                $this->sendSessionMessage('user_disabled', '取消激活用户成功！');
            }
            return $this->redirect(['user/manage']);
        }
        $this->sendSessionMessage('no_user_disable_access', '您不是管理员，无取消激活用户的权限！');
    }
    
    public function actionDeleteUser($id)
    {
        if($this->isAdmin())
        {
            $user = $this->findModel($id);
            if($user->delete())
            {
                //如果被删除用户是某项目发起人，删除被删除用户发起的项目及与该项目有对应关系的
                if($project = Project::find()->where(['starter' => $id])->all())
                {
                    foreach ($project as $p)
                    {
                        //删除该项目的项目-用户对应关系
                        ProjectUserRelations::deleteAll('project_id=:id', [':id' => $p['id']]);
                        //删除该项目的所有进度汇报
                        Progress::deleteAll('project_id=:id', [':id' => $p['id']]);
                        //删除相关的未读消息列表
                        UnreadMessage::deleteAll('project_id=:id', [':id' => $p['id']]);
                        //删除项目
                        Project::findOne($p['id'])->delete();
                    }
                }                      
                
                //如果被删除用户是某项目参与人
                if($project_user = ProjectUserRelations::find()->where(['user_id' => $id])->all())
                {            
                    foreach ($project_user as $p)
                    {
                        $project = Project::findOne($p['project_id']);
                        $partner = explode(', ', $project->partner);
                        if(in_array($id, $partner))
                        {
                            //删除被删除用户的项目-用户关系
                            ProjectUserRelations::deleteAll('project_id=:project_id and user_id=:user_id', [':project_id' => $p['project_id'], ':user_id' => $id]);
                            //删除被删除用户的进度汇报
                            Progress::deleteAll('project_id=:project_id and speaker_id=:id', [':project_id' => $p['project_id'], ':id' => $id]);
                            //删除未读消息
                            UnreadMessage::deleteAll('project_id=:project_id and user_id=:id', [':project_id' => $p['project_id'], ':id' => $id]);
                            //更新项目参与人
                            $project->partner = implode(', ', $this->in_array_delete($id, $partner));
                            $project->save();
                        }
                    }
                }  
                //删除用户与公司的关系
                UserCompanyRelations::deleteAll('user_id=:id', [':id' => $id]);
                
                $this->sendSessionMessage('user_deleted', '删除用户成功！');
                return $this->redirect(['user/manage']);
            }
        }
        $this->sendSessionMessage('no_user_delete_access', '您不是管理员，无权删除用户！');
    }
    
    protected function findModel($id)
    {
        if($user = User::findOne($id))
        {
            return $user;
        }
    }
}