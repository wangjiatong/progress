<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
// use yii\web\Controller;
use yii\filters\VerbFilter;
// use app\models\LoginForm;
// use app\models\ContactForm;
use app\controllers\common\BaseController;
use app\models\models\Project;
use app\models\relations\ProjectUserRelations;
use yii\web\NotFoundHttpException;
use app\models\relations\NewProjectRelations;
use app\models\behaviors\ValidateAccountStatus;

class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        
        return [
            'access' => [
                'class' => AccessControl::className(),
//                 'only' => ['logout', 'index'],
                'denyCallback' => function($rule, $action){
                    $this->sendSessionMessage('user_is_not_active', '您还未登陆或账号尚未激活！');                   
                    return $this->redirect(['user/login']);
                },
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action){
                            return ValidateAccountStatus::isActive();
                        },
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],    
                    ],
                    [
                        'actions' => ['captcha'],
                        'allow' => true,
                        'roles' => ['?'],    
                    ],
                    [
                        'actions' => ['error'],
                        'allow' =>true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
//         if(Yii::$app->user->isGuest)
//         {
//             return $this->redirect(['user/login']);
//         }
        //查询用户名下是否有新的项目
        if($new_project = NewProjectRelations::find()->where(['user_id' => Yii::$app->user->identity->id, 'is_new' => 1])->all())
        {
            $this->sendSessionMessage('new_project', '您已参与新的项目，请到“查看项目”中浏览！');
        }
        
        
        
        
        $my_id = Yii::$app->user->identity->id;
        if($projects = ProjectUserRelations::find()->where(['user_id' => $my_id])->select(['project_id'])->orderBy('id desc')->asArray()->all())
        {
            for($i = 0; $i < count($projects); $i++)
            {
                $project_id = $projects[$i]['project_id'];
                $model = Project::find()->where(['id' => $project_id])->asArray()->one();
                $models[$i] = $model;
            }
            return $this->render('newMessage', [
                'models' => $models,
            ]);
            
        }elseif(!$projects){
            return $this->render('index');
        }else{
            throw new NotFoundHttpException();
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
//     public function actionLogin()
//     {
//         if (!Yii::$app->user->isGuest) {
//             return $this->goHome();
//         }

//         $model = new LoginForm();
//         if ($model->load(Yii::$app->request->post()) && $model->login()) {
//             return $this->goBack();
//         }
//         return $this->render('login', [
//             'model' => $model,
//         ]);
//     }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['user/login']);
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
//     public function actionContact()
//     {
//         $model = new ContactForm();
//         if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//             Yii::$app->session->setFlash('contactFormSubmitted');

//             return $this->refresh();
//         }
//         return $this->render('contact', [
//             'model' => $model,
//         ]);
//     }

    /**
     * Displays about page.
     *
     * @return string
     */
//     public function actionAbout()
//     {
//         return $this->render('about');
//     }
}
