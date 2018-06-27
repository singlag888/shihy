<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 18:15
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Withdrawals extends ActiveRecord
{
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'admin_id']);
    }

    public function getBank()
    {
        return $this->hasOne(BankList::className(),['id'=>'bank_id']);
    }

    public function getBanks()
    {
        return $this->hasOne(Bank::className(),['id'=>'bank_id']);
    }
}