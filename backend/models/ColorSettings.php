<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 16:00
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ColorSettings extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'name'=>'彩种名称',
            'status'=>'彩种状态'
        ];
    }

    public function rules()
    {
        return[
            [['name','status'],'required']
        ];
    }
}