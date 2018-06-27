<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15 0015
 * Time: 14:00
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Znx extends ActiveRecord
{

    public function attributeLabels()
    {
        return [
            'username'=>"发送人",
            'name'=>"接收人",
            'theme'=>"主题",
            'content'=>"内容",
        ];
    }
    public function rules()
    {
        return [
            ['username','required'],
            ['name','required'],
            ['theme','required'],
            ['content','required'],
        ];
    }
}