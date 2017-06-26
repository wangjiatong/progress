<?php
use yii\helpers\Html;
use app\models\models\User;
use app\models\relations\UserCompanyRelations;
use app\models\models\Company;
use app\controllers\common\BaseController;
$partner = explode(', ', $project->partner);


?>
<table>
    <tr>
        <td>
        <?= Html::a('添加参与人', ['project/partner-add', 'id' => $project->id], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => '确定要添加参与人吗？',
            ]
        ]) ?>
        </td>
        <td>
        <?= Html::a('取消', ['project/detail', 'id' => $project->id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定要取消管理参与人吗？',
            ]
        ]) ?>
        </td>
    </tr>
</table>
<br />
<?php BaseController::displaySessionMessage('partner_deleted') ?>
<?php BaseController::displaySessionMessage('no_new_partner', 'danger') ?>
<?php BaseController::displaySessionMessage('new_partner_added') ?>
<div>
    <h4>管理参与人</h3>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>姓名</th>
            <th>所属公司</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1?>
        <?php foreach ($partner as $p): ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= User::findOne($p)->name ?></td>
            <?php 
                $user_company = UserCompanyRelations::findOne(['user_id' => $p]);
                $company_id = $user_company->company_id;
            ?>
            <td><?= Company::findOne($company_id)->company_name ?></td>
            <?php if($p !== $project->starter): ?>
            <td>
                <?= Html::a('删除', ['project/delete-partner', 'user_id' => $p, 'project_id' => $project->id], [
                        'class' => 'btn btn-danger btn-xs',
                        'data' => [ 
                            'confirm' => '你确定要删除该参与人吗？',
                        ],
                    ]); 
                ?>
            </td>
            <?php $i++ ?>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>