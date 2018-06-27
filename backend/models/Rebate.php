<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/24 0024
 * Time: 10:16
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Rebate extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'brokerage', 'rebate'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'brokerage'=>'佣金',
            'rebate'=>'返点',

        ];
    }
}