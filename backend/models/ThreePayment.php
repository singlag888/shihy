<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 17:04
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ThreePayment extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>"简介",
            'number'=>"商户号",
            'alias'=>"商户别名",
            'type'=>"所属分类",
            'in_status'=>"可充值",
            'out_status'=>"可下发",
        ];
    }
    public function rules()
    {
        return [
            ['name','required'],
            ['number','required'],
            ['alias','required'],
            ['type','required'],
            ['in_status','required'],
            ['out_status','required'],
        ];
    }
}