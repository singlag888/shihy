<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 9:59
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Bank extends ActiveRecord
{
    //根据ID关联用户名
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'admin_id']);
    }
    //根据银行ID关联银行
    public function getBank()
    {
        return $this->hasOne(BankList::className(),['id'=>'bank_id']);
    }


    public function attributeLabels()
    {
        return [
            'admin_id'=>"用户ID",
            'bank_number'=>"银行卡号",
            'username'=>"开户名",
            'address'=>"开户地址",
            'bank_id'=>"银行ID",
            'status'=>"状态",
        ];
    }
    public function rules()
    {
        return [
            ['bank_number','required'],
            ['address','required'],
            ['username','required'],
            ['bank_id','required'],
            ['status','required'],
        ];
    }
}