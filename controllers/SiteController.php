<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
// use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
// use app\models\ContactForm;
use app\controllers\common\BaseController;
use app\models\models\Project;
use app\models\relations\ProjectUserRelations;

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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
        if(Yii::$app->user->isGuest)
        {
            return $this->redirect(['user/login']);
        }
        $my_id = Yii::$app->user->identity->id;
        if($projects = ProjectUserRelations::find()->where(['user_id' => $my_id])->select(['project_id'])->asArray()->all())
        {
//             var_dump($projects);
//             exit();
//             foreach ($projects as $p)
//             {
                
// //                 var_dump($p['project_id']);
//                 $model = Project::find()->where(['id' => $p['project_id']])->asArray()->one();
// //                 var_dump($model);
//                 for($i = 0; $i < count($projects); $i++)
//                 {
//                     $models[$i] = $model;
//                 }
            
//             }
//             $models = [];
            for($i = 0; $i < count($projects); $i++)
            {
                $project_id = $projects[$i]['project_id'];
                $model = Project::find()->where(['id' => $project_id])->asArray()->one();
                $models[$i] = $model;
            }
//             var_dump($models);
//             exit();
            return $this->render('index', [
                'models' => $models,
            ]);
        }
        return $this->redirect(['error/index']);
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

        return $this->goHome();
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
