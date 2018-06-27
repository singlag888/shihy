<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/25 0025
 * Time: 10:45
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Drawing extends ActiveRecord
{

    public function rules()
    {
        return[
            [['bank','time','price','status','before_price','last_price','num','username','admini_id'],'safe']
        ];
    }


    public function attributeLabels()
    {
        return [
            'bank'=>'银行名称',
            'time'=>'体现时间',
            'qishu'=>'期数',
            'status'=>'状态',
            'num'=>'体现单号',
            'before_price'=>'取款前金额',
            'last_price'=>'取款后金额',
            'price'=>'取款金额',
            'admini_id'=>'用户id',
            'username'=>'用户名称',
        ];
    }
}