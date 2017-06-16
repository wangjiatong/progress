<?php

namespace app\models\relations;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_company".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 */
class UserCompanyRelations extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_company';
    }

    /**
     * @inheritdoc
     */
//     public function rules()
//     {
//         return [
//             [['user_id', 'company_id'], 'required'],
//             [['user_id', 'company_id'], 'integer'],
//         ];
//     }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户_公司-主键',
            'user_id' => '用户id',
            'company_id' => '公司id',
        ];
    }
}
