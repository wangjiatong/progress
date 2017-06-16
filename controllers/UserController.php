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

class UserController extends BaseController
{
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
                UserCompanyRelations::deleteAll('user_id=:id', [':id' => $id]);
                UnreadMessage::deleteAll('user_id=:id', [':id' => $id]);
                Project::deleteAll('starter=:id', [':id' => $id]);
                Progress::deleteAll('speaker_id=:id', [':id' => $id]);
                
                $project_user = ProjectUserRelations::find()->where(['user_id' => $id])->all();
                
                foreach ($project_user as $p)
                {
                    $project = Project::findOne($p);
                    $partner = explode(', ', $project->partner);
                    if(!in_array($p, $partner))
                    {
                        continue;
                    }else{
                        $project->partner = implode(', ', $this->in_array_delete($p, $partner));
                        $project->save();
                    }
                }    
                ProjectUserRelations::deleteAll('user_id=:id', [':id' => $id]);
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