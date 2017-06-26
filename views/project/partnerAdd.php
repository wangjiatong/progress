<?php
use app\models\models\User;
use app\models\relations\UserCompanyRelations;
use app\models\models\Company;
use yii\helpers\Html;

?>
<div>
    <?= Html::a('取消', ['project/partner', 'id' => $id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => '确定要取消添加参与人吗？',
            ]
        ]) 
    ?>
</div>
<br />
<div>
    <h4>添加参与人</h3>
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
      <?php foreach ($newPartner as $p): ?>
      <tr>
        <?php $i = 1; ?>
        <td><?= $i ?></td>
        <td><?= User::findOne($p)->name ?></td>
        <?php 
            $user_company = UserCompanyRelations::findOne(['user_id' => $p]);
            $company_id = $user_company->company_id;
        ?>
        <td><?= Company::findOne($company_id)->company_name ?></td>
        <td>
            <?= Html::a('添加', ['project/add-partner', 'user_id' => $p, 'project_id' => $id], [
                    'class' => 'btn btn-success btn-xs',
                    'data' => [ 
                        'confirm' => '你确定要添加该参与人吗？',
                    ],
                ]); 
            ?>
        </td>
      </tr>
      <?php $i++; ?>
      <?php endforeach; ?>
    </tbody>
</table>
        