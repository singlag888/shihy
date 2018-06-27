<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24 0024
 * Time: 11:17
 */

namespace backend\models;


use yii\db\ActiveRecord;

class White extends ActiveRecord
{
    public function rules()
    {
        return [
            ['ip', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ip'=>'访问Ip',
        ];
    }
}