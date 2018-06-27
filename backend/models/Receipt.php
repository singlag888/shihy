<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27 0027
 * Time: 14:01
 */

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * 收款账号模型
 * Class Receipt
 * @package backend\models
 */
class Receipt extends ActiveRecord
{

    public function attributeLabels()
    {
        return[
          'bank_name'=>"收款银行",
          'bank_number'=>"银行账号",
          'name'=>"银行名称",
          'username'=>"开户名",
          'text'=>"开户网点",
          'status'=>"是否启用",
          'intro'=>"备注",
        ];
    }

    public function rules()
    {
        return[
            [['bank_name','bank_number','name','username','text','status','intro'],'required','message'=>'不能为空'],
        ];
    }
}