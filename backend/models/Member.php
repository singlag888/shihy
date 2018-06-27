<?php

namespace backend\models;

use yii\db\ActiveRecord;

class  Member extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'baby_name', 'baby_age'], 'required', 'message' => '此项不能为,空必填!'],
            // [['phone'], 'integer','message'=>'手机号必须是整数'],
            ['phone', 'match', 'pattern' => '/^[1][3,4,5,7,8][0-9]{9}$/'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '家长名称',
            'phone' => '家长手机号',
            'baby_name' => '宝宝名字',
            'baby_age' => '宝宝年龄',
        ];
    }
}