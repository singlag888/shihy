<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 17:28
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public function attributeLabels()
    {
        return [
            'content'=>"简介",
            'title'=>"标题",
            'status'=>"状态",
        ];
    }
    public function rules()
    {
        return [
            ['content','required'],
            ['title','required'],
            ['status','required'],
        ];
    }
}