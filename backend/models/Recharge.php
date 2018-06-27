<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/25 0025
 * Time: 11:07
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Recharge extends  ActiveRecord
{
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'admin_id']);
    }

    public function getBank()
    {
        return $this->hasOne(BankList::className(),['id'=>'bank_id']);
    }
}