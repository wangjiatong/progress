<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\models\User;
use app\controllers\common\BaseController;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-manage">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= BaseController::displaySessionMessage('user_enabled') ?>
    <?= BaseController::displaySessionMessage('user_disabled') ?>
    <?= BaseController::displaySessionMessage('user_deleted') ?>

<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name', 
            [
                'label' => '激活',
                'format' => 'raw',
                'value' => function($data){
                    if($data->status == 0)
                    {
                        return Html::a('激活', ['user/enable-user', 'id' => $data->id], [
                            'class' => 'btn btn-success btn-xs',
                            'data' => [
                                'confirm' => '你确定要激活该用户吗？',
                                'method' => 'post',
                            ],
                        ]);
                    }else{
                        return User::userStatus($data->status);
                    }
                }
            ],
            [
                'label' => '取消激活',
                'format' => 'raw',
                'value' => function($data){
                    if($data->status == 1)
                    {
                        return Html::a('取消激活', ['user/disable-user', 'id' => $data->id], [
                            'class' => 'btn btn-primary btn-xs',
                            'data' => [
                                'confirm' => '你确定要取消激活该用户吗？',
                                'method' => 'post',
                            ],
                        ]);
                    }else{
                        return User::userStatus($data->status);
                    }
                }
            ],
            [
                'label' => '删除',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a('删除', ['user/delete-user', 'id' => $data->id], [
                        'class' => 'btn btn-danger btn-xs',
                        'data' => [
                            'confirm' => '你确定要删除该用户吗？',
                            'method' => 'post',
                        ],    
                    ]);
                },                    
            ],       
        ],
    ]); ?> 
<?php Pjax::end(); ?></div>
